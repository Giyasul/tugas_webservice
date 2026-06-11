<?php

use App\Http\Middleware\LogActivity;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;
use PHPOpenSourceSaver\JWTAuth\Exceptions\JWTException;
use PHPOpenSourceSaver\JWTAuth\Exceptions\TokenBlacklistedException;
use PHPOpenSourceSaver\JWTAuth\Exceptions\TokenExpiredException;
use PHPOpenSourceSaver\JWTAuth\Exceptions\TokenInvalidException;
use PHPOpenSourceSaver\JWTAuth\Http\Middleware\Authenticate;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->alias([
            'auth.jwt' => Authenticate::class,
            'log.activity' => LogActivity::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {

        // 404 - Route tidak ditemukan
        $exceptions->render(function (NotFoundHttpException $e, Request $request) {
            if ($request->is('api/*')) {
                return response()->json([
                    'success' => false,
                    'message' => 'Route tidak ditemukan',
                ], 404);
            }
        });

        // Token tidak valid / rusak
        $exceptions->render(function (TokenInvalidException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Token tidak valid',
            ], 401);
        });

        // Token sudah expired
        $exceptions->render(function (TokenExpiredException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Token sudah kadaluarsa, silakan login kembali',
            ], 401);
        });

        // Token di-blacklist (sudah logout)
        $exceptions->render(function (TokenBlacklistedException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Token sudah tidak aktif, silakan login kembali',
            ], 401);
        });

        // Token tidak ditemukan / tidak dikirim
        $exceptions->render(function (JWTException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Token tidak ditemukan, silakan login terlebih dahulu',
            ], 401);
        });

        // Catch-all untuk UnauthorizedHttpException (wrapper dari JWT)
        $exceptions->render(function (UnauthorizedHttpException $e, Request $request) {
            if ($request->is('api/*')) {
                $previous = $e->getPrevious();

                if ($previous instanceof TokenExpiredException) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Token sudah kadaluarsa, silakan login kembali',
                    ], 401);
                }

                if ($previous instanceof TokenInvalidException) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Token tidak valid',
                    ], 401);
                }

                if ($previous instanceof TokenBlacklistedException) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Token sudah tidak aktif, silakan login kembali',
                    ], 401);
                }

                return response()->json([
                    'success' => false,
                    'message' => 'Token tidak ditemukan, silakan login terlebih dahulu',
                ], 401);
            }
        });

        // AuthenticationException
        $exceptions->render(function (AuthenticationException $e, Request $request) {
            if ($request->is('api/*')) {
                return response()->json([
                    'success' => false,
                    'message' => 'Token tidak valid atau tidak ada',
                ], 401);
            }
        });

    })->create();
