<?php declare(strict_types=1);
/*
 * This file is part of FlexPHP.
 *
 * (c) Freddie Gar <freddie.gar@outlook.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace FlexPHP\Bundle\LocationBundle\Domain\DocumentType\Gateway;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Types\Types as DB;
use FlexPHP\Bundle\LocationBundle\Domain\DocumentType\DocumentType;
use FlexPHP\Bundle\LocationBundle\Domain\DocumentType\DocumentTypeGateway;
use FlexPHP\Bundle\HelperBundle\Domain\Helper\DbalCriteriaHelper;

class MySQLDocumentTypeGateway implements DocumentTypeGateway
{
    private $conn;

    private $operator = [
        //
    ];

    public function __construct(Connection $conn)
    {
        $this->conn = $conn;
    }

    public function search(array $wheres, array $orders, int $page, int $limit, int $offset): array
    {
        $query = $this->conn->createQueryBuilder();

        $query->select([
            'documentType.Id as id',
            'documentType.Name as name',
            'documentType.IsActive as isActive',
        ]);
        $query->from('`DocumentTypes`', '`documentType`');

        $query->orderBy('documentType.UpdatedAt', 'DESC');

        $criteria = new DbalCriteriaHelper($query, $offset);

        foreach ($wheres as $column => $value) {
            $criteria->getCriteria('documentType', $column, $value, $this->operator[$column] ?? DbalCriteriaHelper::OP_EQUALS);
        }

        $query->setFirstResult($page ? ($page - 1) * $limit : 0);
        $query->setMaxResults($limit);

        return $query->execute()->fetchAll();
    }

    public function push(DocumentType $documentType): string
    {
        $query = $this->conn->createQueryBuilder();

        $query->insert('`DocumentTypes`');

        $query->setValue('Id', ':id');
        $query->setValue('Name', ':name');
        $query->setValue('IsActive', ':isActive');
        $query->setValue('CreatedAt', ':createdAt');
        $query->setValue('UpdatedAt', ':updatedAt');
        $query->setValue('CreatedBy', ':createdBy');

        $query->setParameter(':id', $documentType->id(), DB::STRING);
        $query->setParameter(':name', $documentType->name(), DB::STRING);
        $query->setParameter(':isActive', $documentType->isActive(), DB::BOOLEAN);
        $query->setParameter(':createdAt', $documentType->createdAt() ?? new \DateTime(date('Y-m-d H:i:s')), DB::DATETIME_MUTABLE);
        $query->setParameter(':updatedAt', $documentType->updatedAt() ?? new \DateTime(date('Y-m-d H:i:s')), DB::DATETIME_MUTABLE);
        $query->setParameter(':createdBy', $documentType->createdBy(), DB::INTEGER);

        $query->execute();

        return $documentType->id();
    }

    public function get(DocumentType $documentType): array
    {
        $query = $this->conn->createQueryBuilder();

        $query->select([
            'documentType.Id as id',
            'documentType.Name as name',
            'documentType.IsActive as isActive',
            'documentType.CreatedAt as createdAt',
            'documentType.UpdatedAt as updatedAt',
            'documentType.CreatedBy as createdBy',
            'documentType.UpdatedBy as updatedBy',
            'createdBy.id as `createdBy.id`',
            'createdBy.name as `createdBy.name`',
            'updatedBy.id as `updatedBy.id`',
            'updatedBy.name as `updatedBy.name`',
        ]);
        $query->from('`DocumentTypes`', '`documentType`');
        $query->leftJoin('`documentType`', '`Users`', '`createdBy`', 'documentType.CreatedBy = createdBy.id');
        $query->leftJoin('`documentType`', '`Users`', '`updatedBy`', 'documentType.UpdatedBy = updatedBy.id');
        $query->where('documentType.Id = :id');
        $query->setParameter(':id', $documentType->id(), DB::STRING);

        return $query->execute()->fetch() ?: [];
    }

    public function shift(DocumentType $documentType): void
    {
        $query = $this->conn->createQueryBuilder();

        $query->update('`DocumentTypes`');

        $query->set('Id', ':id');
        $query->set('Name', ':name');
        $query->set('IsActive', ':isActive');
        $query->set('UpdatedAt', ':updatedAt');
        $query->set('UpdatedBy', ':updatedBy');

        $query->setParameter(':id', $documentType->id(), DB::STRING);
        $query->setParameter(':name', $documentType->name(), DB::STRING);
        $query->setParameter(':isActive', $documentType->isActive(), DB::BOOLEAN);
        $query->setParameter(':updatedAt', $documentType->updatedAt() ?? new \DateTime(date('Y-m-d H:i:s')), DB::DATETIME_MUTABLE);
        $query->setParameter(':updatedBy', $documentType->updatedBy(), DB::INTEGER);

        $query->where('Id = :id');
        $query->setParameter(':id', $documentType->id(), DB::STRING);

        $query->execute();
    }

    public function pop(DocumentType $documentType): void
    {
        $query = $this->conn->createQueryBuilder();

        $query->delete('`DocumentTypes`');

        $query->where('Id = :id');
        $query->setParameter(':id', $documentType->id(), DB::STRING);

        $query->execute();
    }
}
