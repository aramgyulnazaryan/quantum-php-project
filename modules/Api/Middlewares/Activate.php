<?php

namespace Modules\Api\Middlewares;

use Base\models\AuthModel;
use Quantum\Exceptions\ExceptionMessages;
use Quantum\Factory\ModelFactory;
use Quantum\Middleware\Qt_Middleware;
use Quantum\Http\Response;
use Quantum\Http\Request;

class Activate extends Qt_Middleware
{

    public function apply(Request $request, Response $response, \Closure $next)
    {
        list($token) = current_route_args();

        if (!$this->checkToken($token)) {
            $response->json([
                'status' => 'error',
                'message' => [_message(ExceptionMessages::NON_EXISTING_RECORD, 'token')]
            ]);
        }

        $request->set('activation_token', $token);

        return $next($request, $response);
    }

    private function checkToken($token)
    {
        $authModel = (new ModelFactory())->get(AuthModel::class);
        $user = $authModel->criterias(['activation_token', '=', $token])->count();

        if($user) {
            return true;
        }

        return false;
    }

}
