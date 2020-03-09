<?php

declare(strict_types=1);

namespace Kanvas\Sdk;

use Kanvas\Sdk\Api\Operations\All;
use Kanvas\Sdk\Api\Operations\Create;
use Kanvas\Sdk\Api\Operations\Delete;
use Kanvas\Sdk\Api\Operations\Update;
use Kanvas\Sdk\Api\Operations\Retrieve;
use Kanvas\Sdk\Api\Resource;
use Kanvas\Sdk\CompaniesSettings;
use Kanvas\Sdk\CompaniesBranches;
use Kanvas\Sdk\CompaniesCustomFields;
use Kanvas\Sdk\CustomFields;
use Kanvas\Sdk\UsersAssociatedCompanies;
use Kanvas\Sdk\UsersAssociatedApps;
use Kanvas\Sdk\UserCompanyApps;
use Kanvas\Sdk\CompaniesAssociations;
use Kanvas\Sdk\Subscription;
use Kanvas\Sdk\UserWebhooks;
use Kanvas\Sdk\FileSystemEntities;
use Kanvas\Sdk\SystemModules;
use Kanvas\Sdk\Users;

class Companies extends Resource
{
    const OBJECT_NAME = 'companies';
    const CANVAS_PATH = 'Canvas\Models\Companies';

    use All;
    use Create;
    use Delete;
    use Update;
    use Retrieve;

    /**
     * Get the company's settings.
     *
     * @return array
     */
    public function getSettings(): array
    {
        $user = Users::getSelf();
        return CompaniesSettings::all([], ['conditions' => ["companies_id:{$user->default_company}"]]);
    }

    /**
     * Get the company's user.
     *
     * @return KanvasObject
     */
    public function getUser(): KanvasObject
    {
        $user = Users::getSelf();
        return current(Users::all([], ['conditions' => ["id:{$user->id}", "default_company:{$user->default_company}"]]));
    }

    /**
     * Get the company's branches.
     *
     * @return array
     */
    public function getBranches(): array
    {
        $user = Users::getSelf();
        return CompaniesBranches::all([], ['conditions' => ["users_id:{$user->id}", "companies_id:{$user->default_company}"]]);
    }

    /**
     * Get the company's default branch.
     *
     * @return KanvasObject
     */
    public function getDefaultBranch(): KanvasObject
    {
        $user = Users::getSelf();
        return current(CompaniesBranches::all([], ['conditions' => ["users_id:{$user->id}", "companies_id:{$user->default_company}"]]));
    }

    /**
     * Get the company's custom fields.
     *
     * @return array
     */
    public function getFields(): array
    {
        $user = Users::getSelf();
        return CompaniesCustomFields::all([], ['conditions' => ["companies_id:{$user->default_company}"]]);
    }

    /**
     * Get the own company custom fields.
     *
     * @return array
     */
    public function getCustomFields(): array
    {
        $user = Users::getSelf();
        return CustomFields::all([], ['conditions' => ["companies_id:{$user->default_company}"]]);
    }

    /**
     * Get the company's users associated companies.
     *
     * @return array
     */
    public function getUsersAssociatedCompanies(): array
    {
        $user = Users::getSelf();
        return UsersAssociatedCompanies::all([], ['conditions' => ["companies_id:{$user->default_company}"]]);
    }

    /**
     * Get the company's users associated apps.
     *
     * @return array
     */
    public function getUsersAssociatedApps(): array
    {
        $user = Users::getSelf();
        return UsersAssociatedApps::all([], ['conditions' => ["companies_id:{$user->default_company}"]]);
    }

    /**
     * Get the company's users associated apps.
     *
     * @return array
     */
    public function getUsersAssociatedByApps(): array
    {
        $user = Users::getSelf();
        $appsId = Apps::getIdByKey(getenv('GEWAER_APP_ID'));
        return UsersAssociatedApps::all([], ['conditions' => ["users_id:{$user->id}","apps_id:{$appsId}"]]);
    }

    /**
     * Get the company's branch.
     *
     * @return KanvasObject
     */
    public function getBranch(): KanvasObject
    {
        $user = Users::getSelf();
        return current(CompaniesBranches::all([], ['conditions' => ["companies_id:{$user->default_company}"]]));
    }

