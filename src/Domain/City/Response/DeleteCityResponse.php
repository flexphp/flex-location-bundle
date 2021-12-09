<?php declare(strict_types=1);

namespace FlexPHP\Bundle\LocationBundle\Domain\City\Response;

use FlexPHP\Bundle\LocationBundle\Domain\City\City;
use FlexPHP\Messages\ResponseInterface;

final class DeleteCityResponse implements ResponseInterface
{
    public $city;

    public function __construct(City $city)
    {
        $this->city = $city;
    }
}
