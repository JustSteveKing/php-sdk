<?php

declare(strict_types=1);

namespace JustSteveKing\Sdk\Tests\Stubs\Resources;

use Exception;
use JustSteveKing\Sdk\Concerns\Resources\CanAccessClient;
use JustSteveKing\Sdk\Concerns\Resources\CanCreateDataObjects;
use JustSteveKing\Sdk\Concerns\Resources\CanCreateRequests;
use JustSteveKing\Sdk\Tests\Stubs\DataObjects\TestDataObject;
use JustSteveKing\Tools\Http\Enums\Method;
use Ramsey\Collection\Collection;
use Throwable;

final class TestResource
{
    use CanAccessClient;
    use CanCreateDataObjects;
    use CanCreateRequests;

    public function list()
    {
        $request = $this->request(
            method: Method::GET,
            uri: '/test',
        );

        try {
            $response = $this->client->send(
                request: $request,
            );
        } catch (Throwable $exception) {
            throw new Exception(
                message: 'Failed to list test.',
                code: $exception->getCode(),
                previous: $exception,
            );
        }

        return (new Collection(
            collectionType: TestDataObject::class,
            data: array_map(
                callback: static fn(array $data): TestDataObject => TestDataObject::make(
                    data: $data,
                ),
                array: (array) json_decode(
                    json: $response->getBody()->getContents(),
                    associative: true,
                    flags: JSON_THROW_ON_ERROR,
                ),
            ),
        ));
    }
}
