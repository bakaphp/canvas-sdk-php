<?php

namespace Gewaer\Tests\Integration\library\Models;

use IntegrationTester;
use Kanvas\Sdk\Users;
use Kanvas\Sdk\Models\Users as UserModel;
use Kanvas\Sdk\Auth;
use Kanvas\Sdk\Kanvas;
use Phalcon\Security\Random;

/**
 *  Users Model Test Class
 */
class UsersCest
{
    /**
     * Default Users id.
     */
    const DEFAULT_USERS_ID = 1;

    /**
     * Random variable
     *
     * @var string
     */
    public $random;

    /**
     * Users's id
     *
     * @var integer
     */
    public $userId;

    /**
     * User's email
     *
     * @var string
     */
    public $userEmail;

    /**
     * User's password
     *
     * @var string
     */
    public $userPassword;

    /**
     * Constructor
     *
     * @return void
     */
    public function onConstruct(): void
    {
        $this->random =  new Random();
        Kanvas::setApiKey(getenv('KANVAS_SDK_API_KEY'));
        Auth::auth(['email'=> getenv('TEST_USER_EMAIL'),'password'=>getenv('TEST_USER_PASS')]);
        $this->userEmail = 'example-'. $this->random->base58() .'@gmail.com';
        $this->userPassword = $this->random->base58();
    }

    /**
     * Create a new user
     *
     * @param IntegrationTester $I
     * @return void
     */
    public function create(IntegrationTester $I): void
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
     * Find test for users
     *
     * @param IntegrationTester $I
     * @return void
     */
    public function find(IntegrationTester $I): void
    {
        $users = UserModel::find();
        $I->assertTrue(is_array($users));
    }

    /**
     * Find first test for users
     *
     * @param IntegrationTester $I
     * @return void
     */
    public function findFirst(IntegrationTester $I): void
    {
        $users = UserModel::findFirst(self::DEFAULT_USERS_ID);
        $I->assertTrue($users instanceof Users);
    }
}
