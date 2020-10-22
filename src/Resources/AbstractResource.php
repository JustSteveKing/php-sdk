<?php declare(strict_types=1);

namespace JustSteveKing\PhpSdk\Resources;

use JustSteveKing\UriBuilder\Uri;
use JustSteveKing\HttpSlim\HttpClient;
use Psr\Http\Message\ResponseInterface;
use JustSteveKing\HttpAuth\Strategies\Interfaces\StrategyInterface;

abstract class AbstractResource
{
    protected Uri $uri;

    protected string $path;

    protected string $authHeader = 'Bearer';

    protected HttpClient $http;

    protected StrategyInterface $strategy;

    public function uri(): Uri
    {
        return $this->uri;
    }

    public function setUri(Uri $uri): self
    {
        $this->uri = $uri;

        return $this;
    }

    public function http(): HttpClient
    {
        return $this->http;
    }

    public function setHttp(HttpClient $http): self
    {
        $this->http = $http;

        return $this;
    }

    public function strategy(): StrategyInterface
    {
        return $this->strategy;
    }

    public function setStrategy(StrategyInterface $strategy): self
    {
        $this->strategy = $strategy;

        return $this;
    }

    public function loadPath(): self
    {
        $this->uri->addPath($this->path);

        return $this;
    }

    public function get(): ResponseInterface
    {
        return $this->http->get(
            $this->uri->toString(),
            $this->strategy()->getHeader($this->authHeader)
        );
    }

    public function find($identifier): ResponseInterface
    {
        $this->uri->addPath(
            "{$this->path}/{$identifier}"
        );

        return $this->http->get(
            $this->uri->toString(),
            $this->strategy()->getHeader($this->authHeader)
        );
    }
}
