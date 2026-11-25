<?php

namespace Modules\Property\Http\Resources\Users;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Modules\Property\Models\PropertyPaymentPlan;

class PropertyResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */

    public static $wrap = false;

    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'slug' => $this->slug,
            'name' => $this->name,
            'description' => str_replace('&nbsp;', ' ', strip_tags($this->description)),
            'sqft' => $this->sqft,
            'floor' => $this->floor,
            'bedrooms' => $this->bedrooms,
            'bathrooms' => $this->bathrooms,
            'price' => $this->price,
            'lowest_monthly_installment' => $this->whenLoaded('paymentPlans', fn () => $this->paymentPlans
                ->min(fn (PropertyPaymentPlan $plan) => $plan->monthlyInstallment((int) $this->price))),
            'payment_plans' => $this->whenLoaded('paymentPlans', fn () => $this->paymentPlans
                ->map(fn (PropertyPaymentPlan $plan) => $this->plan($plan, (int) $this->price)), []),
            'maintenance_deposit_percent' => $this->maintenance_deposit_percent,
            'maintenance_deposit_amount' => $this->maintenance_deposit_percent === null
                ? null
                : (int) round($this->price * $this->maintenance_deposit_percent / 100),
            'image_url' => $this->image,
            'status' => $this->status->badge(),
            'year_built' => $this->year_built,
            'finishing' => ['id' => $this->finishing->value, 'name' => $this->finishing->label()],
            'delivery' => ['ready' => $this->isReady(), 'year' => $this->isReady() ? null : $this->delivery_year],
            'google_map' => $this->google_map,
            'owner' => OwnerResource::make($this->whenLoaded('owner')),
            'owner_contact' => $this->whenLoaded('owner', fn () => [
                'call' => $this->owner?->phone !== null,
                'whatsapp' => $this->owner?->whatsapp_phone !== null,
            ], ['call' => false, 'whatsapp' => false]),
            'propertyImages' => $this->propertyImages,
            'features' => $this->features->map(fn($feature) => [
                'name' => $feature->name
            ]),
        ];
    }

    private function plan(PropertyPaymentPlan $plan, int $price): array
    {
        return [
            'down_payment_percent' => $plan->down_payment_percent,
            'down_payment_amount' => $plan->downPaymentAmount($price),
            'installment_years' => $plan->installment_years,
            'installment_frequency' => $plan->installment_frequency->value,
            'installment_frequency_label' => $plan->installment_frequency->label(),
            'installment_amount' => $plan->installmentAmount($price),
            'monthly_installment' => $plan->monthlyInstallment($price),
        ];
    }
}
