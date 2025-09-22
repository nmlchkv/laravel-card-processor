<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class UploadCallbackController extends Controller
{
    public function handle(Request $request): Response
    {
        return response()->noContent();
    }
}


