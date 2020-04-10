<?php

namespace Gewaer\Tests\Integration\library\Resources;

use IntegrationTester;
use Kanvas\Sdk\Auth;
use Kanvas\Sdk\Companies;
use Kanvas\Sdk\Models\Companies as CompaniesModel;
use Kanvas\Sdk\Kanvas;
use Phalcon\Security\Random;

class CompaniesCest
{
    /**
     * Default Company id.
     */
    const DEFAULT_COMPANIES_ID = 1;

    /**
     * Random variable.
     *
     * @var string
     */
    public $random;

    /**
     * Constructor.
     *
     * @return void
     */
    public function onConstruct(): void
    {
        $this->random = new Random();
        Kanvas::setApiKey(getenv('KANVAS_SDK_API_KEY'));
        Auth::auth(['email' => getenv('TEST_USER_EMAIL'), 'password' => getenv('TEST_USER_PASS')]);
    }

    /**
     * Get all companies.
     *
     * @param IntegrationTester $I
     * @return void
     */
    public function getAllCompanies(IntegrationTester $I): void
    {
        $I->assertTrue(gettype(Companies::all()) == 'array');
    }

    /**
     * Update a Company.
     *
     * @param IntegrationTester $I
     * @return void
     */
    public function update(IntegrationTester $I): void
    {
        $company = Companies::update(self::DEFAULT_COMPANIES_ID, ['phone' => 4232523]);
        $I->assertTrue($company instanceof Companies);
    }

    /**
     * Get a Company.
     *
     * @param IntegrationTester $I
     * @return void
     */
    public function getCompany(IntegrationTester $I): void
    {
        $company = Companies::retrieve(self::DEFAULT_COMPANIES_ID);
        $I->assertTrue($company instanceof Companies);
    }

    /**
     * Get a Company.
     *
     * @param IntegrationTester $I
     * @return void
     */
    public function getCompanyById(IntegrationTester $I): void
    {
        $company = Companies::getById(self::DEFAULT_COMPANIES_ID);
        $I->assertTrue($company instanceof Companies);
    }
}
