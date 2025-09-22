<?php

namespace Tests\Unit;

use App\Domain\ValueObjects\CardNumber;
use App\Domain\Constants\AppConstants;
use App\Domain\ValueObjects\CardScheme;
use PHPUnit\Framework\TestCase;

class CardNumberTest extends TestCase
{
    public function test_valid_card_number_creates_instance(): void
    {
        $cardNumber = new CardNumber('4276874587654567');
        
        $this->assertEquals('4276874587654567', $cardNumber->getValue());
        $this->assertEquals('427687', $cardNumber->getBin());
        $this->assertEquals(CardScheme::VISA->value, $cardNumber->getScheme());
    }

    public function test_invalid_card_number_throws_exception(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        
        new CardNumber('123');
    }

    public function test_mastercard_scheme_detection(): void
    {
        $cardNumber = new CardNumber('5555555555554444');
        
        $this->assertEquals(CardScheme::MASTERCARD->value, $cardNumber->getScheme());
    }

    public function test_amex_scheme_detection(): void
    {
        $cardNumber = new CardNumber('378282246310005');
        
        $this->assertEquals(CardScheme::AMEX->value, $cardNumber->getScheme());
    }

    public function test_unknown_scheme_detection(): void
    {
        $cardNumber = new CardNumber('1234567890123456');
        
        $this->assertEquals(CardScheme::UNKNOWN->value, $cardNumber->getScheme());
    }

    public function test_bin_length_constant(): void
    {
        $this->assertEquals(6, AppConstants::BIN_LENGTH);
    }

    public function test_scheme_prefix_length_constant(): void
    {
        $this->assertEquals(1, AppConstants::SCHEME_PREFIX_LENGTH);
    }
}
