<?php

declare(strict_types=1);

namespace JustSteveKing\Sdk\Tests;

use Http\Mock\Client as MockClient;
use InvalidArgumentException;
use JsonException;
use JustSteveKing\Sdk\Client;
use JustSteveKing\Sdk\Contracts\ClientContract;
use JustSteveKing\Sdk\Tests\Stubs\MockSDK;
use Nyholm\Psr7\Response;
use PHPUnit\Framework\TestCase;

abstract class PackageTestCase extends TestCase
{
    protected function newClient(string $apiKey = 'test'): Client
    {
        return new MockSDK(
            apiToken: $apiKey,
            url: 'https://api.mock-server.com',
        );
    }

    /**
     * @throws JsonException
     */
    protected function createMockClient(string $fixture): ClientContract
    {
        $mockClient = (new MockClient());
        $mockClient->addResponse(
            response: new Response(
                status: 200,
                headers: [],
                body: json_encode(
                    value: $this->fixture(
                        path: $fixture,
                    ),
                ),
            )
        );

        return (new MockSDK(
            apiToken: '1234',
            url: 'https://api.mock-server.com'
        ))->client(
            client: $mockClient,
        );
    }

    /**
     * @throws JsonException|InvalidArgumentException
     */
    protected function fixture(string $path): array
    {
        $filename = __DIR__ . "/Fixtures/{$path}.json";

        if ( ! file_exists(filename: $filename)) {
            throw new InvalidArgumentException(
                message: 'Failed to fetch fixture.',
            );
        }

        return json_decode(
            json: file_get_contents(
                filename: $filename,
            ),
            associative: true,
            flags: JSON_THROW_ON_ERROR,
        );
    }
}
