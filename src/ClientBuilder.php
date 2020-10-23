<?php declare(strict_types=1);

namespace JustSteveKing\PhpSdk;

use JustSteveKing\HttpAuth\Strategies\Interfaces\StrategyInterface;
use JustSteveKing\HttpAuth\Strategies\NullStrategy;
use JustSteveKing\HttpSlim\HttpClient;
use JustSteveKing\UriBuilder\Uri;
use Psr\Container\ContainerInterface;

/**
 * Class ClientBuilder
 */
class ClientBuilder
{
    /**
     * @var Uri|null
     */
    protected ?Uri $uri;

    /**
     * @var HttpClient|null
     */
    protected ?HttpClient $transport;

    /**
     * @var StrategyInterface|null
     */
    protected ?StrategyInterface $strategy;

    /**
     * @var ContainerInterface|null
     */
    protected ?ContainerInterface $factory;

    /**
     * ClientBuilder constructor.
     *
     * @param Uri|null                $uri
     * @param HttpClient|null         $transport
     * @param StrategyInterface|null  $strategy
     * @param ContainerInterface|null $factory
     */
    public function __construct(
        Uri $uri = null,
        HttpClient $transport = null,
        StrategyInterface $strategy = null,
        ContainerInterface $factory = null
    ) {
        $this->setUri($uri);
        $this->setTransport($transport);
        $this->setStrategy($strategy);
        $this->setFactory($factory);
    }

    /**
     * @param Uri|null $uri
     */
    public function setUri(?Uri $uri): void
    {
        $this->uri = $uri;
    }

    /**
     * @return Uri
     */
    public function uri(): Uri
    {
        return $this->uri;
    }

    /**
     * @param HttpClient|null $transport
     */
    public function setTransport(?HttpClient $transport): void
    {
        $this->transport = $transport;
    }

    /**
     * @return HttpClient
     */
    public function transport(): HttpClient
    {
        return $this->transport;
    }

    /**
     * @param StrategyInterface|null $strategy
     */
    public function setStrategy(?StrategyInterface $strategy): void
    {
        if (is_null($strategy)) {
            $this->strategy = new NullStrategy();

            return;
        }
        $this->strategy = $strategy;
    }

    /**
     * @return StrategyInterface
     */
    public function strategy(): StrategyInterface
    {
        return $this->strategy;
    }

    /**
     * @param ContainerInterface|null $factory
     */
    public function setFactory(?ContainerInterface $factory): void
    {
        $this->factory = $factory;
    }

    /**
     * @return ContainerInterface
     */
    public function factory(): ContainerInterface
    {
        return $this->factory;
    }
}
