<?php declare(strict_types=1);
/*
 * This file is part of FlexPHP.
 *
 * (c) Freddie Gar <freddie.gar@outlook.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace FlexPHP\Bundle\LocationBundle\Domain\City;

use FlexPHP\Bundle\HelperBundle\Domain\Helper\FactoryExtendedTrait;
use FlexPHP\Bundle\LocationBundle\Domain\State\StateFactory;
use FlexPHP\Bundle\UserBundle\Domain\User\UserFactory;

final class CityFactory
{
    use FactoryExtendedTrait;

    public function make($data): City
    {
        $city = new City();

        if (\is_object($data)) {
            $data = (array)$data;
        }

        if (isset($data['id'])) {
            $city->setId((int)$data['id']);
        }

        if (isset($data['stateId'])) {
            $city->setStateId((int)$data['stateId']);
        }

        if (isset($data['name'])) {
            $city->setName((string)$data['name']);
        }

        if (isset($data['code'])) {
            $city->setCode((string)$data['code']);
        }

        if (isset($data['createdAt'])) {
            $city->setCreatedAt(\is_string($data['createdAt']) ? new \DateTime($data['createdAt']) : $data['createdAt']);
        }

        if (isset($data['updatedAt'])) {
            $city->setUpdatedAt(\is_string($data['updatedAt']) ? new \DateTime($data['updatedAt']) : $data['updatedAt']);
        }

        if (isset($data['createdBy'])) {
            $city->setCreatedBy((int)$data['createdBy']);
        }

        if (isset($data['updatedBy'])) {
            $city->setUpdatedBy((int)$data['updatedBy']);
        }

        if (isset($data['stateId.id'])) {
            $city->setStateIdInstance((new StateFactory())->make($this->getFkEntity('stateId.', $data)));
        }

        if (isset($data['createdBy.id'])) {
            $city->setCreatedByInstance((new UserFactory())->make($this->getFkEntity('createdBy.', $data)));
        }

        if (isset($data['updatedBy.id'])) {
            $city->setUpdatedByInstance((new UserFactory())->make($this->getFkEntity('updatedBy.', $data)));
        }

        return $city;
    }
}
