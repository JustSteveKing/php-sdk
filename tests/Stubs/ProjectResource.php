<?php

declare(strict_types=1);

namespace JustSteveKing\PhpSdk\Tests\Stubs;

use JustSteveKing\PhpSdk\Contracts\ResourceContract;
use JustSteveKing\PhpSdk\Resources\AbstractResource;

class ProjectResource extends AbstractResource implements ResourceContract
{
    /**
     * @return string
     */
    public static function name(): string
    {
        return 'projects';
    }
}
