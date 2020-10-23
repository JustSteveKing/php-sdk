# PHP SDK

<!-- BADGES_START -->
[![Latest Version][badge-release]][packagist]
[![PHP Version][badge-php]][php]
![tests](https://github.com/JustSteveKing/php-sdk/workflows/tests/badge.svg)
![Check & fix styling](https://github.com/JustSteveKing/php-sdk/workflows/Code%20style/badge.svg)
[![Total Downloads][badge-downloads]][downloads]

[badge-release]: https://img.shields.io/packagist/v/juststeveking/php-sdk.svg?style=flat-square&label=release
[badge-php]: https://img.shields.io/packagist/php-v/juststeveking/php-sdk.svg?style=flat-square
[badge-downloads]: https://img.shields.io/packagist/dt/juststeveking/php-sdk.svg?style=flat-square&colorB=mediumvioletred

[packagist]: https://packagist.org/packages/juststeveking/php-sdk.svg
[php]: https://php.net
[downloads]: https://packagist.org/packages/juststeveking/php-sdk.svg
<!-- BADGES_END -->

Work in progress.

```php
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
```
