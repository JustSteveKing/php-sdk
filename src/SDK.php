<?php

declare(strict_types=1);

namespace JustSteveKing\PhpSdk;

use InvalidArgumentException;
use JustSteveKing\HttpAuth\Strategies\Interfaces\StrategyInterface;
use JustSteveKing\HttpAuth\Strategies\NullStrategy;
use JustSteveKing\HttpSlim\HttpClient;
use JustSteveKing\PhpSdk\Contracts\ResourceContract;
use JustSteveKing\PhpSdk\Resources\AbstractResource;
use JustSteveKing\UriBuilder\Uri;
use PHPFox\Container\Container;
use PHPFox\Container\Exceptions\BindingResolutionException;
use ReflectionClass;
use Throwable;

class SDK
{
    public function __construct(
        public Uri $uri,
        public HttpClient $client,
        public Container $container,
        public null|StrategyInterface $strategy
    ) {
    }

    public static function build(
        string $uri,
        null|StrategyInterface $strategy = null,
        null|Container $container = null,
    ): SDK {
        return new SDK(
            uri: Uri::fromString(
            uri: $uri,
            ),
            client: HttpClient::build(),
            container: $container ?? Container::getInstance(),
            strategy: $strategy ?? new NullStrategy(),
        );
    }

    /**
     * @param string $name
     * @param string $resource
     * @return $this
     */
    public function add(string $name, string $resource): self
    {
        try {
            $reflection = new ReflectionClass(
                objectOrClass: $resource,
            );

            if (
                ! $reflection->implementsInterface(interface: ResourceContract::class) &&
                ! $reflection->isSubclassOf(class: AbstractResource::class)
            ) {
                throw new InvalidArgumentException(
                    message: "[$resource] needs to implement ResourceContract and extend the AbstractResource class.",
                );
            }
        } catch (Throwable) {}

        $this->container()->bind(
            abstract: $name,
            concrete: fn() => new $resource(
                sdk: $this,
            ),
        );

        return $this;
    }

    /**
     * @return Uri
     */
    public function uri(): Uri
    {
        return $this->uri;
    }

    /**
     * @return HttpClient
     */
    public function client(): HttpClient
    {
        return $this->client;
    }

    /**
     * @return StrategyInterface
     */
    public function strategy(): StrategyInterface
    {
        return $this->strategy;
    }

    /**
     * @return Container
     */
    public function container(): Container
    {
        return $this->container;
    }

    /**
     * @param string $name
     * @return ResourceContract
     * @throws BindingResolutionException|InvalidArgumentException
     */
    public function __get(string $name): ResourceContract
    {
        if (! $this->container()->contains(abstract: $name)) {
            throw new InvalidArgumentException(
                message: "Resource [$name] has not been registered on the SDK.",
            );
        }

        return $this->container()->make(
            abstract: $name,
        );
    }
}
