<?php

use PHPUnit\Framework\TestCase;
use Quantum\Factory\ServiceFactory;
use Base\Services\AuthServiceDB;

class AuthServiceTest extends TestCase
{

    private $initialUser = [
        'username' => 'admin@qt.com',
        'password' => '$2y$12$0M78WcmUZYQq85vHZLoNW.CyDUezRxh9Ye8/Z8oWCwJmBrz8p.j7C',
        'role' => 'admin',
        'firstname' => 'Admin',
        'lastname' => 'User',
        'remember_token' => '',
        'reset_token' => '',
        'activation_token' => '',
    ];
    public $authService;

    public function setUp(): void
    {
        $this->authService = (new ServiceFactory)->get(AuthServiceDB::class);

        $reflectionProperty = new \ReflectionProperty($this->authService, 'users');
        $reflectionProperty->setAccessible(true);
        $reflectionProperty->setValue($this->authService, []);
    }

    public function tearDown(): void
    {
        $reflectionProperty = new \ReflectionProperty(ServiceFactory::class, 'initialized');
        $reflectionProperty->setAccessible(true);
        $reflectionProperty->setValue(ServiceFactory::class, []);
    }

    public function testGet()
    {
        $this->authService->add($this->initialUser);

        $user = $this->authService->get('username', 'admin@qt.com');
        $this->assertIsArray($user);
        $this->assertArrayHasKey('password', $user);
        $this->assertArrayHasKey('firstname', $user);
        $this->assertArrayHasKey('lastname', $user);
        $this->assertArrayHasKey('role', $user);
        $this->assertArrayHasKey('remember_token', $user);
        $this->assertArrayHasKey('reset_token', $user);
        $this->assertArrayHasKey('activation_token', $user);
        $this->assertEquals('admin', $user['role']);
    }

    public function testAdd()
    {
        $user = $this->authService->add([
            'username' => 'guest@qt.com',
            'password' => '$2y$12$0M78WcmUZYQq85vHZLoNW.CyDUezRxh9Ye8/Z8oWCwJmBrz8p.j7C',
            'firstname' => 'Guest',
            'lastname' => 'User',
            'activation_token' => 'sdfsdfsdgds',
        ]);

        $this->assertIsArray($user);
        $this->assertArrayHasKey('username', $user);
        $this->assertEquals('guest@qt.com', $user['username']);
    }

    public function testUpdate()
    {
        $user = $this->authService->get('username', 'admin@qt.com');

        $this->assertEmpty($user['remember_token']);

        $rememberToken = base64_encode(time());

        $this->authService->update('username', $user['username'], [
            'remember_token' => $rememberToken
        ]);

        $user = $this->authService->get('username', 'admin@qt.com');

        $this->assertNotEmpty($user['remember_token']);
        $this->assertEquals($user['remember_token'], $rememberToken);
    }

    public function testGetVisibleFields()
    {
        $this->assertIsArray($this->authService->getVisibleFields());
        $this->assertContains('role', $this->authService->getVisibleFields());
        $this->assertNotContains('password', $this->authService->getVisibleFields());
    }

    public function testGetDefinedKeys()
    {
        $this->assertIsArray($this->authService->getDefinedKeys());
        $this->assertArrayHasKey('passwordKey', $this->authService->getDefinedKeys());
        $this->assertArrayHasKey('rememberTokenKey', $this->authService->getDefinedKeys());
        $this->assertArrayHasKey('resetTokenKey', $this->authService->getDefinedKeys());
    }

}
