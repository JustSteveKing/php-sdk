<?php

declare(strict_types=1);

namespace JustSteveKing\Sdk\Tests\Feature;

use JustSteveKing\Sdk\Client;
use JustSteveKing\Sdk\Tests\PackageTestCase;
use JustSteveKing\Sdk\Tests\Stubs\DataObjects\TestDataObject;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use Ramsey\Collection\Collection;

#[CoversClass(Client::class)]
final class ClientTest extends PackageTestCase
{
    #[Test]
    public function it_can_get_a_collection_of_tests(): void
    {
        $client = $this->createMockClient(
            fixture: 'tests/list',
        );


        $response = $client->tests()->list();

        $this->assertInstanceOf(
            expected: Collection::class,
            actual: $response,
        );

        foreach ($response->toArray() as $engine) {
            $this->assertInstanceOf(
                expected: TestDataObject::class,
                actual: $engine,
            );
        }
    }
}
