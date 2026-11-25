<?php

namespace Modules\Property\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Modules\Property\Enums\InstallmentFrequency;

class PropertyPaymentPlan extends Model
{
    public const MONTHS_PER_YEAR = 12;

    protected $fillable = [
        'down_payment_percent',
        'installment_years',
        'installment_frequency',
    ];

    protected function casts(): array
    {
        return [
            'installment_frequency' => InstallmentFrequency::class,
            'down_payment_percent' => 'integer',
            'installment_years' => 'integer',
        ];
    }

    public function property(): BelongsTo
    {
        return $this->belongsTo(Property::class);
    }

    public function downPaymentAmount(int $price): int
    {
        return (int) round($price * $this->down_payment_percent / 100);
    }

    public function installmentAmount(int $price): int
    {
        return (int) round($this->financedAmount($price) / ($this->installment_years * $this->installment_frequency->paymentsPerYear()));
    }

    public function monthlyInstallment(int $price): int
    {
        return (int) round($this->financedAmount($price) / ($this->installment_years * self::MONTHS_PER_YEAR));
    }

    private function financedAmount(int $price): int
    {
        return $price - $this->downPaymentAmount($price);
    }
}
