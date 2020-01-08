<?php

namespace Modules\Web\Middlewares;

use Base\models\AuthModel;
use Quantum\Factory\ModelFactory;
use Quantum\Libraries\Validation\Validation;
use Quantum\Exceptions\ExceptionMessages;
use Quantum\Middleware\Qt_Middleware;
use Quantum\Http\Response;
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
                session()->setFlash('error', $validated);
                redirect(base_url() . '/' . current_lang() . '/forget');
            }

            if (!$this->emailExists($request->get('email'))) {
                session()->setFlash('error', [_message(ExceptionMessages::NON_EXISTING_RECORD, $request->get('email'))]);
                redirect(base_url() . '/' . current_lang() . '/forget');
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
