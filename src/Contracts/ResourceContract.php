<?php

declare(strict_types=1);

namespace JustSteveKing\PhpSdk\Contracts;

interface ResourceContract
{
    /**
     * @return string
     */
    public static function name(): string;
}
