<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;

class HealthController extends Controller
{
    public function __invoke()
    {
        return response()->json([
            'status' => 'ok',
            'service' => 'sltc-api',
            'version' => 'v1',
            'timestamp' => now()->toIso8601String(),
        ]);
    }
}
