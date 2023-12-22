# PHP SDK

<!-- BADGES_START -->
[![Latest Version][badge-release]][packagist]
[![PHP Version][badge-php]][php]
![tests](https://github.com/JustSteveKing/php-sdk/workflows/tests/badge.svg)
[![Total Downloads][badge-downloads]][downloads]

[badge-release]: https://img.shields.io/packagist/v/juststeveking/php-sdk.svg?style=flat-square&label=release
[badge-php]: https://img.shields.io/packagist/php-v/juststeveking/php-sdk.svg?style=flat-square
[badge-downloads]: https://img.shields.io/packagist/dt/juststeveking/php-sdk.svg?style=flat-square&colorB=mediumvioletred

[packagist]: https://packagist.org/packages/juststeveking/php-sdk
[php]: https://php.net
[downloads]: https://packagist.org/packages/juststeveking/php-sdk
<!-- BADGES_END -->

A framework for building SDKs in PHP.

## Installation

```shell
composer require juststeveking/php-sdk
```

## Purpose

The purpose of this package is to provide a consistent and interoperable way to build PHP SDKs to work with 3rd party APis.

## Usage

To get started with this library, you need to start by extending the `Client` class. Let's walk through building an SDK.

### Create your SDK class

```php
use JustSteveKing\Sdk\Client;

final class YourSDK extends Client
{
    //
}
```

Once this is in place, you can start adding your resources to the class. Let's add a `projects` method for a projects resource.

```php
use JustSteveKing\Sdk\Client;
use JustSteveKing\Sdk\Tests\Stubs\Resources\ProjectResource;

final class YourSDK extends Client
{
    public function projects()
    {
        return new ProjectResource(
            client: $this,
        );
    }
}
```

We return a new instance of our resource classes, passing through your SDK as a `client`. This is so that each resource is able to talk through the client to send requests.

Now, let's look at how to structure a resource.

```php
final class ProjectResource
{
    //
}
```

To save time, there are a collection of traits that you can use on your resources.

- `CanAccessClient` - which will add the default constructor required for a resource.
- `CanCreateDataObjects` - which will allow you to create DataObjects from API responses.
- `CanCreateRequests` - which will allow you to create HTTP requests and payloads using PSR-17 Factories.

Let's look at an example of a full resource class.

```php
use Exception;
use JustSteveKing\Sdk\Concerns\Resources;
use JustSteveKing\Tools\Http\Enums\Method;
use Ramsey\Collection\Collection;
use Throwable;

final class ProjectResource
{
    use Resources\CanAccessClient;
    use Resources\CanCreateDataObjects;
    use Resources\CanCreateRequests;

    public function all(): Collection
    {
        $request = $this->request(
            method: Method::GET,
            uri: '/projects',
        );

        try {
            $response = $this->client->send(
                request: $request,
            );
        } catch (Throwable $exception) {
            throw new Exception(
                message: 'Failed to list test.',
                code: $exception->getCode(),
                previous: $exception,
            );
        }

        return (new Collection(
            collectionType: Project::class,
            data: array_map(
                callback: static fn(array $data): Project => Project::make(
                    data: $data,
                ),
                array: (array) json_decode(
                    json: $response->getBody()->getContents(),
                    associative: true,
                    flags: JSON_THROW_ON_ERROR,
                ),
            ),
        ));
    }
}
```

We start by creating a request, and then try to get a response by sending it through the client.

Once we have a response, we create a `Collection` thanks to a package by Ben Ramsey. We pass through the type of each item we expect it to be,
then the data as an array. To create the data we map over the response content and statically create a new Data Object.

This allows us to keep our code clean, concise, and testable.

## Testing

To run the test:

```bash
composer run test
```

## Static analysis

To run the static analysis checks:

```bash
composer run stan
```

## Code Style

To run the code style fixer:

```bash
composer run pint
```

## Refactoring

To run the rector code refactoring:

```bash
composer run refactor
```

## Special Thanks

Without the following packages and people, this framework would have been a lot harder to build.

- [The PHP League - Object Mapper](https://github.com/thephpleague/object-mapper)
- [Ben Ramsey - Collection](https://github.com/ramsey/collection)
- [Larry Garfield - Serde](https://github.com/crell/serde)

## Credits

- [Steve McDougall](https://github.com/JustSteveKing)
- [All Contributors](../../contributors)

## LICENSE

The MIT License (MIT). Please see [License File](./LICENSE) for more information.
