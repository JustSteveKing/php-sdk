<?php

declare(strict_types=1);

namespace JustSteveKing\Sdk;

use Http\Client\Common\Plugin;
use Http\Client\Common\PluginClient;
use Http\Discovery\Psr18ClientDiscovery;
use JustSteveKing\Sdk\Contracts\ClientContract;
use JustSteveKing\Sdk\Exceptions\ClientSetupException;
use Psr\Http\Client\ClientExceptionInterface;
use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

abstract class Client implements ClientContract
{
    /**
     * The PSR-18 HTTP Client itself.
     */
    public null|ClientInterface $http = null;

    /**
     * Create a new SDK API Client.
     */
    public function __construct(
        protected readonly string $apiToken,
        protected readonly string $url,
    ) {}

    /**
     * Set up the client using PSR-18 discovery, passing in plugins.
     *
     * @see https://docs.php-http.org/en/latest/plugins/introduction.html#install
     *
     * @param  array<int,Plugin>  $plugins
     */
    public function setup(array $plugins = []): ClientContract
    {
        $this->http = new PluginClient(
            client: Psr18ClientDiscovery::find(),
            plugins: $plugins,
        );

        return $this;
    }

    /**
     * Return the URL for the API.
     */
    public function url(): string
    {
        return $this->url;
    }

    /**
     * Set the HTTP Client for the SDK.
     */
    public function client(ClientInterface $client): ClientContract
    {
        $this->http = $client;

        return $this;
    }

    /**
     * Send an API Request.
     *
     * @throws ClientSetupException|ClientExceptionInterface
     */
    public function send(RequestInterface $request): ResponseInterface
    {
        if ( ! $this->http instanceof ClientInterface) {
            throw new ClientSetupException(
                message: 'You have not setup the client correctly, you need to set the HTTP Client using `setup` or `client`.',
            );
        }

        return $this->http->sendRequest(
            request: $request,
        );
    }
}
