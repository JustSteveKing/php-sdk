<?php declare(strict_types=1);

require __DIR__ . '/../vendor/autoload.php';

use Demo\Resources\Post;
use JustSteveKing\PhpSdk\SDK;
use JustSteveKing\UriBuilder\Uri;
use JustSteveKing\HttpSlim\HttpClient;
use JustSteveKing\HttpAuth\Strategies\BasicStrategy;
use PHPFox\Container\Container;

// Build our Strategy
$strategy = new BasicStrategy(
    base64_encode("username:password")
);

// Build our SDK
$sdk = new SDK(
    uri: Uri::fromString('https://jsonplaceholder.typicode.com'),
    client: HttpClient::build(),
    container: Container::getInstance(),
    strategy: new BasicStrategy(
        authString: base64_encode("username:password")
    ),
);

$sdk->add(
    name: Post::name(),
    resource: Post::class,
);

$response = $sdk->posts->get(); // Http Response (psr-7)
