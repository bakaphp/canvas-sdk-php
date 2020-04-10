<?php

declare(strict_types=1);

namespace Kanvas\Sdk\Traits;

use Kanvas\Sdk\CustomFields;
use Kanvas\Sdk\CustomFieldsModules;
use Kanvas\Sdk\CustomFieldsValues;
use Kanvas\Sdk\CustomFieldsTypes;
use Kanvas\Sdk\Apps;
use Kanvas\Sdk\Kanvas;
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
    public $customFields = [];

    /**
     * Record Id.
     */
    public $record_id = null;

    /**
     * Verify if Custom Fields Module exists.
     * @return mixed
     */
    public function customFieldsModuleExists()
    {
        $appsId = Apps::getIdByKey(Kanvas::getApiKey());

        $customFieldsModule = current(CustomFieldsModules::find(['conditions' => [
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
     * Update a new custom field.
     * @param int $id
     * @param array $fieldsValues
     * @return Kanvas\Sdk\KanvasObject
     */
    public function updateCustomField(int $id, array $fieldsValues)
    {
        return CustomFields::update($id, $fieldsValues);
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
        $appsId = Apps::getIdByKey(Kanvas::getApiKey());
        $companiesId = Users::getSelf()->default_company;

        return current(CustomFields::find(['conditions' => [
            "label:{$name}",
            "companies_id:{$companiesId}",
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
        $appsId = Apps::getIdByKey(Kanvas::getApiKey());
        $usersId = Users::getSelf()->id;
        $companiesId = Users::getSelf()->default_company;

        return CustomFields::find(['conditions' => [
            "companies_id:{$companiesId}",
            "apps_id:{$appsId}",
            'is_deleted:0'
        ]]);
    }

    /**
     * Get all Custom Fields.
     * @param string $name
     * @param int $fieldTypeId
     * @param int $customFieldsModuleId
     * @return Kanvas\Sdk\KanvasObject
     */
    public function getAllCustomFieldsBySystemModule()
    {
        $appsId = Apps::getIdByKey(Kanvas::getApiKey());
        $usersId = Users::getSelf()->id;
        $companiesId = Users::getSelf()->default_company;

        return CustomFields::find(['conditions' => [
            "companies_id:{$companiesId}",
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
    public function createCustomFieldValue(string $label, string $value)
    {
        if ($customFieldModule = $this->getCustomFieldModuleBySelf()) {
            if ($customField = $this->getCustomField($label, (int) $customFieldModule->getId())) {
                return CustomFieldsValues::create([
                    'custom_fields_id' => $customField->getId(),
                    'label' => $label,
                    'value' => $value,
                    'is_default' => 1,
                    'is_deleted' => 0,
                ]);
            }
        }

        return false;
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
        return current(CustomFieldsTypes::find(['conditions' => [
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
        return CustomFieldsTypes::find(['conditions' => ['is_deleted:0']]);
    }

    /**
     * Get the custom field module by this model.
     * @return mixed
     */
    public function getCustomFieldModuleBySelf()
    {
        $appsId = Apps::getIdByKey(Kanvas::getApiKey());

        $customFieldsModule = current(CustomFieldsModules::find([
            'conditions' => [
                "apps_id:{$appsId}",
                'model_name:' . get_class(new self()),
                'is_deleted:0'
            ]
        ]));

        return $customFieldsModule instanceof KanvasObject ? $customFieldsModule : false;
    }

    /**
     * Process Custom Fields.
     * @return void
     */
    public function processCustomFields()
    {
        foreach ($this->customFields as $key => $value) {
            $this->createCustomFieldValue($key, $value);
        }
    }

    /**
     * Set the custom field to update a custom field module.
     *
     * @param array $fields
     * @return void
     */
    public function setCustomFields(array $fields): void
    {
        $this->customFields = $fields;
    }

    /**
     * After the module was created we need to add it custom fields.
     *
     * @return  void
     */
    public function afterCreate()
    {
        $this->processCustomFields();
        unset($this->customFields);
    }
}
