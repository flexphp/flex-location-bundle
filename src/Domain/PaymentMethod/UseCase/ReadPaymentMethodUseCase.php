<?php declare(strict_types=1);
/*
 * This file is part of FlexPHP.
 *
 * (c) Freddie Gar <freddie.gar@outlook.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace FlexPHP\Bundle\LocationBundle\Domain\PaymentMethod\UseCase;

use FlexPHP\Bundle\LocationBundle\Domain\PaymentMethod\PaymentMethodRepository;
use FlexPHP\Bundle\LocationBundle\Domain\PaymentMethod\Request\ReadPaymentMethodRequest;
use FlexPHP\Bundle\LocationBundle\Domain\PaymentMethod\Response\ReadPaymentMethodResponse;

final class ReadPaymentMethodUseCase
{
    private PaymentMethodRepository $paymentMethodRepository;

    public function __construct(PaymentMethodRepository $paymentMethodRepository)
    {
        $this->paymentMethodRepository = $paymentMethodRepository;
    }

    public function execute(ReadPaymentMethodRequest $request): ReadPaymentMethodResponse
    {
        $paymentMethod = $this->paymentMethodRepository->getById($request);

        return new ReadPaymentMethodResponse($paymentMethod);
    }
}
