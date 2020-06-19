<?php

declare(strict_types=1);

namespace Kanvas\Sdk\Contracts;

use Exception;
use Kanvas\Sdk\Apps;
use Kanvas\Sdk\Dto\Files;
use Kanvas\Sdk\FileSystem;
use Kanvas\Sdk\FileSystemEntities;
use Kanvas\Sdk\Kanvas;
use Kanvas\Sdk\KanvasObject;
use Kanvas\Sdk\Mapper\FileMapper;
use Kanvas\Sdk\SystemModules;
use Kanvas\Sdk\Users as KanvasUsers;
use Phalcon\Di;

/**
 * Trait ResponseTrait.
 *
 * @package Canvas\Traits
 *
 * @property Users $user
 * @property AppsPlans $appPlan
 * @property CompanyBranches $branches
 * @property Companies $company
 * @property UserCompanyApps $app
 * @property \Phalcon\Di $di
 *
 */
trait FileSystemModelTrait
{
    /**
     * Mapper Trait.
     */
    use MapperTrait;

    /**
     * Uploaded Files Array.
     */
    public $uploadedFiles = [];

    /**
     * Associated the list of uploaded files to this entity.
     *
     * call on the after saves
     *
     * @return void
     */
    protected function associateFileSystem() : bool
    {
        if (!empty($this->uploadedFiles) && is_array($this->uploadedFiles)) {
            foreach ($this->uploadedFiles as $file) {
                if (!isset($file['filesystem_id'])) {
                    continue;
                }

                if ($fileSystem = FileSystem::getById((string)$file['filesystem_id'])) {
                    $this->attach([[
                        'id' => $file['id'] ?: 0,
                        'file' => $fileSystem,
                        'field_name' => $file['field_name'] ?? ''
                    ]]);
                }
            }
        }

        return true;
    }

    /**
     * Given the array of files we will attch this files to the files.
     * [
     *  'file' => $file,
     *  'file_name' => 'dfadfa'
     * ];.
     *
     * @param array $files
     *
     * @return void
     */
    public function attach(array $files) : bool
    {
        $appId = Apps::getIdByKey(getenv('GEWAER_APP_ID'));
        $systemModule = SystemModules::validateOrCreate(self::class, (int)$appId);
        $currentCompanyId = KanvasUsers::getSelf()->default_company;

        foreach ($files as $file) {
            //im looking for the file inside an array
            if (!isset($file['file'])) {
                continue;
            }

            if (!$file['file'] instanceof KanvasObject) {
                throw new Exception('Cant attach a none Filesytem to this entity');
            }

            $fileSystemEntities = null;
            //check if we are updating the attachment
            if ($id = (int) $file['id']) {
                $fileSystemEntities = FileSystemEntities::getByIdWithSystemModule($id, (int)$systemModule->id, (int)$appId, $currentCompanyId);
            }

            if (!is_object($fileSystemEntities)) {
                //If filesystem entity does not exist then create a new one
                FileSystemEntities::create([
                    'filesystem_id' => $file['file']->id,
                    'field_name' => $file['field_name'] ?? null,
                    'system_modules_id' => $systemModule->id,
                    'companies_id' => $file['file']->companies_id,
                    'entity_id' => $this->id,
                    'created_at' => $file['file']->created_at,
                    'is_deleted' => 0
                ]);
            } else {
                //If filesystem entity does exist then update
                FileSystemEntities::update($fileSystemEntities->id, [
                    'filesystem_id' => $file['file']->id,
                    'field_name' => $file['field_name'] ?? null,
                    'is_deleted' => 0
                ]);
            }
        }

        return true;
    }

    /**
     * Given this entity define a new path.
     *
     * @param string $path
     *
     * @return string
     */
    protected function filesNewAttachedPath() : ?string
    {
        return null;
    }

    /**
     * Over write, because of the phalcon events.
     *
     * @param array data
     * @param array whiteList
     *
     * @return boolean
     */
    public function update($data = null, $whiteList = null) : bool
    {
        //associate uploaded files
        if (isset($data['files'])) {
            if (!empty($data['files'])) {
                /**
                 * @todo for now lets delete them all and updated them
                 * look for a better solution later, this can since we are not using transactions
                 */
                $this->deleteFiles();

                $this->uploadedFiles = $data['files'];
            } else {
                $this->deleteFiles();
            }
        }

        return parent::update($data, $whiteList);
    }

    /**
     * Delete all the files from a module.
     *
     * @return bool
     */
    public function deleteFiles() : bool
    {
        $appId = Apps::getIdByKey(getenv('GEWAER_APP_ID'));
        $currentCompanyId = KanvasUsers::getSelf()->default_company;

        if ($files = FileSystemEntities::getAllByEntityId($this->getId(), (int)$appId, $currentCompanyId)) {
            foreach ($files as $file) {
                FileSystemEntities::update($file->id, [
                    'is_deleted' => 1
                ]);
            }
        }

        return true;
    }

    /**
     * Get User Files.
     *
     * @return KanvasObject
     */
    public function getFiles() : array
    {
        $appsId = Apps::getIdByKey(getenv('GEWAER_APP_ID'));
        $systemModule = SystemModules::validateOrCreate(self::class, (int)$appsId);
        $attachments = FileSystemEntities::find(['conditions' => ["entity_id:{$this->id}", "system_modules_id:{$systemModule->id}", 'is_deleted:0']]);

        //Filemapper
        $fileMapper = new FileMapper((int)$this->id, (int)$systemModule->id);

        /**
         * Call mapper service.
         *
         * @todo Move this to use it globally
         */
        $config = $this->configureAutoMapper(KanvasObject::class, Files::class, $fileMapper);

        $autoMapper = $this->instantiateAutoMapper($config);

        return $autoMapper->mapMultiple($attachments, Files::class);
    }

    /**
     * Undocumented function.
     *
     * @param string $fieldName
     *
     * @todo Adapt this function to the sdk
     *
     * @return string|null
     */
    public function getFileByName(string $fieldName) : ?object
    {
        $appsId = Apps::getIdByKey(getenv('GEWAER_APP_ID'));
        $systemModule = SystemModules::validateOrCreate(self::class, (int)$appsId);
        $fileSystemEntities = FileSystemEntities::find(['conditions' => ["entity_id:{$this->id}", "system_modules_id:{$systemModule->id}", "field_name:{$fieldName}", 'is_deleted:0']]);
        return is_object($fileSystemEntities) ? $fileSystemEntities : null;
    }
}
