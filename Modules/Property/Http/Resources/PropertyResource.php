<?php

namespace Modules\Property\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PropertyResource extends JsonResource
{
    public static $wrap = false;

    public function toArray(Request $request): array
    {
        $locale = $request->input('locale', 'en');

        return [
            'id' => $this->id,
            'name' => $this->getTranslation('name', $locale, $this->name),
            'description' => $this->getTranslation('description', $locale, $this->description),
            'address' => $this->getTranslation('address', $locale, $this->address),
            'price' => $this->price,
            'maintenance_deposit_percent' => $this->maintenance_deposit_percent,
            'payment_plans' => $this->whenLoaded('paymentPlans', fn () => $this->paymentPlans->map(fn ($plan) => [
                'down_payment_percent' => $plan->down_payment_percent,
                'installment_years' => $plan->installment_years,
                'installment_frequency' => $plan->installment_frequency->value,
            ])),
            'image' => $this->image,
            'bedrooms' => $this->bedrooms,
            'bathrooms' => $this->bathrooms,
            'garages' => $this->garages,
            'year_built' => $this->year_built,
            'finishing' => $this->finishing->value,
            'delivery_year' => $this->delivery_year,
            'sqft' => $this->sqft,
            'floor' => $this->floor,
            'google_map' => $this->google_map,
            'is_published' => $this->is_published,
            'feature_ids' => $this->features->pluck('id'),
            'property_type_id' => $this->property_type_id,
            'property_classification_id' => $this->property_classification_id,
            'purpose' => $this->purpose->value,
            'status' => $this->status->value,
            'review_status' => $this->review_status->value,
            'rejection_reason' => $this->rejection_reason,
            'country_id' => $this->country_id,
            'city_id' => $this->city_id,
            'area_id' => $this->area_id,
        ];
    }
}
