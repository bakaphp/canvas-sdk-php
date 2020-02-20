<?php

declare(strict_types=1);

namespace Kanvas\Sdk\Traits;

use Kanvas\Sdk\CustomFields;
use Kanvas\Sdk\CustomFieldsModules;
use Kanvas\Sdk\Apps;
use Kanvas\Sdk\KanvasObject;
use Kanvas\Sdk\Users;

/**
 * Trait FractalTrait.
 *
 * @package Canvas\Traits
 */
trait CustomFieldsTrait
{
    /**
     * Create Custom Fields Modules.
     *
     * @return Kanvas\Sdk\KanvasObject
     */
    public function createCustomFieldsModule(string $name)
    {
        return CustomFieldsModules::create([
            'name' => $name,
            'model_name' => get_class(new self())
        ]);
    }

    /**
     * Create a new custom field.
     * @param string $name
     * @param int $fieldTypeId
     * @param int $customFieldsModuleId
     * @return Kanvas\Sdk\KanvasObject
     */
    public function createCustomField(string $name, int $fieldTypeId, int $customFieldsModuleId)
    {
        return CustomFields::create([
            'name' => $name,
            'fields_type_id' => $fieldTypeId,
            'custom_fields_modules_id' => $customFieldsModuleId
        ]);
    }

    /**
     * Create a new custom field.
     * @param string $name
     * @param int $fieldTypeId
     * @param int $customFieldsModuleId
     * @return Kanvas\Sdk\KanvasObject
     */
    public function getCustomField(string $name, int $customFieldsModuleId)
    {
        $appsId = Apps::getIdByKey(getenv('GEWAER_APP_ID'));
        $usersId = Users::getSelf()->id;
        $companiesId = Users::getSelf()->default_company;

        return current(CustomFields::all([], ['conditions' => [
            "name:{$name}",
            "companies_id:{$companiesId}",
            "users_id:{$usersId}",
            "apps_id:{$appsId}",
            "custom_fields_modules_id:{$customFieldsModuleId}",
            'is_deleted:0'
        ]]));
    }

    /**
     * Create a new custom field.
     * @param string $name
     * @param int $fieldTypeId
     * @param int $customFieldsModuleId
     * @return Kanvas\Sdk\KanvasObject
     */
    public function getAllCustomFields()
    {
        $appsId = Apps::getIdByKey(getenv('GEWAER_APP_ID'));
        $usersId = Users::getSelf()->id;
        $companiesId = Users::getSelf()->default_company;

        return CustomFields::all([], ['conditions' => [
            "companies_id:{$companiesId}",
            "users_id:{$usersId}",
            "apps_id:{$appsId}",
            'is_deleted:0'
        ]]);
    }

    public function customFieldsModuleExists()
    {
        $appsId = Apps::getIdByKey(getenv('GEWAER_APP_ID'));
        
        $customFieldsModule = current(CustomFieldsModules::all([], ['conditions' => [
            "apps_id:{$appsId}",
            "name:" . get_class(new self()),
            "model_name:". get_class(new self()),
            'is_deleted:0'
        ]]));
        
        return $customFieldsModule instanceof KanvasObject ? $customFieldsModule : false;
    }
}
