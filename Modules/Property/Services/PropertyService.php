<?php

namespace Modules\Property\Services;

use App\Models\User;
use App\Traits\ImagesUtility;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;
use Modules\Lead\Services\PropertyLeadService;
use Modules\Property\Enums\PropertyStatus;
use Modules\Property\Exceptions\PropertyHasLeads;
use Modules\Property\Enums\ReviewStatus;
use Modules\Property\Models\Property;

final class PropertyService
{
    use ImagesUtility;

    private const PER_PAGE = 10;
    private const IMAGE_TYPE = 'property';
    private const EDIT_RELATIONS = ['features', 'propertyType', 'propertyClassification', 'city', 'area', 'country', 'propertyImages', 'paymentPlans'];

    public function __construct(
        private readonly PropertyGalleryService $gallery,
        private readonly PropertyPublicationService $publication,
        private readonly PropertyLeadService $leads,
        private readonly PropertyPaymentPlanService $paymentPlans,
    ) {
    }

    public function paginateForAdmin(?string $search, ?ReviewStatus $reviewStatus): LengthAwarePaginator
    {
        return Property::query()
            ->with(['city', 'area', 'propertyType', 'owner'])
            ->when($search, fn (Builder $query) => $query->where(fn (Builder $query) => $this->search($query, $search)))
            ->when($reviewStatus, fn (Builder $query) => $query->where('review_status', $reviewStatus))
            ->latest()
            ->paginate(self::PER_PAGE)
            ->withQueryString();
    }

    public function loadForEdit(Property $property): Property
    {
        return $property->load(self::EDIT_RELATIONS);
    }

    public function create(array $data, User $owner, ReviewStatus $reviewStatus): Property
    {
        return DB::transaction(function () use ($data, $owner, $reviewStatus): Property {
            $property = new Property($data);
            $this->dropSaleOnlyTerms($property);
            $property->image = $this->storeImage($data['image'], self::IMAGE_TYPE);
            $property->forceFill([
                'owner_id' => $owner->id,
                'review_status' => $reviewStatus,
                'reviewed_by' => $reviewStatus === ReviewStatus::Approved ? $owner->id : null,
                'reviewed_at' => $reviewStatus === ReviewStatus::Approved ? now() : null,
            ])->save();

            $property->features()->sync($data['feature_ids']);
            $this->gallery->add($property, $data['gallery_images'] ?? []);
            $this->paymentPlans->sync($property, $data['payment_plans'] ?? []);

            return $property;
        });
    }

    public function update(Property $property, array $data): void
    {
        DB::transaction(function () use ($property, $data): void {
            $property->fill($data);
            $this->dropSaleOnlyTerms($property);
            if (isset($data['image'])) {
                $property->image = $this->storeImage($data['image'], self::IMAGE_TYPE);
            }
            $property->save();

            $property->features()->sync($data['feature_ids']);
            $this->gallery->remove($property, $data['removed_image_ids'] ?? []);
            $this->gallery->add($property, $data['gallery_images'] ?? []);
            $this->paymentPlans->sync($property, $data['payment_plans'] ?? []);
        });
    }

    public function updateListingState(Property $property, array $state): void
    {
        $property->fill($state);

        if ($property->isDirty('is_published') && $property->is_published) {
            $this->publication->ensurePublishable($property);
        }

        if (!in_array($property->status, PropertyStatus::allowedFor($property->purpose), true)) {
            $property->status = PropertyStatus::Available;
        }

        $this->dropSaleOnlyTerms($property);

        DB::transaction(function () use ($property): void {
            $property->save();

            if (! $property->purpose->allowsPaymentPlans()) {
                $this->paymentPlans->clear($property);
            }
        });
    }

    public function delete(Property $property): void
    {
        if ($this->leads->existsFor($property)) {
            throw PropertyHasLeads::make();
        }

        DB::transaction(function () use ($property): void {
            $this->paymentPlans->clear($property);
            $property->delete();
        });
    }

    private function dropSaleOnlyTerms(Property $property): void
    {
        if (! $property->purpose->allowsPaymentPlans()) {
            $property->maintenance_deposit_percent = null;
        }
    }

    private function search(Builder $query, string $term): void
    {
        $query->where('name', 'like', "%{$term}%")->orWhere('address', 'like', "%{$term}%");

        if (is_numeric($term)) {
            $query->orWhere('price', $term)
                ->orWhere('sqft', $term)
                ->orWhere('bedrooms', $term)
                ->orWhere('bathrooms', $term);
        }
    }
}
