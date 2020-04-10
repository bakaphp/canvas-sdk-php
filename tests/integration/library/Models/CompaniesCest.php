<?php

namespace Gewaer\Tests\Integration\library\Models;

use IntegrationTester;
use Kanvas\Sdk\Auth;
use Kanvas\Sdk\Companies;
use Kanvas\Sdk\Models\Companies as CompaniesModel;
use Kanvas\Sdk\Kanvas;
use Phalcon\Security\Random;

/**
 * Companies Model Test Class
 */
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
     * Find test for companies.
     *
     * @param IntegrationTester $I
     * @return void
     */
    public function find(IntegrationTester $I): void
    {
        $company = CompaniesModel::find();
        $I->assertTrue(is_array($company));
    }

    /**
     * Find test for companies.
     *
     * @param IntegrationTester $I
     * @return void
     */
    public function findFirst(IntegrationTester $I): void
    {
        $company = CompaniesModel::findFirst(self::DEFAULT_COMPANIES_ID);
        $I->assertTrue($company instanceof Companies);
    }
}
