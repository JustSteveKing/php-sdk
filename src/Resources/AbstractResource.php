<?php declare(strict_types=1);

namespace JustSteveKing\PhpSdk\Resources;

use JustSteveKing\HttpAuth\Strategies\Interfaces\StrategyInterface;
use JustSteveKing\HttpSlim\HttpClient;
use JustSteveKing\PhpSdk\SDK;
use JustSteveKing\UriBuilder\Uri;
use Psr\Http\Message\ResponseInterface;
use RuntimeException;

/**
 * Class AbstractResource
 */
abstract class AbstractResource
{
    public function __construct(
        private SDK $sdk,
        private null|string $path = null,
        protected string $authHeader = 'Bearer',
        private array $with = [],
        protected array $relations = [],
        protected bool $strictRelations = false,
        private null|string $load = null,
    ) {}

    /**
     * @return SDK
     */
    public function sdk(): SDK
    {
        return $this->sdk;
    }

    /**
     * @return array
     */
    public function getWith(): array
    {
        return $this->with;
    }

    /**
     * @param array $with
     * @return $this
     */
    public function with(array $with): self
    {
        if ($this->strictRelations) {
            foreach ($with as $resource) {
                if (! in_array($resource, $this->relations)) {
                    throw new RuntimeException(
                        message: "Cannot sideload {$resource} as it has not been registered as an available resource",
                    );
                }
            }
        }

        $this->with = $with;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getLoad(): string|null
    {
        return $this->load;
    }

    /**
     * @param string|int $identifier
     * @return $this
     */
    public function load(string|int $identifier): self
    {
        $this->load = (string) $identifier;

        return $this;
    }

    /**
     * @param Uri $uri
     *
     * @return $this
     */
    public function uri(Uri $uri): self
    {
        $this->sdk()->uri = $uri;

        return $this;
    }

    /**
     * @param HttpClient $http
     *
     * @return $this
     */
    public function client(HttpClient $http): self
    {
        $this->sdk()->client = $http;

        return $this;
    }

    /**
     * @param StrategyInterface $strategy
     *
     * @return $this
     */
    public function strategy(StrategyInterface $strategy): self
    {
        $this->sdk()->strategy = $strategy;

        return $this;
    }

    /**
     * @return $this
     */
    public function loadPath(): self
    {
        $this->sdk()->uri()->addPath(
            path: $this->path,
        );

        return $this;
    }

    /**
     * @return ResponseInterface
     * @throws \Psr\Http\Client\ClientExceptionInterface
     * @throws \Throwable
     */
    public function get(): ResponseInterface
    {
        return $this->sdk()->client()->get(
            uri: $this->sdk()->uri()->toString(),
            headers: $this->sdk()->strategy()->getHeader(
                prefix: $this->authHeader,
            )
        );
    }

    /**
     * @param string|int $identifier
     * @return ResponseInterface
     * @throws \Psr\Http\Client\ClientExceptionInterface
     * @throws \Throwable
     */
    public function find(string|int $identifier): ResponseInterface
    {
        $this->sdk()->uri()->addPath(
            path: "{$this->sdk()->uri()->path()}/{$identifier}",
        );

        if (! is_null($this->with)) {
            $this->sdk()->uri()->addPath(
                path: "{$this->sdk()->uri()->path()}/" . implode("/", $this->with),
            );
        }

        if (! is_null($this->load)) {
            $this->sdk()->uri()->addPath(
                path: "{$this->sdk()->uri()->path()}/{$this->load}",
            );
        }

        return $this->sdk()->client()->get(
            uri: $this->sdk()->uri()->toString(),
            headers: $this->sdk()->strategy()->getHeader(
                prefix: $this->authHeader),
        );
    }

    /**
     * @param array $data
     * @return ResponseInterface
     * @throws \Psr\Http\Client\ClientExceptionInterface
     * @throws \Throwable
     */
    public function create(array $data): ResponseInterface
    {
        if (! is_null($this->with)) {
            $this->sdk()->uri()->addPath(
                path: "{$this->sdk()->uri()->path()}/" . implode("/", $this->with),
            );
        }

        return $this->sdk()->client()->post(
            uri: $this->sdk()->uri()->toString(),
            body: $data,
            headers: $this->sdk()->strategy()->getHeader(
                prefix: $this->authHeader,
            ),
        );
    }

    /**
     * @param $identifier
     * @param array $data
     * @param string $method
     * @return ResponseInterface
     */
    public function update($identifier, array $data, string $method = 'patch'): ResponseInterface
    {
        $this->sdk()->uri()->addPath(
            path: "{$this->sdk()->uri()->path()}/{$identifier}",
        );

        if (! is_null($this->with)) {
            $this->sdk()->uri()->addPath(
                path: "{$this->sdk()->uri()->path()}/" . implode("/", $this->with),
            );
        }

        return $this->sdk()->client()->{$method}(
            uri: $this->sdk()->uri()->toString(),
            data: $data,
            headers: $this->sdk()->strategy()->getHeader(
                prefix: $this->authHeader,
            ),
        );
    }

    /**
     * @param string|int $identifier
     * @return ResponseInterface
     * @throws \Psr\Http\Client\ClientExceptionInterface
     * @throws \Throwable
     */
    public function delete(string|int $identifier): ResponseInterface
    {
        $this->sdk()->uri()->addPath(
            path: "{$this->sdk()->uri()->path()}/{$identifier}"
        );

        if (! is_null($this->with)) {
            $this->sdk()->uri()->addPath(
                path: "{$this->sdk()->uri()->path()}/" . implode("/", $this->with)
            );
        }

        return $this->sdk()->client()->delete(
            uri: $this->sdk()->uri()->toString(),
            headers: $this->sdk()->strategy()->getHeader(
                prefix: $this->authHeader,
            )
        );
    }

    /**
     * @param string $key
     * @param mixed $value
     * @return $this
     */
    public function where(string $key, $value): self
    {
        $this->sdk()->uri()->addQueryParam(
            key: $key,
            value: $value
        );

        return $this;
    }
}
