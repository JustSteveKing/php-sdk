<?php declare(strict_types=1);

namespace Demo;

use Demo\Resources\Server;
use JustSteveKing\HttpAuth\Strategies\BasicStrategy;
use JustSteveKing\HttpSlim\HttpClient;
use JustSteveKing\PhpSdk\SDK;
use JustSteveKing\UriBuilder\Uri;
use PHPFox\Container\Container;

class Forge extends SDK
{
    /**
     * Forge constructor.
     * @param string $apikey
     */
    public function __construct(string $apikey)
    {
        parent::__construct(
            uri: Uri::fromString(
                uri: 'https://forge.laravel.com',
            ),
            client: HttpClient::build(),
            container: Container::getInstance(),
            strategy: new BasicStrategy(
                authString: $apikey,
            ),
        );
    }

    /**
     * @param string $apikey
     * @return static
     */
    public static function illuminate(string $apikey): self
    {
        $client = new self($apikey);

        // Add Resources
        $client->add(
            name: Server::name(),
            resource: Server::class,
        );

        return $client;
    }
}
