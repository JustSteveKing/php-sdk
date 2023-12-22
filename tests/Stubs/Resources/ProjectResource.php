<?php

declare(strict_types=1);

namespace JustSteveKing\Sdk\Tests\Stubs\Resources;

use JustSteveKing\Sdk\Concerns\Resources\CanAccessClient;
use JustSteveKing\Sdk\Concerns\Resources\CanCreateDataObjects;
use JustSteveKing\Sdk\Concerns\Resources\CanCreateRequests;

final class ProjectResource
{
    use CanAccessClient;
    use CanCreateDataObjects;
    use CanCreateRequests;
}
