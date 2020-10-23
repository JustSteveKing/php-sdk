<?php declare(strict_types=1);

namespace JustSteveKing\PhpSdk;

use JustSteveKing\UriBuilder\Uri;
use Psr\Container\ContainerInterface;
use JustSteveKing\HttpSlim\HttpClient;
use JustSteveKing\HttpAuth\Strategies\NullStrategy;
use JustSteveKing\HttpAuth\Strategies\Interfaces\StrategyInterface;

class ClientBuilder
{
    protected ?Uri $uri;

    protected ?HttpClient $transport;

    protected ?StrategyInterface $strategy;

    protected ?ContainerInterface $factory;

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

    public function setUri(?Uri $uri): void
    {
        $this->uri = $uri;
    }

    public function uri(): Uri
    {
        return $this->uri;
    }

    public function setTransport(?HttpClient $transport): void
    {
        $this->transport = $transport;
    }

    public function transport(): HttpClient
    {
        return $this->transport;
    }

    public function setStrategy(?StrategyInterface $strategy): void
    {
        if (is_null($strategy)) {
            $this->strategy = new NullStrategy();
            return;
        }
        $this->strategy = $strategy;
    }

    public function strategy(): StrategyInterface
    {
        return $this->strategy;
    }

    public function setFactory(?ContainerInterface $factory): void
    {
        $this->factory = $factory;
    }

    public function factory(): ContainerInterface
    {
        return $this->factory;
    }
}
