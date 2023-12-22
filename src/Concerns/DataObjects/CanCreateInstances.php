<?php

declare(strict_types=1);

namespace JustSteveKing\Sdk\Concerns\DataObjects;

use JustSteveKing\Sdk\Contracts\DataObjectContract;
use League\ObjectMapper\ObjectMapperUsingReflection;

trait CanCreateInstances
{
    /**
     * @param class-string<DataObjectContract> $class
     * @param array<string,mixed> $payload
     */
    public static function create(string $class, array $payload): DataObjectContract
    {
        return (new ObjectMapperUsingReflection())->hydrateObject(
            className: $class,
            payload: $payload,
        );
    }
}
