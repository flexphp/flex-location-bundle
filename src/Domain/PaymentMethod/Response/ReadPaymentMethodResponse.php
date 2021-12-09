<?php declare(strict_types=1);

namespace FlexPHP\Bundle\LocationBundle\Domain\PaymentMethod\Response;

use FlexPHP\Bundle\LocationBundle\Domain\PaymentMethod\PaymentMethod;
use FlexPHP\Messages\ResponseInterface;

final class ReadPaymentMethodResponse implements ResponseInterface
{
    public $paymentMethod;

    public function __construct(PaymentMethod $paymentMethod)
    {
        $this->paymentMethod = $paymentMethod;
    }
}
