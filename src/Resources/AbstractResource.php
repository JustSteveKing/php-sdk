<?php declare(strict_types=1);

namespace JustSteveKing\PhpSdk\Resources;

use JustSteveKing\HttpAuth\Strategies\Interfaces\StrategyInterface;
use JustSteveKing\HttpSlim\HttpClient;
use JustSteveKing\UriBuilder\Uri;
use Psr\Http\Message\ResponseInterface;

/**
 * Class AbstractResource
 */
abstract class AbstractResource
{
    /**
     * @var Uri
     */
    protected Uri $uri;

    /**
     * @var string
     */
    protected string $path;

    /**
     * @var string
     */
    protected string $authHeader = 'Bearer';

    /**
     * @var HttpClient
     */
    protected HttpClient $http;

    /**
     * @var StrategyInterface
     */
    protected StrategyInterface $strategy;

    /**
     * @return Uri
     */
    public function uri(): Uri
    {
        return $this->uri;
    }

    /**
     * @param Uri $uri
     *
     * @return $this
     */
    public function setUri(Uri $uri): self
    {
        $this->uri = $uri;

        return $this;
    }

    /**
     * @return HttpClient
     */
    public function http(): HttpClient
    {
        return $this->http;
    }

    /**
     * @param HttpClient $http
     *
     * @return $this
     */
    public function setHttp(HttpClient $http): self
    {
        $this->http = $http;

        return $this;
    }

    /**
     * @return StrategyInterface
     */
    public function strategy(): StrategyInterface
    {
        return $this->strategy;
    }

    /**
     * @param StrategyInterface $strategy
     *
     * @return $this
     */
    public function setStrategy(StrategyInterface $strategy): self
    {
        $this->strategy = $strategy;

        return $this;
    }

    /**
     * @return $this
     */
    public function loadPath(): self
    {
        $this->uri->addPath($this->path);

        return $this;
    }

    /**
     * @return ResponseInterface
     */
    public function get(): ResponseInterface
    {
        return $this->http->get(
            $this->uri->toString(),
            $this->strategy()->getHeader($this->authHeader)
        );
    }

    /**
     * @param $identifier
     *
     * @return ResponseInterface
     */
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
