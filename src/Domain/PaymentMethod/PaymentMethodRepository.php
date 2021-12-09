<?php declare(strict_types=1);
/*
 * This file is part of FlexPHP.
 *
 * (c) Freddie Gar <freddie.gar@outlook.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace FlexPHP\Bundle\LocationBundle\Domain\PaymentMethod;

use FlexPHP\Bundle\LocationBundle\Domain\PaymentMethod\Request\CreatePaymentMethodRequest;
use FlexPHP\Bundle\LocationBundle\Domain\PaymentMethod\Request\DeletePaymentMethodRequest;
use FlexPHP\Bundle\LocationBundle\Domain\PaymentMethod\Request\IndexPaymentMethodRequest;
use FlexPHP\Bundle\LocationBundle\Domain\PaymentMethod\Request\ReadPaymentMethodRequest;
use FlexPHP\Bundle\LocationBundle\Domain\PaymentMethod\Request\UpdatePaymentMethodRequest;

final class PaymentMethodRepository
{
    private PaymentMethodGateway $gateway;

    public function __construct(PaymentMethodGateway $gateway)
    {
        $this->gateway = $gateway;
    }

    /**
     * @return array<PaymentMethod>
     */
    public function findBy(IndexPaymentMethodRequest $request): array
    {
        return \array_map(function (array $paymentMethod) {
            return (new PaymentMethodFactory())->make($paymentMethod);
        }, $this->gateway->search((array)$request, [], $request->_page, $request->_limit, $request->_offset));
    }

    public function add(CreatePaymentMethodRequest $request): PaymentMethod
    {
        $paymentMethod = (new PaymentMethodFactory())->make($request);

        $paymentMethod->setId($this->gateway->push($paymentMethod));

        return $paymentMethod;
    }

    public function getById(ReadPaymentMethodRequest $request): PaymentMethod
    {
        $factory = new PaymentMethodFactory();
        $data = $this->gateway->get($factory->make($request));

        return $factory->make($data);
    }

    public function change(UpdatePaymentMethodRequest $request): PaymentMethod
    {
        $paymentMethod = (new PaymentMethodFactory())->make($request);

        $this->gateway->shift($paymentMethod);

        return $paymentMethod;
    }

    public function remove(DeletePaymentMethodRequest $request): PaymentMethod
    {
        $factory = new PaymentMethodFactory();
        $data = $this->gateway->get($factory->make($request));

        $paymentMethod = $factory->make($data);

        $this->gateway->pop($paymentMethod);

        return $paymentMethod;
    }
}
