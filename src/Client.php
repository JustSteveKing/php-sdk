<?php declare(strict_types=1);

namespace JustSteveKing\PhpSdk;

use JustSteveKing\UriBuilder\Uri;
use Psr\Container\ContainerInterface;
use JustSteveKing\HttpSlim\HttpClient;
use JustSteveKing\PhpSdk\Resources\AbstractResource;

class Client
{
    /**
     * @var ContainerInterface
     */
    private ContainerInterface $factory;

    /**
     * @var HttpClient
     */
    private HttpClient $http;

    /**
     * @var Uri
     */
    private Uri $uri;

    public function __construct(
        ContainerInterface $factory,
        string $uri
    ){
        $this->factory = $factory;
        $this->uri = Uri::fromString($uri);
    }

    public function addTransport(HttpClient $http): self
    {
            $this->http = $http;

            return $this;
    }

    public function addResource(string $name, AbstractResource $resource): self
    {
        $this->factory()->set($name, $resource);

        return $this;
    }

    public function uri(): Uri
    {
        return $this->uri;
    }

    public function factory(): ContainerInterface
    {
        return $this->factory;
    }

    public function __get(string $name)
    {
        if (! $this->factory()->has($name)) {
            throw new RuntimeException("Resource {$name} has not been registered with the SDK.");
        }

        $resource = $this->factory()->get($name);

        $resource->setHttp($this->http)->setUri($this->uri)->loadPath();

        return $resource;
    }
}