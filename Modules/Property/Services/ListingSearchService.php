<?php

namespace Modules\Property\Services;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Modules\Property\Enums\PaymentType;
use Modules\Property\Models\Property;
use Modules\Property\Models\PropertyPaymentPlan;

final class ListingSearchService
{
    private const PER_PAGE = 10;

    /** Filter key → column for the plain "equals" filters. */
    private const COLUMNS = [
        'purpose' => 'purpose',
        'type' => 'property_type_id',
        'city' => 'city_id',
        'area' => 'area_id',
        'classification' => 'property_classification_id',
        'country' => 'country_id',
        'finishing' => 'finishing',
    ];

    /** @param array<string, mixed> $filters search, purpose, type, city, area, classification, country, payment, max_monthly, max_down_payment_percent, finishing, delivered_by */
    public function paginate(array $filters): LengthAwarePaginator
    {
        $query = Property::query()
            ->active()
            ->with(['propertyType', 'paymentPlans', 'owner:id,name,avatar,phone,phone_verified_at,whatsapp_phone,created_at'])
            ->when($filters['search'], fn (Builder $query, string $search) => $query->where('name', 'like', "%{$search}%"));

        foreach (self::COLUMNS as $key => $column) {
            $query->when($filters[$key], fn (Builder $query, $value) => $query->where($column, $value));
        }

        $query->when($filters['delivered_by'], fn (Builder $query, int $year) => $query->deliveredBy($year));

        return $this->byPayment($query, $filters)
            ->paginate(self::PER_PAGE)
            ->withQueryString();
    }

    /** The budget filters only match when one plan fits both the monthly and the down payment limit. */
    private function byPayment(Builder $query, array $filters): Builder
    {
        ['payment' => $payment, 'max_monthly' => $maxMonthly, 'max_down_payment_percent' => $maxDownPercent] = $filters;

        if ($payment === PaymentType::Cash) {
            return $query->whereDoesntHave('paymentPlans');
        }

        if ($payment !== PaymentType::Installments && $maxMonthly === null && $maxDownPercent === null) {
            return $query;
        }

        $price = $query->qualifyColumn('price');

        return $query->whereHas('paymentPlans', fn (Builder $plans) => $plans
            ->when($maxMonthly !== null, fn (Builder $plans) => $this->monthlyAtMost($plans, $price, $maxMonthly))
            ->when($maxDownPercent !== null, fn (Builder $plans) => $plans->where('down_payment_percent', '<=', $maxDownPercent)));
    }

    /** monthly = price × (100 − dp%) ÷ 100 ÷ (years × 12), multiplied out so the database never divides. */
    private function monthlyAtMost(Builder $plans, string $price, int $max): Builder
    {
        return $plans->whereRaw(
            "{$price} * (100 - down_payment_percent) <= ? * installment_years",
            [$max * 100 * PropertyPaymentPlan::MONTHS_PER_YEAR],
        );
    }
}
