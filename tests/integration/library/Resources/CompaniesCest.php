<?php

namespace Gewaer\Tests\Integration\library\Resources;

use IntegrationTester;
use Canvas\Resources\Auth;
use Canvas\Resources\Companies;
use Canvas\Canvas;
use Phalcon\Security\Random;

class CompaniesCest
{
    /**
     * Default Company id
     */
    const DEFAULT_COMPANIES_ID = 1;

    /**
     * Random variable
     *
     * @var string
     */
    public $random;

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
    }

    /**
     * Get all companies
     *
     * @param IntegrationTester $I
     * @return void
     */
    public function getAllCompanies(IntegrationTester $I): void
    {
        $I->assertTrue(gettype(Companies::all()) == 'array');
    }

    /**
     * Update a Company
     *
     * @param IntegrationTester $I
     * @return void
     */
    public function update(IntegrationTester $I): void
    {
        $users = Companies::update(self::DEFAULT_COMPANIES_ID, ['phone'=>4232523]);
        $I->assertTrue(gettype($users) == 'object');
    }

    /**
     * Get a Company
     *
     * @param IntegrationTester $I
     * @return void
     */
    public function getCompany(IntegrationTester $I): void
    {
        $users = Companies::retrieve(self::DEFAULT_COMPANIES_ID);
        $I->assertTrue(gettype($users) == 'object');
    }
}
