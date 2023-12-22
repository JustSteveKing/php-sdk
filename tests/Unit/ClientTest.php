<?php

declare(strict_types=1);

namespace JustSteveKing\Sdk\Tests\Unit;

use Http\Client\Common\PluginClient;
use Http\Mock\Client as MockClient;
use JustSteveKing\Sdk\Client;
use JustSteveKing\Sdk\Contracts\ClientContract;
use JustSteveKing\Sdk\Tests\PackageTestCase;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;

#[CoversClass(Client::class)]
final class ClientTest extends PackageTestCase
{
    #[Test]
    public function it_can_create_the_client(): void
    {
        $this->assertInstanceOf(
            expected: ClientContract::class,
            actual: $this->newClient(),
        );
    }

    #[Test]
    public function it_can_setup_the_http_client(): void
    {
        $client = $this->newClient();

        $this->assertNull(
            actual: $client->http,
        );

        $client->setup();

        $this->assertInstanceOf(
            expected: PluginClient::class,
            actual: $client->http,
        );
    }

    #[Test]
    public function it_can_set_a_new_http_client(): void
    {
        $client = $this->newClient()->setup();

        $this->assertInstanceOf(
            expected: PluginClient::class,
            actual: $client->http,
        );

        $this->assertInstanceOf(
            expected: MockClient::class,
            actual: $client->client(
                client: new MockClient(),
            )->http,
        );
    }
}
