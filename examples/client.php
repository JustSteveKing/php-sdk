<?php declare(strict_types=1);

require __DIR__ . '/../vendor/autoload.php';

use JustSteveKing\UriBuilder\Uri;
use JustSteveKing\HttpSlim\HttpClient;
use JustSteveKing\HttpAuth\Strategies\BasicStrategy;
use JustSteveKing\PhpSdk\Resources\AbstractResource;

// Build our Strategy
$strategy = new BasicStrategy(
    base64_encode("username:password")
);

// Build our SDK
$sdk = new \JustSteveKing\PhpSdk\SDK(
    uri: Uri::fromString('https://api.acme.com'),
    client: HttpClient::build(),
    strategy: $strategy,
    container: \PHPFox\Container\Container::getInstance(),
);

$sdk->add(
    name: 'name',
    resource: \Demo\Resources\Server::class,
);

$response = $sdk->name->get(); // Http Response (psr-7)
