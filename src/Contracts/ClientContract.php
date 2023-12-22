<?php

declare(strict_types=1);

namespace JustSteveKing\Sdk\Contracts;

use Http\Client\Common\Plugin;
use JustSteveKing\Sdk\Exceptions\ClientSetupException;
use Psr\Http\Client\ClientExceptionInterface;
use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

interface ClientContract
{
    /**
     * Set up the client using PSR-18 discovery, passing in plugins.
     *
     * @see https://docs.php-http.org/en/latest/plugins/introduction.html#install
     *
     * @param  array<int,Plugin>  $plugins
     */
    public function setup(array $plugins = []): ClientContract;
    /**
     * Return the URL for the API.
     */
    public function url(): string;

    /**
     * Set the HTTP Client for the SDK Client.
     */
    public function client(ClientInterface $client): ClientContract;

    /**
     * Send an API Request.
     *
     * @throws ClientSetupException|ClientExceptionInterface
     */
    public function send(RequestInterface $request): ResponseInterface;
}
