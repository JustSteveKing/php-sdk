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

Example:

```php
use DI\Container;
use DI\ContainerBuilder;
use JustSteveKing\PhpSdk\Client;
use JustSteveKing\HttpSlim\HttpClient;
use Symfony\Component\HttpClient\Psr18Client;
use JustSteveKing\PhpSdk\Resources\AbstractResource;

// use PHP-DI to build our container
$builder = new ContainerBuilder();
$builder->useAutowiring(true);
$builder->useAnnotations(false);

$container = $builder->build();

// Build our SDK
$sdk = new Client(
    $container, // container (psr-11)
    'https://www.domain.com'
);

// Build our Http Client
$http = HttpClient::build(
    new Psr18Client(), // http client (psr-18)
    new Psr18Client(), // request factory (psr-17)
    new Psr18Client() // stream factory (psr-17)
);
$sdk->addTransport($http);

$strategy = new BasicStrategy(
    base64_encode("username:password")
);

$sdk->addStrategy($strategy);

$sdk->addResource('name', new class extends AbstractResource {
    protected string $path = 'name';
});

$response = $sdk->name->get(); // response interface (psr-7)
```

## Todo

- Add More Resource methods.
- Add filtering Resource methods.
- Add Auth strategies, including a NullStrategy - allowing no auth needed.


```php
$strategy = new HttpBasicStrategy();
$strategy = new NullStrategy();
$strategy = new JWTStrategy();
```
