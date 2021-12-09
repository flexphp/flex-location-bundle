<?php declare(strict_types=1);

namespace FlexPHP\Bundle\LocationBundle\Domain\City\Response;

use FlexPHP\Messages\ResponseInterface;

final class IndexCityResponse implements ResponseInterface
{
    public $cities;

    public function __construct(array $cities)
    {
        $this->cities = $cities;
    }
}
