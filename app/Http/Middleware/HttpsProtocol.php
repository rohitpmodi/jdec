<?php

namespace App\Http\Middleware;

use Closure;

class HttpsProtocol {

    public function handle($request, Closure $next) {
        if (!$request->secure() && env('APP_ENV') === 'production') {
			$redirect_url = 'https://'.request()->getHost().$request->getRequestUri();
            return redirect($redirect_url);
        }

        return $next($request);
    }

}
