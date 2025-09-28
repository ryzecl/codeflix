<?php

namespace App\Http\Middleware;

use Closure;
use LDAP\Result;
use Illuminate\Http\Request;
use App\Services\DeviceLimitService;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Session;
use Symfony\Component\HttpFoundation\Response;

class LogoutDevice
{

    protected $deviceService;

    public function __construct(DeviceLimitService $deviceService)
    {
        $this->deviceService = $deviceService;
    }
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if ($this->isLogoutRequest($request)) {
            $deviceId = Session::get('device_id');

            if ($deviceId) {
                $this->deviceService->logoutDevice($deviceId);
            }
        }

        return $next($request);
    }

    private function isLogoutRequest(Request $request): bool
    {
        return $request->is('logout') || Route::currentRouteName() === 'logout';
    }
}
