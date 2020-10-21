<?php declare(strict_types=1);

namespace JustSteveKing\PhpSdk\Resources;

use JustSteveKing\UriBuilder\Uri;
use JustSteveKing\HttpSlim\HttpClient;
use Psr\Http\Message\ResponseInterface;

abstract class AbstractResource
{
    protected string $path;

    protected Uri $uri;

    protected HttpClient $http;

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

    public function loadPath(): self
    {
        $this->uri->addPath($this->path);

        return $this;
    }

    public function get(): ResponseInterface
    {
        return $this->http->get($this->uri->toString());
    }

    public function find($identifier): ResponseInterface
    {
        $this->uri->addPath(
            "{$this->path}/{$identifier}"
        );

        return $this->http->get($this->uri->toString());
    }
}
