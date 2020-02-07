<?php

declare(strict_types=1);

namespace Kanvas\Sdk\Traits;

use Exception;
use Kanvas\Sdk\SystemModules;
use Kanvas\Sdk\FileSystem;
use Kanvas\Sdk\Apps;
use Kanvas\Sdk\Users as KanvasUsers;
use Kanvas\Sdk\FileSystemEntities;
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
    public $uploadedFiles = [];

    /**
     * Associated the list of uploaded files to this entity.
     *
     * call on the after saves
     *
     * @return void
     */
    protected function associateFileSystem(): bool
    {
        if (!empty($this->uploadedFiles) && is_array($this->uploadedFiles)) {
            foreach ($this->uploadedFiles as $file) {
                if (!isset($file['filesystem_id'])) {
                    continue;
                }

                if ($fileSystem = FileSystem::getById($file['filesystem_id'])) {
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
     * @return void
     */
    public function attach(array $files): bool
    {
        $appId = Apps::getIdByKey(getenv('GEWAER_APP_ID'))->id;
        $systemModule = SystemModules::getSystemModuleByModelName(self::class, $appId);
        $currentCompanyId = KanvasUsers::getSelf()->default_company;

        foreach ($files as $file) {
            //im looking for the file inside an array
            if (!isset($file['file'])) {
                continue;
            }

            if (!$file['file'] instanceof FileSystem) {
                throw new Exception('Cant attach a none Filesytem to this entity');
            }

            $fileSystemEntities = null;
            //check if we are updating the attachment
            if ($id = (int) $file['id']) {
                $fileSystemEntities = FileSystemEntities::getByIdWithSystemModule($id, $systemModule->id, $appId , $currentCompanyId);
            }

            //new attachment
            if (!is_object($fileSystemEntities)) {
                // $fileSystemEntities = new FileSystemEntities();
                // $fileSystemEntities->system_modules_id = $systemModule->getId();
                // $fileSystemEntities->companies_id = $file['file']->companies_id;
                // $fileSystemEntities->entity_id = $this->getId();
                // $fileSystemEntities->created_at = $file['file']->created_at;

                //If filesystem entity does not exist then create a new one
                $users = FileSystemEntities::create([
                    'system_modules_id' => $systemModule->id,
                    'companies_id' => $file['file']->companies_id,
                    'entity_id' => $this->id,
                    'created_at' => $file['file']->created_at
                ]);
            }

            //If filesystem entity does exist then update
            $users = FileSystemEntities::update($fileSystemEntities->id, [
                'filesystem_id' => $file['file']->id,
                'field_name' => $file['field_name'] ?? null,
                'is_deleted' => 0
            ]);

            // $fileSystemEntities->filesystem_id = $file['file']->getId();
            // $fileSystemEntities->field_name = $file['field_name'] ?? null;
            // $fileSystemEntities->is_deleted = 0;
            // $fileSystemEntities->saveOrFail();

            if (!is_null($this->filesNewAttachedPath())) {
                $file['file']->move($this->filesNewAttachedPath());
            }
        }

        return true;
    }

    /**
     * Given this entity define a new path.
     *
     * @param string $path
     * @return string
     */
    protected function filesNewAttachedPath(): ?string
    {
        return null;
    }

    /**
     * Over write, because of the phalcon events.
     *
     * @param array data
     * @param array whiteList
     * @return boolean
     */
    public function update($data = null, $whiteList = null): bool
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
    public function deleteFiles(): bool
    {
        $appId = Apps::getIdByKey(getenv('GEWAER_APP_ID'))->id;
        $currentCompanyId = KanvasUsers::getSelf()->default_company;

        if ($files = FileSystemEntities::getAllByEntityId($this->getId(), $appId, $currentCompanyId)) {
            foreach ($files as $file) {
                FileSystemEntities::update($file->id, [
                    "is_deleted"=> 1
                ]);
            }
        }

        return true;
    }
}
