<?php

declare(strict_types=1);

namespace Kanvas\Sdk\Resources;

use Kanvas\Sdk\Contracts\CrudOperationsTrait;
use Kanvas\Sdk\Resources;

/**
 * @todo Implement relationship functions using the new http client of the SDK.
 */
class Companies extends Resources
{
    const RESOURCE_NAME = 'companies';
    const CANVAS_PATH = 'Canvas\Models\Companies';

    use CrudOperationsTrait;

    /**
     * Get the current User's sources.
     *
     * @return KanvasObject
     */
    public function getUser() : object
    {
        return Users::getSelf();
    }

    /**
     * Get the current User's sources.
     *
     * @return array
     */
    public function getBranches() : array
    {
        return CompaniesBranches::find(['conditions' => ["companies_id:{$this->getUser()->default_company}"]]);
    }

    /**
     * Get the current User's sources.
     *
     * @return array
     */
    public function getDefaultBranch() : object
    {
        return CompaniesBranches::findFirst(null, ['conditions' => ["companies_id:{$this->getUser()->default_company}"]]);
    }

    /**
     * Get the current User's sources.
     *
     * @return array
     */
    public function getFields() : array
    {
        return CompaniesCustomFields::find(['conditions' => ["companies_id:{$this->getUser()->default_company}"]]);
    }

    /**
     * Get the current Users Session.
     *
     * @return KanvasObject
     */
    public function getCustomFields() : array
    {
        return CustomFields::find(['conditions' => ["companies_id:{$this->getUser()->default_company}"]]);
    }

    /**
     * Get the company Id.
     *
     * @return void
     */
    public function getUsersAssociatedCompanies() : object
    {
        return UsersAssociatedCompanies::findFirst(null, ['conditions' => ["companies_id:{$this->getUser()->default_company}"]]);
    }

    /**
     * Get the current company of the the current user.
     *
     * @return KanvasObject
     */
    public static function getUsersAssociatedApps() : array
    {
        $user = Users::getSelf();
        return UsersAssociatedApps::findFirst(null, ['conditions' => ["companies_id:{$user['default_company']}"]]);
    }

    /**
     * Get all the user's subscriptions.
     *
     * @return array
     */
    public static function getUsersAssociatedByApps() : array
    {
        $user = Users::getSelf();
        $appsId = Apps::getIdByKey(self::getClient()->getApiKey());
        return UsersAssociatedApps::findFirst(null, ['conditions' => ["companies_id:{$user['default_company']}", "apps_id:{$appsId}"]]);
    }

    /**
     * Get all the user's companies.
     *
     * @return array
     */
    public static function getBranch() : array
    {
        $user = Users::getSelf();
        return CompaniesBranches::findFirst(null, ['conditions' => ["id:{$user['default_company_branch']}"]]);
    }

    /**
     * Get all the user's apps.
     *
     * @return array
     */
    public static function getApp()
    {
        $user = Users::getSelf();
        $appsId = Apps::getIdByKey(self::getClient()->getApiKey());
        return UserCompanyApps::findFirst(null, ['conditions' => ["companies_id:{$user['default_company']}", "apps_id:{$appsId}"]]);
    }

    /**
     * Get User Files.
     *
     * @return KanvasObject
     */
    public static function getCompaniesAssoc() : array
    {
        $user = Users::getSelf();
        return CompaniesAssociations::find(['conditions' => ["companies_id:{$user['default_company']}"]]);
    }

    /**
     * Get user role.
     *
     * @return KanvasObject
     */
    public static function getSubscription() : array
    {
        $user = Users::getSelf();
        $appsId = Apps::getIdByKey(self::getClient()->getApiKey());
        return Subscription::findFirst(null, ['conditions' => ["companies_id:{$user['default_company']}", "apps_id:{$appsId}"]]);
    }

    /**
     * Get user role.
     *
     * @return array
     */
    public static function getSubscriptions() : array
    {
        $user = Users::getSelf();
        $appsId = Apps::getIdByKey(self::getClient()->getApiKey());
        return Subscription::find([
            'conditions' => [
                "companies_id:{$user['default_company']}",
                "apps_id:{$appsId}",
                'is_deleted:0'],
            'order' => 'id|desc'
        ]);
    }

    /**
     * Returns table name mapped in the model.
     *
     * @return string
     */
    public function getSource() : string
    {
        return 'companies';
    }

    /**
     * Set hashtable settings table, userConfig ;).
     *
     * @return void
     */
    public static function getUserWebhooks() : array
    {
        $user = Users::getSelf();
        return UserWebhooks::find(['conditions' => ["companies_id:{$user['default_company']}"]]);
    }
}
