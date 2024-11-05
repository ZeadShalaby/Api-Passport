<?php

namespace App\Http\Middleware;

use Closure;
use Exception;
use Illuminate\Http\Request;
use App\Traits\ResponseTrait;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;
use League\OAuth2\Server\Exception\OAuthServerException;

class CheckToken
{
    use ResponseTrait;

    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next, $guard = null): Response
    {
        try {
            if ($guard != null) {
                auth()->shouldUse($guard);
            }
            // ?todo Ensure that the user is authenticated
            if (!Auth::guard('api')->check()) {
                return $this->returnError(401, 'Token not provided or invalid');
            }
            return $next($request);
        } catch (OAuthServerException $e) {
            if ($e->getCode() === 401) {
                return $this->returnError(401, 'Token has expired. Please log in again.');
            }
            return $this->returnError(401, 'Invalid token');
        } catch (Exception $e) {
            return $this->returnError(500, $e->getCode() . " , " . $e->getMessage());
        }
    }
}
