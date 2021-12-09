<?php declare(strict_types=1);

namespace FlexPHP\Bundle\LocationBundle\Domain\PaymentMethod\Response;

use FlexPHP\Messages\ResponseInterface;

final class IndexPaymentMethodResponse implements ResponseInterface
{
    public $paymentMethods;

    public function __construct(array $paymentMethods)
    {
        $this->paymentMethods = $paymentMethods;
    }
}
