<?php declare(strict_types=1);

namespace Demo;

use Demo\Resources\Server;
use DI\Container;
use JustSteveKing\HttpAuth\Strategies\BasicStrategy;
use JustSteveKing\HttpSlim\HttpClient;
use JustSteveKing\PhpSdk\Client;
use JustSteveKing\PhpSdk\ClientBuilder;
use JustSteveKing\UriBuilder\Uri;
use Symfony\Component\HttpClient\Psr18Client;

class Forge extends Client
{
    /**
     * Forge constructor.
     * @param string $apikey
     */
    public function __construct(string $apikey)
    {
        parent::__construct(new ClientBuilder(
            Uri::fromString('https://forge.laravel.com'),
            HttpClient::build(
                new Psr18Client(), // http client (psr-18)
                new Psr18Client(), // request factory (psr-17)
                new Psr18Client() // stream factory (psr-17)
            ),
            new BasicStrategy(
                $apikey
            ),
            new Container()
        ));
    }

    /**
     * @param string $apikey
     * @return static
     */
    public static function illuminate(string $apikey): self
    {
        $client = new self($apikey);

        // Add Resources
        $client->addResource('servers', new Server());

        return $client;
    }
}
