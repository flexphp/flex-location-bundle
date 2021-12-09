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

use FlexPHP\Bundle\HelperBundle\Domain\Helper\FactoryExtendedTrait;
use FlexPHP\Bundle\UserBundle\Domain\User\UserFactory;

final class PaymentMethodFactory
{
    use FactoryExtendedTrait;

    public function make($data): PaymentMethod
    {
        $paymentMethod = new PaymentMethod();

        if (\is_object($data)) {
            $data = (array)$data;
        }

        if (isset($data['id'])) {
            $paymentMethod->setId((string)$data['id']);
        }

        if (isset($data['name'])) {
            $paymentMethod->setName((string)$data['name']);
        }

        if (isset($data['isActive'])) {
            $paymentMethod->setIsActive((bool)$data['isActive']);
        }

        if (isset($data['createdAt'])) {
            $paymentMethod->setCreatedAt(\is_string($data['createdAt']) ? new \DateTime($data['createdAt']) : $data['createdAt']);
        }

        if (isset($data['updatedAt'])) {
            $paymentMethod->setUpdatedAt(\is_string($data['updatedAt']) ? new \DateTime($data['updatedAt']) : $data['updatedAt']);
        }

        if (isset($data['createdBy'])) {
            $paymentMethod->setCreatedBy((int)$data['createdBy']);
        }

        if (isset($data['updatedBy'])) {
            $paymentMethod->setUpdatedBy((int)$data['updatedBy']);
        }

        if (isset($data['createdBy.id'])) {
            $paymentMethod->setCreatedByInstance((new UserFactory())->make($this->getFkEntity('createdBy.', $data)));
        }

        if (isset($data['updatedBy.id'])) {
            $paymentMethod->setUpdatedByInstance((new UserFactory())->make($this->getFkEntity('updatedBy.', $data)));
        }

        return $paymentMethod;
    }
}
