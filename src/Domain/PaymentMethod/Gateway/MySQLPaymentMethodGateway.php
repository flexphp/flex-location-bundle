<?php declare(strict_types=1);
/*
 * This file is part of FlexPHP.
 *
 * (c) Freddie Gar <freddie.gar@outlook.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace FlexPHP\Bundle\LocationBundle\Domain\PaymentMethod\Gateway;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Types\Types as DB;
use FlexPHP\Bundle\HelperBundle\Domain\Helper\DbalCriteriaHelper;
use FlexPHP\Bundle\LocationBundle\Domain\PaymentMethod\PaymentMethod;
use FlexPHP\Bundle\LocationBundle\Domain\PaymentMethod\PaymentMethodGateway;

class MySQLPaymentMethodGateway implements PaymentMethodGateway
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
            'paymentMethod.Id as id',
            'paymentMethod.Name as name',
            'paymentMethod.IsActive as isActive',
        ]);
        $query->from('`PaymentMethods`', '`paymentMethod`');

        $query->orderBy('paymentMethod.UpdatedAt', 'DESC');

        $criteria = new DbalCriteriaHelper($query, $offset);

        foreach ($wheres as $column => $value) {
            $criteria->getCriteria('paymentMethod', $column, $value, $this->operator[$column] ?? DbalCriteriaHelper::OP_EQUALS);
        }

        $query->setFirstResult($page ? ($page - 1) * $limit : 0);
        $query->setMaxResults($limit);

        return $query->execute()->fetchAll();
    }

    public function push(PaymentMethod $paymentMethod): string
    {
        $query = $this->conn->createQueryBuilder();

        $query->insert('`PaymentMethods`');

        $query->setValue('Id', ':id');
        $query->setValue('Name', ':name');
        $query->setValue('IsActive', ':isActive');
        $query->setValue('CreatedAt', ':createdAt');
        $query->setValue('UpdatedAt', ':updatedAt');
        $query->setValue('CreatedBy', ':createdBy');

        $query->setParameter(':id', $paymentMethod->id(), DB::STRING);
        $query->setParameter(':name', $paymentMethod->name(), DB::STRING);
        $query->setParameter(':isActive', $paymentMethod->isActive(), DB::BOOLEAN);
        $query->setParameter(':createdAt', $paymentMethod->createdAt() ?? new \DateTime(date('Y-m-d H:i:s')), DB::DATETIME_MUTABLE);
        $query->setParameter(':updatedAt', $paymentMethod->updatedAt() ?? new \DateTime(date('Y-m-d H:i:s')), DB::DATETIME_MUTABLE);
        $query->setParameter(':createdBy', $paymentMethod->createdBy(), DB::INTEGER);

        $query->execute();

        return $paymentMethod->id();
    }

    public function get(PaymentMethod $paymentMethod): array
    {
        $query = $this->conn->createQueryBuilder();

        $query->select([
            'paymentMethod.Id as id',
            'paymentMethod.Name as name',
            'paymentMethod.IsActive as isActive',
            'paymentMethod.CreatedAt as createdAt',
            'paymentMethod.UpdatedAt as updatedAt',
            'paymentMethod.CreatedBy as createdBy',
            'paymentMethod.UpdatedBy as updatedBy',
            'createdBy.id as `createdBy.id`',
            'createdBy.name as `createdBy.name`',
            'updatedBy.id as `updatedBy.id`',
            'updatedBy.name as `updatedBy.name`',
        ]);
        $query->from('`PaymentMethods`', '`paymentMethod`');
        $query->leftJoin('`paymentMethod`', '`Users`', '`createdBy`', 'paymentMethod.CreatedBy = createdBy.id');
        $query->leftJoin('`paymentMethod`', '`Users`', '`updatedBy`', 'paymentMethod.UpdatedBy = updatedBy.id');
        $query->where('paymentMethod.Id = :id');
        $query->setParameter(':id', $paymentMethod->id(), DB::STRING);

        return $query->execute()->fetch() ?: [];
    }

    public function shift(PaymentMethod $paymentMethod): void
    {
        $query = $this->conn->createQueryBuilder();

        $query->update('`PaymentMethods`');

        $query->set('Id', ':id');
        $query->set('Name', ':name');
        $query->set('IsActive', ':isActive');
        $query->set('UpdatedAt', ':updatedAt');
        $query->set('UpdatedBy', ':updatedBy');

        $query->setParameter(':id', $paymentMethod->id(), DB::STRING);
        $query->setParameter(':name', $paymentMethod->name(), DB::STRING);
        $query->setParameter(':isActive', $paymentMethod->isActive(), DB::BOOLEAN);
        $query->setParameter(':updatedAt', $paymentMethod->updatedAt() ?? new \DateTime(date('Y-m-d H:i:s')), DB::DATETIME_MUTABLE);
        $query->setParameter(':updatedBy', $paymentMethod->updatedBy(), DB::INTEGER);

        $query->where('Id = :id');
        $query->setParameter(':id', $paymentMethod->id(), DB::STRING);

        $query->execute();
    }

    public function pop(PaymentMethod $paymentMethod): void
    {
        $query = $this->conn->createQueryBuilder();

        $query->delete('`PaymentMethods`');

        $query->where('Id = :id');
        $query->setParameter(':id', $paymentMethod->id(), DB::STRING);

        $query->execute();
    }
}
