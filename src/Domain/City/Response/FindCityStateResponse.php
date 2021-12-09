<?php declare(strict_types=1);

namespace FlexPHP\Bundle\LocationBundle\Domain\City\Response;

use FlexPHP\Messages\ResponseInterface;

final class FindCityStateResponse implements ResponseInterface
{
    public $states;

    public function __construct(array $states)
    {
        $this->states = $states;
    }
}
