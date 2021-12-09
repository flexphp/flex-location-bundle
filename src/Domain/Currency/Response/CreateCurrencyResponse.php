<?php declare(strict_types=1);

namespace FlexPHP\Bundle\LocationBundle\Domain\Currency\Response;

use FlexPHP\Bundle\LocationBundle\Domain\Currency\Currency;
use FlexPHP\Messages\ResponseInterface;

final class CreateCurrencyResponse implements ResponseInterface
{
    public $currency;

    public function __construct(Currency $currency)
    {
        $this->currency = $currency;
    }
}