    /**
     * Get the company's app.
     *
     * @return KanvasObject
     */
    public function getApp(): KanvasObject
    {
        $user = Users::getSelf();
        $appsId = Apps::getIdByKey(getenv('GEWAER_APP_ID'));
        return current(UserCompanyApps::all([], ['conditions' => ["companies_id:{$user->default_company}","apps_id:{$appsId}"]]));
    }

    /**
     * Get the company's apps.
     *
     * @return KanvasObject
     */
    public function getApps(): KanvasObject
    {
        $user = Users::getSelf();
        $appsId = Apps::getIdByKey(getenv('GEWAER_APP_ID'));
        return current(UserCompanyApps::all([], ['conditions' => ["companies_id:{$user->default_company}","apps_id:{$appsId}"]]));
    }

    /**
     * Get the company's associations.
     *
     * @return array
     */
    public function getCompaniesAssoc(): array
    {
        $user = Users::getSelf();
        return CompaniesAssociations::all([], ['conditions' => ["companies_id:{$user->default_company}"]]);
    }



    ////////////////////////////////////////////////////////////////////////

    /**
     * Get the company's users.
     *
     * @return array
     */
    public function getUsers(): array
    {
        $usersArray = [];
        $user = Users::getSelf();
        $appsId = Apps::getIdByKey(getenv('GEWAER_APP_ID'));
        $userAssocApps = UsersAssociatedApps::all([], ['conditions' => ["companies_id:{$user->default_company}","apps_id:{$appsId}","is_deleted:0"]]);
        
        foreach ($userAssocApps as $userAssocApp) {
            $userAssoc = current(Users::all([], ['conditions' => ["id:{$userAssocApp->users_id}"]]));
            $usersArray[] = $userAssoc;
        }
        
        return $usersArray;
    }

    /**
     * Get the company's active subscription.
     *
     * @return KanvasObject
     */
    public function getSubscription(): KanvasObject
    {
        $user = Users::getSelf();
        $appsId = Apps::getIdByKey(getenv('GEWAER_APP_ID'));
        return current(Subscription::all([], [
            'conditions' => [
                "companies_id:{$user->default_company}",
                "apps_id:{$appsId}",
                "is_deleted:0"],
            'sort' => 'id|desc'
        ]));
    }

    /**
     * Get the company's subscriptions.
     *
     * @return array
     */
    public function getSubscriptions(): array
    {
        $user = Users::getSelf();
        $appsId = Apps::getIdByKey(getenv('GEWAER_APP_ID'));
        return Subscription::all([], [
            'conditions' => [
                "companies_id:{$user->default_company}",
                "apps_id:{$appsId}",
                "is_deleted:0"],
            'sort' => 'id|desc'
        ]);
    }

    /**
     * Get the company's user webhooks.
     *
     * @return array
     */
    public function getUserWebhooks(): array
    {
        $user = Users::getSelf();
        return UserWebhooks::all([], ['conditions' => ["companies_id:{$user->default_company}"]]);
    }

    /**
     * Get the company's files.
     *
     * @return KanvasObject
     */
    public function getFiles(): KanvasObject
    {
        $user = Users::getSelf();
        $appsId = Apps::getIdByKey(getenv('GEWAER_APP_ID'));
        $systemModule = SystemModules::getSystemModuleByModelName(self::CANVAS_PATH, (int)$appsId);
        return current(FileSystemEntities::all([], ['conditions' => ["entity_id:{$user->default_company}", "system_modules_id:{$systemModule->id}"]]));
    }

    /**
     * Get the company's logo.
     *
     * @return KanvasObject
     */
    public function getLogo(): KanvasObject
    {
        $user = Users::getSelf();
        $appsId = Apps::getIdByKey(getenv('GEWAER_APP_ID'));
        $systemModule = SystemModules::getSystemModuleByModelName(self::CANVAS_PATH, (int)$appsId);
        return current(FileSystemEntities::all([], ['conditions' => ["entity_id:{$user->default_company}", "system_modules_id:{$systemModule->id}"]]));
    }
}
