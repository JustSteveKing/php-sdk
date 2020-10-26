<?php declare(strict_types=1);

namespace Demo\Resources;

use JustSteveKing\PhpSdk\Resources\AbstractResource;

class Server extends AbstractResource
{
    protected string $path = 'api/v1/servers';

    public function databases($identifier = null): self
    {
        $this->with = ['databases'];

        if (! is_null($identifier)) {
            $this->load = $identifier;
        }

        return $this;
    }
}
