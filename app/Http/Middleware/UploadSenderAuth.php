<?php

namespace App\Http\Middleware;

use App\Domain\Constants\AppConstants;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class UploadSenderAuth
{
    public function handle(Request $request, Closure $next): Response
    {
        $token = $request->header(AppConstants::UPLOAD_TOKEN_HEADER);
        $expected = config('app.upload_sender_token');

        if (!$token || !hash_equals((string) $expected, (string) $token)) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        return $next($request);
    }
}


