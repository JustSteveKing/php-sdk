# PHP SDK

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

$sdk->addResource('name', new class extends AbstractResource {
    protected string $path = 'name';
});

$response = $sdk->name->get(); // response interface (psr-7)
```
