<?php declare(strict_types=1);
/*
 * This file is part of FlexPHP.
 *
 * (c) Freddie Gar <freddie.gar@outlook.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace FlexPHP\Bundle\LocationBundle\Domain\DocumentType;

use FlexPHP\Bundle\HelperBundle\Domain\Helper\FactoryExtendedTrait;
use FlexPHP\Bundle\UserBundle\Domain\User\UserFactory;

final class DocumentTypeFactory
{
    use FactoryExtendedTrait;

    public function make($data): DocumentType
    {
        $documentType = new DocumentType();

        if (\is_object($data)) {
            $data = (array)$data;
        }

        if (isset($data['id'])) {
            $documentType->setId((string)$data['id']);
        }

        if (isset($data['name'])) {
            $documentType->setName((string)$data['name']);
        }

        if (isset($data['isActive'])) {
            $documentType->setIsActive((bool)$data['isActive']);
        }

        if (isset($data['createdAt'])) {
            $documentType->setCreatedAt(\is_string($data['createdAt']) ? new \DateTime($data['createdAt']) : $data['createdAt']);
        }

        if (isset($data['updatedAt'])) {
            $documentType->setUpdatedAt(\is_string($data['updatedAt']) ? new \DateTime($data['updatedAt']) : $data['updatedAt']);
        }

        if (isset($data['createdBy'])) {
            $documentType->setCreatedBy((int)$data['createdBy']);
        }

        if (isset($data['updatedBy'])) {
            $documentType->setUpdatedBy((int)$data['updatedBy']);
        }

        if (isset($data['createdBy.id'])) {
            $documentType->setCreatedByInstance((new UserFactory())->make($this->getFkEntity('createdBy.', $data)));
        }

        if (isset($data['updatedBy.id'])) {
            $documentType->setUpdatedByInstance((new UserFactory())->make($this->getFkEntity('updatedBy.', $data)));
        }

        return $documentType;
    }
}
