<?php declare(strict_types=1);

namespace FlexPHP\Bundle\LocationBundle\Domain\Currency\Response;

use FlexPHP\Messages\ResponseInterface;

final class IndexCurrencyResponse implements ResponseInterface
{
    public $currencies;

    public function __construct(array $currencies)
    {
        $this->currencies = $currencies;
    }
}
