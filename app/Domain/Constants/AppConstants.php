<?php

namespace App\Domain\Constants;

class AppConstants
{
    public const BIN_LENGTH = 6;
    public const SCHEME_PREFIX_LENGTH = 1;
    public const MIN_LENGTH = 13;
    public const MAX_LENGTH = 19;
    
    public const VISA_PREFIX = '4';
    public const MASTERCARD_PREFIX = '5';
    public const AMEX_PREFIX = '3';
    
    public const VISA_SCHEME = 'visa';
    public const MASTERCARD_SCHEME = 'mastercard';
    public const AMEX_SCHEME = 'amex';
    public const UNKNOWN_SCHEME = 'unknown';
    
    public const VISA_BRAND = 'visa electron';
    public const MASTERCARD_BRAND = 'standard';
    public const AMEX_BRAND = 'amex';
    public const UNKNOWN_BRAND = 'unknown';
    
    public const SBERBANK = 'sberbank';
    public const VTB = 'vtb';
    public const UNKNOWN_BANK = 'unknown';
    
    public const CARD_NUMBER_PATTERN = '/^\d{13,19}$/';
    
    public const STATUS_QUEUED = 'queued';
    public const STATUS_PROCESSING = 'processing';
    public const STATUS_COMPLETED = 'completed';
    public const STATUS_FAILED = 'failed';
    
    public const INPUT_FILENAME = 'input.xlsx';
    public const OUTPUT_FILENAME = 'output.xlsx';
    
    public const HTTP_TIMEOUT = 5;
    public const CALLBACK_SIGNATURE_HEADER = 'X-Callback-Signature';
    public const UPLOAD_TOKEN_HEADER = 'X-Upload-Token';
    public const HASH_ALGORITHM = 'sha256';
    public const TEMPORARY_URL_LIFETIME_MINUTES = 30;
    
    public const BINLIST_API_URL = 'https://lookup.binlist.net/';
}

