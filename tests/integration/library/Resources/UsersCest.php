<?php

namespace Gewaer\Tests\Integration\library\Resources;

use IntegrationTester;
use Canvas\Users;
use Canvas\Auth;
use Canvas\Canvas;
use Phalcon\Security\Random;

class UsersCest
{
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
        Canvas::setApiKey($this->random->base58());
        Auth::auth(['email'=> 'max@mctekk.com','password'=>'nosenose']);
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

        $I->assertTrue(gettype($users) == 'object');
        $this->userId = $users->user->id;
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
        $I->assertTrue(gettype($users) == 'object');
    }

    /**
     * Get a user
     *
     * @param IntegrationTester $I
     * @return void
     */
    public function getUser(IntegrationTester $I): void
    {
        $users = Users::retrieve($this->userId);
        $I->assertTrue(gettype($users) == 'object');
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
    }
}
