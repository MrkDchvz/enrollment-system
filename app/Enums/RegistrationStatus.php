<?php

namespace App\Enums;

use BladeUI\Icons\Components\Icon;
use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasIcon;
use Filament\Support\Contracts\HasLabel;

enum RegistrationStatus: string implements HasIcon, HasLabel, HasColor
{
    case Irregular = 'IRREGULAR';
    case Regular = 'REGULAR';

    public function getLabel(): string {
        return match ($this) {
            self::Irregular => 'Irregular',
            self::Regular => 'Regular',
        };
    }
    public function getColor(): string {
        return match ($this) {
            self::Irregular => 'info',
            self::Regular => 'success',
        };
    }
    public function getIcon(): string
    {
        return match ($this) {
            self::Irregular => 'heroicon-o-arrow-path-rounded-square',
            self::Regular => 'heroicon-o-check',
        };
    }

}
