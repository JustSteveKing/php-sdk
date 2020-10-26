<?php declare(strict_types=1);

namespace Demo\Resources;

use JustSteveKing\PhpSdk\Resources\AbstractResource;

class Server extends AbstractResource
{
    protected string $path = 'api/v1/servers';
}
