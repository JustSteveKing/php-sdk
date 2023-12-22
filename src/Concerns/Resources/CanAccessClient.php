<?php

declare(strict_types=1);

namespace JustSteveKing\Sdk\Concerns\Resources;

use Crell\Serde\SerdeCommon;
use JustSteveKing\Sdk\Contracts\ClientContract;

trait CanAccessClient
{
    /**
     * All Resources accept the Client in the constructor and instantiates Serve for payload hydration.
     */
    public function __construct(
        protected readonly ClientContract $client,
        protected readonly SerdeCommon $serializer = new SerdeCommon(),
    ) {}
}
