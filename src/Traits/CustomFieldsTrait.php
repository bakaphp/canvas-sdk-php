<?php

declare(strict_types=1);

namespace Kanvas\Sdk\Traits;

use Exception;
use Gewaer\Models\CustomFields as AppCustomFields;
use Kanvas\Sdk\CustomFields;
use Kanvas\Sdk\CustomFieldsModules;
use Kanvas\Sdk\CustomFieldsValues;
use Kanvas\Sdk\CustomFieldsTypes;
use Kanvas\Sdk\Apps;
use Kanvas\Sdk\Kanvas;
use Kanvas\Sdk\KanvasObject;
use Kanvas\Sdk\Users;
use Phalcon\Security\Random;

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
     * Set custom fields relationship
     *
     * @return void
     */
    public function afterFetch()
    {
        $this->hasMany(
            'id',
            AppCustomFields::class,
            'entity_id',
            [
                'params' => [
                    'conditions' => 'model_name = :model_name: AND companies_id = :companies_id:',
                    'columns' => 'name, label, value',
                    'bind' => [
                        'model_name' => get_class($this),
                        'companies_id' => $this->companies_id,
                    ]
                ],
                'alias' => 'custom_fields'
            ]
        );
    }

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
        $random = new Random();

        return CustomFields::create([
            'name' => $random->uuid(),
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
     *
     * @param string $name
     * @param int $fieldTypeId
     * @param int $customFieldsModuleId
     * @return Kanvas\Sdk\KanvasObject
     */
    public function getAllCompanyCustomFields()
    {
        $appsId = Apps::getIdByKey(Kanvas::getApiKey());
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
    public function getCustomFields()
    {
        $appsId = Apps::getIdByKey(Kanvas::getApiKey());
        $companiesId = Users::getSelf()->default_company;
        $customFieldModule = $this->getCustomFieldByModel();

        return CustomFields::find(['conditions' => [
            "companies_id:{$companiesId}",
            "apps_id:{$appsId}",
            "custom_fields_modules_id:{$customFieldModule->getId()}",
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
    public function createCustomFieldValue(string $label, string $value, int $isDefault = 0)
    {
        if ($customFieldModule = $this->getCustomFieldByModel()) {
            if ($customField = $this->getCustomField($label, (int) $customFieldModule->getId())) {
                return CustomFieldsValues::create([
                    'custom_fields_id' => $customField->getId(),
                    'label' => $label,
                    'value' => $value,
                    'is_default' => $isDefault,
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
    public function getCustomFieldByModel()
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
    public function processCustomFields(): void
    {
        if (!class_exists('Gewaer\Models\CustomFields')) {
            throw new Exception('Can\'t use Custom Fields without the Model, please run the migration');
        }

        $this->saveCustomFields();
    }

    /**
     * Save Custom Fields from Kanvas to the DB.
     *
     * @return void
     */
    protected function saveCustomFields(): void
    {
        $customFieldsAvailable = $this->getCustomFields();
        $customFieldsKeys = [];

        foreach ($customFieldsAvailable as $key => $value) {
            $customFieldsKeys[$value->label] = $value->name;
        }

        $user = Users::getSelf();
        if (isset($this->customFields) && !empty($this->customFields)) {
            foreach ($this->customFields as $key => $value) {
                if (isset($customFieldsKeys[$key])) {
                    $customFields = AppCustomFields::findFirst([
                        'conditions' => 'companies_id = :companies_id:  AND model_name = :model_name: AND entity_id = :entity_id: AND name = :name:',
                        'bind' => [
                            'companies_id' => $user->default_company,
                            'model_name' => get_class($this),
                            'entity_id' => $this->getId(),
                            'name' => $customFieldsKeys[$key],
                        ]
                    ]);

                    if (!$customFields) {
                        $customFields = new AppCustomFields();
                        $customFields->companies_id = $user->default_company;
                        $customFields->users_id = $user->getId();
                        $customFields->model_name = get_class($this);
                        $customFields->entity_id = $this->getId();
                        $customFields->name = $customFieldsKeys[$key];
                    }

                    $customFields->label = $key;
                    $customFields->value = $value;
                    $customFields->saveOrFail();
                }
            }
        }
    }

    /**
     * Delete all custom field value from this entity
     *
     * @return void
     */
    public function deleteCustomFields(): bool
    {
        $user = Users::getSelf();

        AppCustomFields::find([
            'conditions' => 'companies_id = :companies_id:  AND model_name = :model_name: AND entity_id = :entity_id:',
            'bind' => [
                'companies_id' => $user->default_company,
                'model_name' => get_class($this),
                'entity_id' => $this->getId(),
            ]
        ])->delete();

        return true;
    }

    /**
     * Set the custom field to update a custom field module.
     *
     * @param array $fields
     * @return void
     */
    public function setCustomFields($fields): void
    {
        $this->customFields = $fields;
    }

    /**
     * After the module was created we need to add it custom fields.
     *
     * @return  void
     */
    public function afterSave()
    {
        $this->processCustomFields();
        unset($this->customFields);
    }

    /**
     * Before delete remove content
     *
     * @return void
     */
    public function beforeDelete()
    {
        $this->deleteCustomFields();
    }
}
