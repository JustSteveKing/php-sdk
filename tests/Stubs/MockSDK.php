<?php

declare(strict_types=1);

namespace JustSteveKing\Sdk\Tests\Stubs;

use JustSteveKing\Sdk\Client;
use JustSteveKing\Sdk\Tests\Stubs\Resources\TestResource;

final class MockSDK extends Client
{
    public function tests(): TestResource
    {
        return new TestResource(
            client: $this,
        );
    }
}
