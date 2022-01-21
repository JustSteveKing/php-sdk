<?php

declare(strict_types=1);

namespace Demo\Resources;

use JustSteveKing\PhpSdk\Contracts\ResourceContract;
use JustSteveKing\PhpSdk\Resources\AbstractResource;

class Post extends AbstractResource implements ResourceContract
{
    protected string $path = '/posts';

    /**
     * @return string
     */
    public static function name(): string
    {
        return 'posts';
    }
}
