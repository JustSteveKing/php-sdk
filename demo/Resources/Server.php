<?php declare(strict_types=1);

namespace Demo\Resources;

use JustSteveKing\PhpSdk\Contracts\ResourceContract;
use JustSteveKing\PhpSdk\Resources\AbstractResource;

class Server extends AbstractResource implements ResourceContract
{
    /**
     * @var string
     */
    protected string $path = 'api/v1/servers';

    /**
     * @param null $identifier
     * @return $this
     */
    public function databases($identifier = null): self
    {
        $this->with(
            with: ['databases'],
        );

        if (!is_null($identifier)) {
            $this->load(
                identifier: $identifier,
            );
        }

        return $this;
    }

    /**
     * @return string
     */
    public static function name(): string
    {
        return 'servers';
    }
}
