<?php

namespace App\Enum;

enum EnumOrderStatus: string
{
    case ToPrepare = 'to_prepare';
    case Ready = 'ready';
    case Collected = 'collected';


    public function label(): string
    {
        return match ($this) {
            self::ToPrepare => 'À préparer',
            self::Ready => 'Prête',
            self::Collected => 'Retirée',
        };
    }
}
