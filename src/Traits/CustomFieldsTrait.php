<?php

declare(strict_types=1);

namespace Kanvas\Sdk\Traits;

use Kanvas\Sdk\CustomFields;
use Kanvas\Sdk\CustomFieldsModules;
use Kanvas\Sdk\CustomFieldsValues;
use Kanvas\Sdk\CustomFieldsTypes;
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
     * Custom Fields.
     */
    public $custom_fields = [];

    /**
     * Record Id.
     */
    public $record_id = NULL;

    /**
     * Verify if Custom Fields Module exists.
     * @return mixed
     */
    public function customFieldsModuleExists()
    {
        $appsId = Apps::getIdByKey(getenv('GEWAER_APP_ID'));

        $customFieldsModule = current(CustomFieldsModules::all([], ['conditions' => [
            "apps_id:{$appsId}",
            'name:' . get_class(new self()),
            'model_name:' . get_class(new self()),
            'is_deleted:0'
        ]]));

        return $customFieldsModule instanceof KanvasObject ? $customFieldsModule : false;
    }

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
            'label' => $name,
            'fields_type_id' => $fieldTypeId,
            'custom_fields_modules_id' => $customFieldsModuleId
        ]);
    }

    /**
     * Get a Custom Field by name and custom_fields_module_id.
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
     * Get all Custom Fields.
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

    /**
     * Create a new custom field value.
     * @param int $customFieldId
     * @param string $label
     * @param mixed $value
     * @return Kanvas\Sdk\KanvasObject
     */
    public function createCustomFieldValue(int $customFieldId, string $label, $value)
    {
        return CustomFieldsValues::create([
            'custom_fields_id' => $customFieldId,
            'label' => $label,
            'value' => $value,
            'is_default' => 1,
            'is_deleted' => 0,
        ]);
    }

    /**
     * Get Custom Field Type.
     * @param string $name
     * @param int $fieldTypeId
     * @param int $customFieldsModuleId
     * @return Kanvas\Sdk\KanvasObject
     */
    public function getCustomFieldTypeByName(string $name)
    {
        return current(CustomFieldsTypes::all([], ['conditions' => [
            "name:{$name}",
            'is_deleted:0'
        ]]));
    }

    /**
     * Get all Custom Fields Types.
     * @param string $name
     * @param int $fieldTypeId
     * @param int $customFieldsModuleId
     * @return Kanvas\Sdk\KanvasObject
     */
    public function getAllCustomFieldsTypes()
    {
        return CustomFieldsTypes::all([], ['conditions' => ['is_deleted:0']]);
    }

    /**
     * Process Custom Fields.
     * @return void
     */
    public function processCustomFields()
    {
        $customFieldModule = $this->customFieldsModuleExists();
        if (!$customFieldModule) {
            $customFieldModule = $this->createCustomFieldsModule(get_class(new self()));
        }

        foreach ($this->custom_fields as $key => $value) {
            if (!$customField = $this->getCustomField($key, (int)$customFieldModule->id)) {
                $customField = $this->createCustomField($key, (int)$this->getCustomFieldTypeByName(gettype($value))->id, (int)$customFieldModule->id);
            }

            $this->createCustomFieldValue($customField->id, $customField->label, $value);
        }
    }
}
