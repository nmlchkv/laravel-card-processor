<?php

namespace App\Domain\ValueObjects;

use App\Domain\Constants\AppConstants;

enum CardScheme: string
{
    case VISA = 'visa';
    case MASTERCARD = 'mastercard';
    case AMEX = 'amex';
    case UNKNOWN = 'unknown';
    
    public static function fromPrefix(string $prefix): self
    {
        return match ($prefix) {
            AppConstants::VISA_PREFIX => self::VISA,
            AppConstants::MASTERCARD_PREFIX => self::MASTERCARD,
            AppConstants::AMEX_PREFIX => self::AMEX,
            default => self::UNKNOWN
        };
    }
}

