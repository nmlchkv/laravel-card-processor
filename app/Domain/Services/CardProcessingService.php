<?php

namespace App\Domain\Services;

use App\Domain\ValueObjects\CardInfo;
use App\Domain\ValueObjects\CardNumber;

class CardProcessingService
{
    public function __construct(
        private BinLookupServiceInterface $binLookupService
    ) {}

    public function processCard(CardNumber $cardNumber): CardInfo
    {
        try {
            $lookupResult = $this->binLookupService->lookup($cardNumber->getValue());
            
            return new CardInfo(
                scheme: $lookupResult['scheme'] ?? 'unknown',
                brand: $lookupResult['brand'] ?? 'unknown',
                bank: $lookupResult['bank'] ?? 'unknown',
                bin: $cardNumber->getBin()
            );
        } catch (\Exception $e) {
            return CardInfo::unknown($cardNumber->getBin());
        }
    }
}

