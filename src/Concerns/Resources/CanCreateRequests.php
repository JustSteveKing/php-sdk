<?php

declare(strict_types=1);

namespace JustSteveKing\Sdk\Concerns\Resources;

use Http\Discovery\Psr17FactoryDiscovery;
use JustSteveKing\Sdk\Contracts\ClientContract;
use JustSteveKing\Tools\Http\Enums\Method;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\StreamInterface;

/**
 * @property-read ClientContract $client
 */
trait CanCreateRequests
{
    /**
     * Create a new Request using PSR-17 discovery.
     */
    public function request(Method $method, string $uri): RequestInterface
    {
        return Psr17FactoryDiscovery::findRequestFactory()->createRequest(
            method: $method->value,
            uri: "{$this->client->url()}{$uri}",
        );
    }

    /**
     * Create a new Stream for sending data to the API using PSR-17 discovery.
     */
    public function payload(string $payload): StreamInterface
    {
        return Psr17FactoryDiscovery::findStreamFactory()->createStream(
            content: $payload,
        );
    }
}
