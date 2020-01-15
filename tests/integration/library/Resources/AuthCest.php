<?php

namespace Gewaer\Tests\Integration\library\Resources;

use IntegrationTester;
use Kanvas\Sdk\Auth;
use Kanvas\Sdk\Users;
use Kanvas\Sdk\Kanvas;
use Phalcon\Security\Random;

class AuthCest
{
    const DEFAULT_KANVAS_USER_ID = 1;
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
        Kanvas::setApiKey($this->random->base58());
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
        Kanvas::setApiKey($this->random->base58());
        $I->assertTrue(gettype(Kanvas::getApiKey()) == 'string');
    }

    /**
     * Setup Auth Token
     *
     * @param IntegrationTester $I
     * @return void
     */
    public function setupAuthToken(IntegrationTester $I)
    {
        $I->assertTrue(gettype(Kanvas::getAuthToken()) == 'string');
    }

    /**
     * Autheticate a Kanvas user
     *
     * @param IntegrationTester $I
     * @return void
     */
    public function auth(IntegrationTester $I)
    {
        $I->assertTrue($this->auth->id == self::DEFAULT_KANVAS_USER_ID);
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

    /**
     * Get the values from the auth property
     *
     * @param IntegrationTester $I
     * @return void
     */
    public function getValues(IntegrationTester $I): void
    {
        $I->assertArrayHasKey('token', $this->auth->getValues());
    }
}
