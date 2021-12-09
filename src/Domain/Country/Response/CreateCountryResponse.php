<?php declare(strict_types=1);

namespace FlexPHP\Bundle\LocationBundle\Domain\Country\Response;

use FlexPHP\Bundle\LocationBundle\Domain\Country\Country;
use FlexPHP\Messages\ResponseInterface;

final class CreateCountryResponse implements ResponseInterface
{
    public $country;

    public function __construct(Country $country)
    {
        $this->country = $country;
    }
}
