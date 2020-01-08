<?php

namespace Modules\Api\Middlewares;

use Base\models\AuthModel;
use Quantum\Factory\ModelFactory;
use Quantum\Libraries\Validation\Validation;
use Quantum\Exceptions\ExceptionMessages;
use Quantum\Middleware\Qt_Middleware;
use Quantum\Hooks\HookManager;
use Quantum\Loader\Loader;
use Quantum\Http\Response;
use Quantum\Http\Request;

class Reset extends Qt_Middleware
{

    private $ruels = [
        'password' => 'required|min_len,6'
    ];

    public function apply(Request $request, Response $response, \Closure $next)
    {
        list($token) = current_route_args();

        if (!$this->checkToken($token)) {
            $response->json([
                'status' => 'error',
                'message' => [_message(ExceptionMessages::NON_EXISTING_RECORD, 'token')]
            ]);
        }

        $validated = Validation::is_valid($request->all(), $this->ruels);
        if ($validated !== true) {
            $response->json([
                'status' => 'error',
                'message' => $validated
            ]);
        }

        $request->set('reset_token', $token);

        return $next($request, $response);
    }

    private function checkToken($token)
    {
        $authModel = (new ModelFactory())->get(AuthModel::class);
        $user = $authModel->criterias(['reset_token', '=', $token])->count();

        if($user) {
            return true;
        }

        return false;
    }

}
