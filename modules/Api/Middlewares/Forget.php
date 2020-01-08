<?php

namespace Modules\Api\Middlewares;

use Base\models\AuthModel;
use Quantum\Factory\ModelFactory;
use Quantum\Libraries\Validation\Validation;
use Quantum\Exceptions\ExceptionMessages;
use Quantum\Middleware\Qt_Middleware;
use Quantum\Http\Response;
use Quantum\Loader\Loader;
use Quantum\Http\Request;

class Forget extends Qt_Middleware
{

    private $ruels = [
        'email' => 'required|valid_email'
    ];

    public function apply(Request $request, Response $response, \Closure $next)
    {
        if ($request->getMethod() == 'POST') {
            $validated = Validation::is_valid($request->all(), $this->ruels);

            if ($validated !== true) {
                $response->json([
                    'status' => 'error',
                    'message' => $validated
                ]);
            }

            if (!$this->emailExists($request->get('email'))) {
                $response->json([
                    'status' => 'error',
                    'message' => [_message(ExceptionMessages::NON_EXISTING_RECORD, $request->get('email'))]
                ]);
            }
        }

        return $next($request, $response);
    }

    private function emailExists($email)
    {
        $authModel = (new ModelFactory())->get(AuthModel::class);
        $user = $authModel->criterias(['username', '=', $email])->count();

        if($user) {
            return true;
        }

        return false;
    }

}
