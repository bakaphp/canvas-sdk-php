<?php

namespace Gewaer\Tests\Integration\library\Resources;

use IntegrationTester;
use Kanvas\Sdk\Users;
use Kanvas\Sdk\Models\Users as UserModel;
use Kanvas\Sdk\Auth;
use Kanvas\Sdk\Kanvas;
use Phalcon\Security\Random;

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
        Kanvas::setApiKey($this->random->base58());
        Auth::auth(['email'=> getenv('TEST_USER_EMAIL'),'password'=>getenv('TEST_USER_PASS')]);
        $this->userEmail = 'example-'. $this->random->base58() .'@gmail.com';
        $this->userPassword = $this->random->base58();
    }

    /**
     * Get all users
     *
     * @param IntegrationTester $I
     * @return void
     */
    public function getAllUsers(IntegrationTester $I): void
    {
        $I->assertTrue(gettype(Users::all()) == 'array');
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
     * Update a user
     *
     * @param IntegrationTester $I
     * @return void
     */
    public function update(IntegrationTester $I): void
    {
        $users = Users::update($this->userId, ['firstname'=>'testSDKUpdate','lastname'=> 'testSDKUpdate']);
        $I->assertTrue($users instanceof Users);
    }

    /**
     * Get a user
     *
     * @param IntegrationTester $I
     * @return void
     */
    public function getUser(IntegrationTester $I): void
    {
        $users = Users::getById($this->userId);
        $I->assertTrue($users instanceof Users);
    }

    /**
     * Delete a user
     *
     * @param IntegrationTester $I
     * @return void
     */
    public function delete(IntegrationTester $I): void
    {
        $users = Users::delete($this->userId);
        $I->assertTrue(gettype($users[0]) == 'string');
        $I->assertTrue($users[0] == 'Delete Successfully');
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
