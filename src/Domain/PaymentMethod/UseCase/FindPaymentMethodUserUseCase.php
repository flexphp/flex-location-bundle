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
use FlexPHP\Bundle\LocationBundle\Domain\PaymentMethod\Request\FindPaymentMethodUserRequest;
use FlexPHP\Bundle\LocationBundle\Domain\PaymentMethod\Response\FindPaymentMethodUserResponse;

final class FindPaymentMethodUserUseCase
{
    private PaymentMethodRepository $paymentMethodRepository;

    public function __construct(PaymentMethodRepository $paymentMethodRepository)
    {
        $this->paymentMethodRepository = $paymentMethodRepository;
    }

    public function execute(FindPaymentMethodUserRequest $request): FindPaymentMethodUserResponse
    {
        $users = $this->paymentMethodRepository->findUsersBy($request);

        return new FindPaymentMethodUserResponse($users);
    }
}
