<?php

// app/Http/Middleware/LogActivity.php

namespace App\Http\Middleware;

use App\Models\Log;
use Closure;
use Illuminate\Http\Request;
use PHPOpenSourceSaver\JWTAuth\Facades\JWTAuth;

class LogActivity
{
    public function handle(Request $request, Closure $next)
    {
        $response = $next($request);

        try {
            $user = JWTAuth::parseToken()->authenticate();
            $userId = $user?->id;
        } catch (\Exception $e) {
            $userId = null;
        }

        Log::create([
            'user_id' => $userId,
            'method' => $request->method(),
            'endpoint' => $request->path(),
            'status_code' => $response->getStatusCode(),
            'ip_address' => $request->ip(),
            'request_body' => json_encode($request->except(['password'])),
            'response_body' => $response->getContent(),
        ]);

        return $response;
    }
}
