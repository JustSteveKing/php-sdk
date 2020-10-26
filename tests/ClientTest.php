<?php declare(strict_types=1);

namespace JustSteveKing\PhpSdk\Tests;

use DI\Container;
use DI\ContainerBuilder;
use JustSteveKing\HttpAuth\Strategies\BasicStrategy;
use JustSteveKing\HttpAuth\Strategies\Interfaces\StrategyInterface;
use JustSteveKing\HttpSlim\HttpClient;
use JustSteveKing\PhpSdk\Client;
use JustSteveKing\PhpSdk\ClientBuilder;
use JustSteveKing\PhpSdk\Resources\AbstractResource;
use JustSteveKing\UriBuilder\Uri;
use PHPUnit\Framework\TestCase;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface;
use RuntimeException;
use Symfony\Component\HttpClient\Psr18Client;

class ClientTest extends TestCase
{
    /**
     * @test
     */
    public function it_will_create_a_client()
    {
        $builder = new ClientBuilder(
            Uri::fromString('https://www.test.com'),
            $this->http(),
            $this->strategy(),
            $this->container()
        );

        $client = new Client($builder);

        $this->assertInstanceOf(
            Client::class,
            $client
        );
    }

    /**
     * @test
     */
    public function it_will_throw_an_exception_if_uri_is_not_a_uri()
    {
        $this->expectException(RuntimeException::class);
        $builder = new ClientBuilder(
            Uri::fromString('definitely.not.a.uri'),
            $this->http(),
            $this->strategy(),
            $this->container()
        );

        $client = new Client($builder);
    }

    /**
     * @test
     */
    public function it_will_allow_you_to_access_and_use_the_uri()
    {
        $builder = new ClientBuilder(
            Uri::fromString('https://www.test.com'),
            $this->http(),
            $this->strategy(),
            $this->container()
        );
        $client = new Client($builder);

        $this->assertInstanceOf(
            Uri::class,
            $client->uri()
        );

        $this->assertEquals(
            'https://www.test.com',
            $client->uri()->toString()
        );

        $client->uri()->addPath('path');

        $this->assertEquals(
            'https://www.test.com/path',
            $client->uri()->toString()
        );
    }

    /**
     * @test
     */
    public function it_will_allow_access_to_the_http_client()
    {
        $builder = new ClientBuilder(
            Uri::fromString('https://www.test.com'),
            $this->http(),
            $this->strategy(),
            $this->container()
        );
        $client = new Client($builder);

        $client->addResource('name', new class extends AbstractResource {
            protected string $path = 'name';
        });

        $this->assertInstanceOf(
            HttpClient::class,
            $client->name->http()
        );
    }

    /**
     * @test
     */
    public function it_will_let_me_access_the_container()
    {
        $builder = new ClientBuilder(
            Uri::fromString('https://www.test.com'),
            $this->http(),
            $this->strategy(),
            $this->container()
        );

        $client = new Client($builder);

        $this->assertInstanceOf(
            ContainerInterface::class,
            $client->factory()
        );

        $this->assertFalse(
            $client->factory()->has('not-in-container')
        );
    }

    /**
     * @test
     */
    public function it_will_let_me_register_a_new_resource()
    {
        $builder = new ClientBuilder(
            Uri::fromString('https://www.test.com'),
            $this->http(),
            $this->strategy(),
            $this->container()
        );

        $client = new Client($builder);

        $client->addResource('name', new class extends AbstractResource {
        });

        $this->assertTrue(
            $client->factory()->has('name')
        );
    }

    /**
     * @test
     */
    public function it_will_let_me_forward_a_call_to_a_resource()
    {
        $builder = new ClientBuilder(
            Uri::fromString('https://www.test.com'),
            $this->http(),
            $this->strategy(),
            $this->container()
        );

        $client = new Client($builder);

        $client->addResource('name', new class extends AbstractResource {
            protected string $path = 'name';
        });

        $this->assertEquals(
            'https://www.test.com/name',
            $client->name->uri()->toString()
        );
    }

    /**
     * @test
     */
    public function it_will_throw_a_runtime_exception_if_resource_is_not_registered()
    {
        $this->expectException(RuntimeException::class);
        $builder = new ClientBuilder(
            Uri::fromString('https://jsonplaceholder.typicode.com'),
            $this->http(),
            $this->strategy(),
            $this->container()
        );

        $client = new Client($builder);
        $client->fake->get();
    }

    /**
     * @test
     */
    public function it_will_perform_requests_on_the_resource()
    {
        $builder = new ClientBuilder(
            Uri::fromString('https://jsonplaceholder.typicode.com'),
            $this->http(),
            $this->strategy(),
            $this->container()
        );

        $client = new Client($builder);

        $client->addResource('todos', new class extends AbstractResource {
            protected string $path = 'todos';
        });

        $this->assertInstanceOf(
            ResponseInterface::class,
            $client->todos->get()
        );

        $this->assertInstanceOf(
            ResponseInterface::class,
            $client->todos->find(1)
        );

        $this->assertInstanceOf(
            ResponseInterface::class,
            $client->todos->create([])
        );

        $this->assertInstanceOf(
            ResponseInterface::class,
            $client->todos->update(1, [])
        );

        $this->assertInstanceOf(
            ResponseInterface::class,
            $client->todos->delete(1)
        );

        $this->assertInstanceOf(
            ResponseInterface::class,
            $client->todos->where('name', 'test')->get()
        );

        $this->assertEquals(
            ['name' => 'test'],
            $client->todos->where('name', 'test')->uri()->query()->all()
        );
    }

    /**
     * @test
     */
    public function it_will_append_extra_parts_to_the_uri_path()
    {
        $builder = new ClientBuilder(
            Uri::fromString('https://jsonplaceholder.typicode.com'),
            $this->http(),
            $this->strategy(),
            $this->container()
        );

        $client = new Client($builder);

        $client->addResource('todos', new class extends AbstractResource {
            protected string $path = 'todos';
        });

        $this->assertEquals(
            "https://jsonplaceholder.typicode.com/todos",
            $client->todos->uri()->toString()
        );

        $client->todos->with(['test']);

        $this->assertEquals(
            ['test'],
            $client->todos->getWith()
        );

        $client->todos->load(123);

        $this->assertEquals(
            "123",
            $client->todos->getLoad()
        );

        $client->todos->with(['user']);
        $this->assertInstanceOf(
            ResponseInterface::class,
            $client->todos->with(['user'])->load(123)->find(1)
        );

        $this->assertInstanceOf(
            ResponseInterface::class,
            $client->todos->with(['user'])->load(123)->create([])
        );

        $this->assertInstanceOf(
            ResponseInterface::class,
            $client->todos->with(['user'])->load(123)->update(123, [])
        );

        $this->assertInstanceOf(
            ResponseInterface::class,
            $client->todos->with(['user'])->load(123)->delete(123)
        );
    }

    /**
     * @test
     */
    public function it_will_allow_me_to_pass_through_an_auth_strategy()
    {
        $builder = new ClientBuilder(
            Uri::fromString('https://jsonplaceholder.typicode.com'),
            $this->http(),
            $this->strategy(),
            $this->container()
        );

        $client = new Client($builder);

        $strategy = new BasicStrategy(
            base64_encode("username:password")
        );

        $this->assertInstanceOf(
            StrategyInterface::class,
            $client->strategy()
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
