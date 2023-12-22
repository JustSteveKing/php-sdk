<?php

declare(strict_types=1);

namespace JustSteveKing\Sdk\Contracts;

/**
 * @template T
 */
interface DataObjectContract
{
    /**
     * @param class-string<T> $class
     * @param array<string,mixed> $payload
     */
    public static function create(string $class, array $payload): DataObjectContract;

    /**
     * Create a new DataObject.
     *
     * @param array<string,mixed> $data
     */
    public static function make(array $data): DataObjectContract;
}
