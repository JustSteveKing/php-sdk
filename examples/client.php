<?php declare(strict_types=1);

require __DIR__ . '/../vendor/autoload.php';

use DI\Container;
use DI\ContainerBuilder;
use JustSteveKing\PhpSdk\Client;
use JustSteveKing\UriBuilder\Uri;
use JustSteveKing\HttpSlim\HttpClient;
use JustSteveKing\PhpSdk\ClientBuilder;
use Symfony\Component\HttpClient\Psr18Client;
use JustSteveKing\HttpAuth\Strategies\BasicStrategy;
use JustSteveKing\PhpSdk\Resources\AbstractResource;

// Build our Container
$builder = new ContainerBuilder();
$builder->useAutowiring(true);
$builder->useAnnotations(false);
$container = $builder->build();

// Build our Http Client
$http = HttpClient::build(
    new Psr18Client(), // http client (psr-18)
    new Psr18Client(), // request factory (psr-17)
    new Psr18Client() // stream factory (psr-17)
);

// Build our Strategy
$strategy = new BasicStrategy(
    base64_encode("username:password")
);

$builder = new ClientBuilder(
    Uri::fromString('https://www.domain.com'),
    $http,
    $strategy,
    $container
);


// Build our SDK
$sdk = new Client($builder);

$sdk->addResource('name', new class extends AbstractResource {
    protected string $path = 'name';
});

$response = $sdk->name->get(); // Http Response (psr-7)
