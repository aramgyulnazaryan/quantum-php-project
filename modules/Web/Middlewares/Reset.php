<?php

namespace Modules\Web\Middlewares;

use Base\models\AuthModel;
use Base\Services\AuthServiceDB;
use Quantum\Factory\ModelFactory;
use Quantum\Factory\ServiceFactory;
use Quantum\Libraries\Validation\Validation;
use Quantum\Exceptions\ExceptionMessages;
use Quantum\Middleware\Qt_Middleware;
use Quantum\Hooks\HookManager;
use Quantum\Http\Response;
use Quantum\Http\Request;

class Reset extends Qt_Middleware
{

    private $ruels = [
        'password' => 'required|min_len,6'
    ];

    public function apply(Request $request, Response $response, \Closure $next)
    {

        list($lang, $token) = current_route_args();

        if ($request->getMethod() == 'POST') {
            if (!$this->checkToken($token)) {
                session()->setFlash('error', [_message(ExceptionMessages::NON_EXISTING_RECORD, 'token')]);
                redirect(get_referrer());
            }

            $validated = Validation::is_valid($request->all(), $this->ruels);
            if ($validated !== true) {
                session()->setFlash('error', $validated);
                redirect(get_referrer());
            }
        } elseif ($request->getMethod() == 'GET') {
            if (!$this->checkToken($token)) {
                HookManager::call('pageNotFound');
            }
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
