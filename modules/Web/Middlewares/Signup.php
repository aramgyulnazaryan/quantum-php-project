<?php

namespace Modules\Web\Middlewares;

use Base\models\AuthModel;
use Quantum\Factory\ModelFactory;
use Quantum\Libraries\Validation\Validation;
use Quantum\Exceptions\ExceptionMessages;
use Quantum\Middleware\Qt_Middleware;
use Quantum\Http\Response;
use Quantum\Http\Request;

class Signup extends Qt_Middleware
{

    private $ruels = [
        'username' => 'required|valid_email',
        'password' => 'required|min_len,6',
        'firstname' => 'required',
        'lastname' => 'required',
    ];

    public function apply(Request $request, Response $response, \Closure $next)
    {
        if ($request->getMethod() == 'POST') {
            $validated = Validation::is_valid($request->all(), $this->ruels);

            if ($validated !== true) {
                session()->setFlash('error', $validated);
                redirect(base_url() . '/' . current_lang() . '/signup');
            }

            if (!$this->isUnique($request->all())) {
                session()->setFlash('error', [_message(ExceptionMessages::NON_UNIQUE_VALUE, 'username')]);
                redirect(base_url() . '/' . current_lang() . '/signup');
            }
        }

        return $next($request, $response);
    }

    private function isUnique($userData)
    {
        $authModel = (new ModelFactory())->get(AuthModel::class);
        $user = $authModel->criterias(['username', '=', $userData['username']])->count();

        if($user) {
            return false;
        }

        return true;
    }

}
