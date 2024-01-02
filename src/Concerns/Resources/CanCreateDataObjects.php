<?php

declare(strict_types=1);

namespace JustSteveKing\Sdk\Concerns\Resources;

use Crell\Serde\SerdeCommon;
use JustSteveKing\Sdk\Contracts\DataObjectContract;

/**
 * @property SerdeCommon $serializer
 */
trait CanCreateDataObjects
{
    /**
     * Make a new Data Object from a response body.
     *
     * @param class-string<DataObjectContract> $class
     */
    public function make(string $class, string $body): DataObjectContract
    {
        return $this->serializer->deserialize(
            serialized: $body,
            from: 'json',
            to: $class,
        );
    }
}
