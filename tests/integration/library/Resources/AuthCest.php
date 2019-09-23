<?php

namespace Gewaer\Tests\Integration\library\Resources;

use IntegrationTester;
use Canvas\Resources\Auth;
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
     * Constructor
     *
     * @return void
     */
    public function onConstruct(): void
    {
        $this->random =  new Random();

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
        Canvas::setApiKey($this->random->base58());
        $auth = Auth::auth(['email'=> 'nobody@baka.io','password'=>'bakatest123567']);
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
        Canvas::setApiKey($this->random->base58());
        $auth = Auth::auth(['email'=> 'nobody@baka.io','password'=>'bakatest123567']);
        $I->assertTrue($auth['id'] == self::DEFAULT_CANVAS_USER_ID);
    }
}
