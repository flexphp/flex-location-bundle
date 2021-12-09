<?php declare(strict_types=1);

namespace FlexPHP\Bundle\LocationBundle\Domain\State\Response;

use FlexPHP\Messages\ResponseInterface;

final class IndexStateResponse implements ResponseInterface
{
    public $states;

    public function __construct(array $states)
    {
        $this->states = $states;
    }
}
