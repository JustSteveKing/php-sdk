<?php

declare(strict_types=1);

use JustSteveKing\HttpAuth\Strategies\BasicStrategy;
use JustSteveKing\HttpAuth\Strategies\Interfaces\StrategyInterface;
use JustSteveKing\HttpSlim\HttpClient;
use JustSteveKing\PhpSdk\SDK;
use JustSteveKing\PhpSdk\Tests\Stubs\ProjectResource;
use JustSteveKing\UriBuilder\Uri;
use PHPFox\Container\Container;

it('can build an sdk instance using the build method', function () {
    expect(
        SDK::build(
            uri: 'https://www.juststeveking.uk'
        )
    )->toBeInstanceOf(SDK::class);
});

it('can build an sdk instance using the constructor', function () {
    expect(
        new SDK(
            uri: Uri::fromString('https://www.juststeveking.uk'),
            client: HttpClient::build(),
            container: Container::getInstance(),
            strategy: new BasicStrategy(
                authString: 'test',
            )
        )
    )->toBeInstanceOf(SDK::class);
});

it('can access properties of the sdk', function () {
    $sdk = SDK::build(
        uri: 'https://www.juststeveking.uk'
    );

    expect(
        $sdk->container()
    )->toBeInstanceOf(Container::class);

    expect(
        $sdk->strategy()
    )->toBeInstanceOf(StrategyInterface::class);

    expect(
        $sdk->uri()
    )->toBeInstanceOf(Uri::class);

    expect(
        $sdk->client()
    )->toBeInstanceOf(HttpClient::class);
});

it('can add resources to the SDK', function () {
    $sdk = SDK::build(
        uri: 'https://www.juststeveking.uk',
    );

    $sdk->add(
        name: ProjectResource::name(),
        resource: ProjectResource::class
    );

    expect(
        $sdk->container()->make(
            abstract: ProjectResource::name(),
        ),
    )->toBeInstanceOf(ProjectResource::class);
});

it('can forward calls through to attached resource', function () {
    $sdk = SDK::build(
        uri: 'https://www.juststeveking.uk',
    );

    $sdk->add(
        name: ProjectResource::name(),
        resource: ProjectResource::class
    );

    expect(
        $sdk->projects->uri()
    )->toBeInstanceOf(Uri::class);
});
