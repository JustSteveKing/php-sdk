<?php declare(strict_types=1);

namespace JustSteveKing\PhpSdk\Tests;

use DI\ContainerBuilder;
use PHPUnit\Framework\TestCase;
use JustSteveKing\UriBuilder\Uri;
use Psr\Container\ContainerInterface;
use JustSteveKing\HttpSlim\HttpClient;
use JustSteveKing\PhpSdk\ClientBuilder;
use Symfony\Component\HttpClient\Psr18Client;
use JustSteveKing\HttpAuth\Strategies\NullStrategy;
use JustSteveKing\HttpAuth\Strategies\BasicStrategy;
use JustSteveKing\HttpAuth\Strategies\Interfaces\StrategyInterface;

class ClientBuilderTest extends TestCase
{
    /**
     * @test
     */
    public function it_will_build_will_no_args_to_constructor()
    {
        $this->assertInstanceOf(
            ClientBuilder::class,
            new ClientBuilder()
        );
    }

    /**
     * @test
     */
    public function it_will_add_a_transport_layer()
    {
            $builder = new ClientBuilder(
                Uri::fromString('https://www.test.com'),
                $this->http(),
                $this->strategy(),
                $this->container()
            );

            $this->assertInstanceOf(
                HttpClient::class,
                $builder->transport()
            );

            $builder = new ClientBuilder();
            $builder->setTransport(
                $this->http()
            );

            $this->assertInstanceOf(
                HttpClient::class,
                $builder->transport()
            );
    }

    /**
     * @test
     */
    public function it_will_add_a_uri()
    {
            $builder = new ClientBuilder(
                Uri::fromString('https://www.test.com'),
                $this->http(),
                $this->strategy(),
                $this->container()
            );

            $this->assertInstanceOf(
                Uri::class,
                $builder->uri()
            );

            $builder = new ClientBuilder();
            $builder->setUri(
                Uri::fromString('https://www.test.com'),
            );

            $this->assertInstanceOf(
                Uri::class,
                $builder->uri()
            );
    }

    /**
     * @test
     */
    public function it_will_add_a_strategy()
    {
            $builder = new ClientBuilder(
                Uri::fromString('https://www.test.com'),
                $this->http(),
                $this->strategy(),
                $this->container()
            );

            $this->assertInstanceOf(
                StrategyInterface::class,
                $builder->strategy()
            );

            $builder = new ClientBuilder();
            $builder->setStrategy(
                $this->strategy()
            );

            $this->assertInstanceOf(
                StrategyInterface::class,
                $builder->strategy()
            );
    }

    /**
     * @test
     */
    public function it_will_set_null_strategy_if_none_is_passed()
    {
        $builder = new ClientBuilder(
            Uri::fromString('https://www.test.com'),
            $this->http(),
            $this->strategy(),
            $this->container()
        );

        $this->assertInstanceOf(
            BasicStrategy::class,
            $builder->strategy()
        );

        $builder = new ClientBuilder();

        $this->assertInstanceOf(
            NullStrategy::class,
            $builder->strategy()
        );
    }

    /**
     * @test
     */
    public function it_will_add_a_factory()
    {
            $builder = new ClientBuilder(
                Uri::fromString('https://www.test.com'),
                $this->http(),
                $this->strategy(),
                $this->container()
            );

            $this->assertInstanceOf(
                ContainerInterface::class,
                $builder->factory()
            );

            $builder = new ClientBuilder();
            $builder->setFactory(
                $this->container()
            );

            $this->assertInstanceOf(
                ContainerInterface::class,
                $builder->factory()
            );
    }

    private function http(): HttpClient
    {
        return HttpClient::build(
            new Psr18Client(), // http client (psr-18)
            new Psr18Client(), // request factory (psr-17)
            new Psr18Client() // stream factory (psr-17)
        );
    }

    private function strategy(): StrategyInterface
    {
        return new BasicStrategy(
            base64_encode("username:password")
        );
    }

    private function container(): ContainerInterface
    {
        $builder = new ContainerBuilder();
        $builder->useAutowiring(true);
        $builder->useAnnotations(false);
        return $builder->build();
    }
}
