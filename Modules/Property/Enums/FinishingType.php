<?php

namespace Modules\Property\Enums;

enum FinishingType: string
{
    case CoreAndShell = 'core_and_shell';
    case SemiFinished = 'semi_finished';
    case FullyFinished = 'fully_finished';
    case Furnished = 'furnished';

    public function label(): string
    {
        return match ($this) {
            self::CoreAndShell => __('Core & shell'),
            self::SemiFinished => __('Semi-finished'),
            self::FullyFinished => __('Fully finished'),
            self::Furnished => __('Furnished'),
        };
    }

    /** @return list<array{id: string, name: string}> */
    public static function options(): array
    {
        return array_map(fn (self $finishing) => ['id' => $finishing->value, 'name' => $finishing->label()], self::cases());
    }
}
