<?php declare(strict_types=1);

namespace FlexPHP\Bundle\LocationBundle\Domain\State\Response;

use FlexPHP\Bundle\LocationBundle\Domain\State\State;
use FlexPHP\Messages\ResponseInterface;

final class ReadStateResponse implements ResponseInterface
{
    public $state;

    public function __construct(State $state)
    {
        $this->state = $state;
    }
}
