<?php declare(strict_types=1);
/*
 * This file is part of FlexPHP.
 *
 * (c) Freddie Gar <freddie.gar@outlook.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace FlexPHP\Bundle\LocationBundle\Domain\State;

use FlexPHP\Bundle\LocationBundle\Domain\Country\CountryFactory;
use FlexPHP\Bundle\HelperBundle\Domain\Helper\FactoryExtendedTrait;
use FlexPHP\Bundle\UserBundle\Domain\User\UserFactory;

final class StateFactory
{
    use FactoryExtendedTrait;

    public function make($data): State
    {
        $state = new State();

        if (\is_object($data)) {
            $data = (array)$data;
        }

        if (isset($data['id'])) {
            $state->setId((int)$data['id']);
        }

        if (isset($data['countryId'])) {
            $state->setCountryId((int)$data['countryId']);
        }

        if (isset($data['name'])) {
            $state->setName((string)$data['name']);
        }

        if (isset($data['code'])) {
            $state->setCode((string)$data['code']);
        }

        if (isset($data['createdAt'])) {
            $state->setCreatedAt(\is_string($data['createdAt']) ? new \DateTime($data['createdAt']) : $data['createdAt']);
        }

        if (isset($data['updatedAt'])) {
            $state->setUpdatedAt(\is_string($data['updatedAt']) ? new \DateTime($data['updatedAt']) : $data['updatedAt']);
        }

        if (isset($data['createdBy'])) {
            $state->setCreatedBy((int)$data['createdBy']);
        }

        if (isset($data['updatedBy'])) {
            $state->setUpdatedBy((int)$data['updatedBy']);
        }

        if (isset($data['countryId.id'])) {
            $state->setCountryIdInstance((new CountryFactory())->make($this->getFkEntity('countryId.', $data)));
        }

        if (isset($data['createdBy.id'])) {
            $state->setCreatedByInstance((new UserFactory())->make($this->getFkEntity('createdBy.', $data)));
        }

        if (isset($data['updatedBy.id'])) {
            $state->setUpdatedByInstance((new UserFactory())->make($this->getFkEntity('updatedBy.', $data)));
        }

        return $state;
    }
}
