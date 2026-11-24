<?php

namespace Modules\Users\Enums;

enum AccountStatus: string
{
    case Active = 'active';
    case Disabled = 'disabled';

    public static function of(bool $isActive): self
    {
        return $isActive ? self::Active : self::Disabled;
    }

    public function isActive(): bool
    {
        return $this === self::Active;
    }

    public function label(): string
    {
        return match ($this) {
            self::Active => __('Active'),
            self::Disabled => __('Disabled'),
        };
    }

    public function tone(): string
    {
        return match ($this) {
            self::Active => 'success',
            self::Disabled => 'neutral',
        };
    }

    public static function options(): array
    {
        return array_map(
            fn (self $status) => ['id' => $status->value, 'name' => $status->label(), 'tone' => $status->tone()],
            self::cases()
        );
    }
}
