<?php

declare(strict_types=1);

namespace JustSteveKing\Sdk\Tests\Stubs\DataObjects;

use JustSteveKing\Sdk\Concerns\DataObjects\CanCreateInstances;
use JustSteveKing\Sdk\Contracts\DataObjectContract;

final class TestDataObject implements DataObjectContract
{
    use CanCreateInstances;

    public function __construct(
        public readonly string $id,
        public readonly int $number,
    ) {}

    public static function make(array $data): DataObjectContract
    {
        return self::create(
            class: self::class,
            payload: $data,
        );
    }
}
