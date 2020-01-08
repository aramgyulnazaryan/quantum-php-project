<?php

/**
 * Quantum PHP Framework
 *
 * An open source software development framework for PHP
 *
 * @package Quantum
 * @author Arman Ag. <arman.ag@softberg.org>
 * @copyright Copyright (c) 2018 Softberg LLC (https://softberg.org)
 * @link http://quantum.softberg.org/
 * @since 1.9.0
 */

namespace Base\Services;

use Base\models\AuthModel;
use Quantum\Factory\ModelFactory;
use Quantum\Libraries\Auth\AuthServiceInterface;
use Quantum\Exceptions\ExceptionMessages;
use Quantum\Libraries\Storage\FileSystem;
use Quantum\Mvc\Qt_Service;
use Quantum\Loader\Loader;
use Modules\Web\Models\User;

/**
 *
 */
class AuthServiceDB extends Qt_Service implements AuthServiceInterface
{

    protected static $users = [];

    private $authModel;

    protected $fields = [
        'username',
        'firstname',
        'lastname',
        'role'
    ];

    protected $keyFields = [
        'usernameKey' => 'username',
        'passwordKey' => 'password',
        'rememberTokenKey' => 'remember_token',
        'resetTokenKey' => 'reset_token',
        'accessTokenKey' => 'access_token',
        'refreshTokenKey' => 'refresh_token',
    ];

    protected $visibleFields = [
        'username',
        'firstname',
        'lastname',
        'role'
    ];

    public function __init(ModelFactory $modelFactory)
    {
       $this->authModel = $modelFactory->get(AuthModel::class);
    }

    public function get($field, $value) : array
    {
        $user = $this->authModel->findOneBy($field, $value)->asArray();
        return $user;
    }

    public function add($data)
    {
        $user = $this->authModel->create();
        $user->username = $data['username'];
        $user->password = $data['password'];
        $user->firstname = $data['firstname'];
        $user->lastname = $data['lastname'];
        $user->role = $data['role'] ?? '';
        $user->save();
        return $user->asArray();
    }

    public function update($field, $value, $data)
    {
        $user = $this->authModel->findOneBy($field, $value);
        foreach ($data as $key => $value) {
            $user->$key = $value;
        }
        $user->save();
    }

    public function getVisibleFields()
    {
        return $this->visibleFields ?? [];
    }

    public function getDefinedKeys()
    {
        return $this->keyFields;
    }

}
