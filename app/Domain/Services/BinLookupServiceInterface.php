<?php

namespace App\Domain\Services;

interface BinLookupServiceInterface
{
    public function lookup(string $cardNumber): array;
}

