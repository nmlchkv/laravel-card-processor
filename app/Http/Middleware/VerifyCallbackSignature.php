<?php

namespace App\Http\Middleware;

use App\Domain\Constants\AppConstants;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class VerifyCallbackSignature
{
    public function handle(Request $request, Closure $next): Response
    {
        $signature = $request->header(AppConstants::CALLBACK_SIGNATURE_HEADER);
        $payload = (string) $request->getContent();
        $secret = config('app.callback_signing_secret');

        $expected = base64_encode(hash_hmac(AppConstants::HASH_ALGORITHM, $payload, (string) $secret, true));

        if (!$signature || !hash_equals($expected, $signature)) {
            return response()->json(['message' => 'Invalid signature'], 403);
        }

        return $next($request);
    }
}


