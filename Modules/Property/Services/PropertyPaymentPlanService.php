<?php

namespace Modules\Property\Services;

use Modules\Property\Models\Property;

final class PropertyPaymentPlanService
{
    /**
     * A property whose purpose has no plans (Rent) ends up with none.
     *
     * @param list<array{down_payment_percent: int, installment_years: int, installment_frequency: string}> $plans
     */
    public function sync(Property $property, array $plans): void
    {
        $property->paymentPlans()->delete();

        if ($property->purpose->allowsPaymentPlans()) {
            $property->paymentPlans()->createMany($plans);
        }
    }

    public function clear(Property $property): void
    {
        $property->paymentPlans()->delete();
    }
}
