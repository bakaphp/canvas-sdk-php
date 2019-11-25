<?php

namespace Gewaer\Tests\Integration\library\Resources;

use IntegrationTester;
use Canvas\Auth;
use Canvas\Users;
use Canvas\Canvas;
use Phalcon\Security\Random;

class AuthCest
{
    const DEFAULT_CANVAS_USER_ID = 1;
    /**
     * Random variable
     *
     * @var string
     */
    private $random;

    /**
     * Auth variable
     *
     * @var [type]
     */
    private $auth;

    /**
     * Constructor
     *
     * @return void
     */
    public function onConstruct(): void
    {
        $this->random =  new Random();
        Canvas::setApiKey($this->random->base58());
        $this->auth = Auth::auth(['email'=> 'nobody@baka.io','password'=>'bakatest123567']);
        $this->userEmail = 'example-'. $this->random->base58() .'@gmail.com';
        $this->userPassword = $this->random->base58();

    }

    /**
     * Setup Api Key
     *
     * @param IntegrationTester $I
     * @return void
     */
    public function setupApiKey(IntegrationTester $I)
    {
        Canvas::setApiKey($this->random->base58());
        $I->assertTrue(gettype(Canvas::getApiKey()) == 'string');
    }

    /**
     * Setup Auth Token
     *
     * @param IntegrationTester $I
     * @return void
     */
    public function setupAuthToken(IntegrationTester $I)
    {
        $I->assertTrue(gettype(Canvas::getAuthToken()) == 'string');
    }

    /**
     * Autheticate a canvas user
     *
     * @param IntegrationTester $I
     * @return void
     */
    public function auth(IntegrationTester $I)
    {
        $I->assertTrue($this->auth->id == self::DEFAULT_CANVAS_USER_ID);
    }

    /**
     * Create a new user
     *
     * @param IntegrationTester $I
     * @return void
     */
    public function signup(IntegrationTester $I): void
    {
        $users = Users::create([
            'firstname'=>'testSDK',
            'lastname'=> 'testSDK',
            'displayname'=> 'sdktester',
            'password'=> $this->userPassword,
            'default_company'=> 'Company'. $this->random->base58(),
            'email'=> $this->userEmail,
            'verify_password'=> $this->userPassword
        ]);

        $I->assertTrue($users instanceof Users);
        $this->userId = $users->id;
    }
}
