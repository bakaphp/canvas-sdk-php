<?php

declare(strict_types=1);

namespace Kanvas\Sdk\Mapper;

use AutoMapperPlus\CustomMapper\CustomMapper;
use Kanvas\Sdk\Dto\Files;
use Kanvas\Sdk\Filesystem;

// You can either extend the CustomMapper, or just implement the MapperInterface
// directly.
class FileMapper extends CustomMapper
{
    public $systemModuleId;
    public $entityId;

    /**
     * constructor.
     *
     * @param int $entityId
     * @param int $systemModuleId
     */
    public function __construct(int $entityId, int $systemModuleId)
    {
        $this->systemModuleId = $systemModuleId;
        $this->entityId = $entityId;
    }

    /**
     * @param Canvas\Models\FileSystem $file
     * @param Canvas\Dto\Files $fileDto
     *
     * @return Files
     */
    public function mapToObject($fileEntity, $fileDto, array $context = [])
    {
        //Get filesystem records
        $file = Filesystem::retrieve($fileEntity->filesystem_id, [], []);

        $fileDto->id = $fileEntity->id;
        $fileDto->filesystem_id = $fileEntity->filesystem_id;
        $fileDto->name = $file->name;
        $fileDto->field_name = $fileEntity->field_name;
        $fileDto->url = $file->url;
        $fileDto->size = $file->size;
        $fileDto->file_type = $file->file_type;

        return $fileDto;
    }
}
