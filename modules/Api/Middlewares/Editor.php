<?php

namespace Modules\Api\Middlewares;

use Quantum\Middleware\Qt_Middleware;
use Quantum\Http\Response;
use Quantum\Http\Request;

class Editor extends Qt_Middleware
{

    public function apply(Request $request, Response $response, \Closure $next)
    {
        if (auth()->user()->role != 'admin' && auth()->user()->role != 'editor') {
            $response->json([
                'status' => 'error',
                'message' => 'Access denied'
            ]);
        }

        return $next($request, $response);
    }

}
