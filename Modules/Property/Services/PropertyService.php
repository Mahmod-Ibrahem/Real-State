<?php

namespace Modules\Property\Services;

use App\Models\User;
use App\Traits\ImagesUtility;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Arr;
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
    private const NON_COLUMN_KEYS = ['image', 'feature_ids', 'gallery_images', 'removed_image_ids', 'locale'];
    private const EDIT_RELATIONS = ['features', 'propertyType', 'propertyClassification', 'city', 'area', 'country', 'propertyImages'];

    public function __construct(
        private readonly PropertyGalleryService $gallery,
        private readonly PropertyPublicationService $publication,
        private readonly PropertyLeadService $leads,
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
            $property = new Property($this->columns($data));
            $property->image = $this->storeImage($data['image'], self::IMAGE_TYPE);
            $property->forceFill([
                'owner_id' => $owner->id,
                'review_status' => $reviewStatus,
                'reviewed_by' => $reviewStatus === ReviewStatus::Approved ? $owner->id : null,
                'reviewed_at' => $reviewStatus === ReviewStatus::Approved ? now() : null,
            ])->save();

            $property->features()->sync($data['feature_ids']);
            $this->gallery->add($property, $data['gallery_images'] ?? []);

            return $property;
        });
    }

    public function update(Property $property, array $data): bool
    {
        return DB::transaction(function () use ($property, $data): bool {
            $property->fill($this->columns($data));
            if (isset($data['image'])) {
                $property->image = $this->storeImage($data['image'], self::IMAGE_TYPE);
            }
            $columnsChanged = $property->isDirty();
            $property->save();

            $featureChanges = $property->features()->sync($data['feature_ids']);
            $removedImages = $this->gallery->remove($property, $data['removed_image_ids'] ?? []);
            $addedImages = $this->gallery->add($property, $data['gallery_images'] ?? []);

            return $columnsChanged
                || array_filter($featureChanges) !== []
                || $removedImages > 0
                || $addedImages > 0;
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

        $property->save();
    }

    public function delete(Property $property): void
    {
        if ($this->leads->existsFor($property)) {
            throw PropertyHasLeads::make();
        }

        $property->delete();
    }

    private function columns(array $data): array
    {
        return Arr::except($data, self::NON_COLUMN_KEYS);
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
