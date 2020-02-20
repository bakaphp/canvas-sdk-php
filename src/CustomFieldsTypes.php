<?php

declare(strict_types=1);

namespace Kanvas\Sdk;

use Kanvas\Sdk\Api\Operations\All;
use Kanvas\Sdk\Api\Operations\Create;
use Kanvas\Sdk\Api\Operations\Delete;
use Kanvas\Sdk\Api\Operations\Update;
use Kanvas\Sdk\Api\Operations\Retrieve;
use Kanvas\Sdk\Api\Resource;

class CustomFieldsTypes extends Resource
{
    const OBJECT_NAME = 'custom-fields-types';

    use All;
    use Create;
    use Delete;
    use Update;
    use Retrieve;
}
