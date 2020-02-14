<?php

declare(strict_types=1);

namespace Kanvas\Sdk\Traits;

use Kanvas\Sdk\CustomFields;
use Kanvas\Sdk\CustomFieldsModules;

/**
 * Trait FractalTrait.
 *
 * @package Canvas\Traits
 */
trait CustomFieldsTrait
{
    /**
     * Create Custom Fields Modules
     *
     * @return Kanvas\Sdk\KanvasObject
     */
    public function createCustomFieldsModule(string $name)
    {
        return CustomFieldsModules::create([
            'name'=> $name,
            'model_name'=> $this->model
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
            'name'=>$name,
            'fields_type_id'=> $fieldTypeId,
            'custom_fields_modules_id'=> $customFieldsModuleId
        ]);
    }
}
