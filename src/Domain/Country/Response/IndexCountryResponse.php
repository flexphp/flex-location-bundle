<?php declare(strict_types=1);

namespace FlexPHP\Bundle\LocationBundle\Domain\Country\Response;

use FlexPHP\Messages\ResponseInterface;

final class IndexCountryResponse implements ResponseInterface
{
    public $countries;

    public function __construct(array $countries)
    {
        $this->countries = $countries;
    }
}
