<?php

namespace App\Domain\ValueObjects;

use App\Domain\Constants\AppConstants;
use App\Domain\ValueObjects\CardScheme;

class CardNumber
{
    public function __construct(
        private readonly string $value
    ) {
        if (!$this->isValid($value)) {
            throw new \InvalidArgumentException('Invalid card number format');
        }
    }

    public function getValue(): string
    {
        return $this->value;
    }

    public function getBin(): string
    {
        return substr($this->value, 0, AppConstants::BIN_LENGTH);
    }

    public function getScheme(): string
    {
        $prefix = substr($this->value, 0, AppConstants::SCHEME_PREFIX_LENGTH);
        return CardScheme::fromPrefix($prefix)->value;
    }

    private function isValid(string $value): bool
    {
        return preg_match(AppConstants::CARD_NUMBER_PATTERN, $value);
    }
}