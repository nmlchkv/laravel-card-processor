<?php

namespace App\Domain\ValueObjects;

class CardInfo
{
    public function __construct(
        public readonly string $scheme,
        public readonly string $brand,
        public readonly string $bank,
        public readonly string $bin
    ) {}

    public static function unknown(string $bin): self
    {
        return new self('unknown', 'unknown', 'unknown', $bin);
    }
}

