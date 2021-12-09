<?php declare(strict_types=1);
/*
 * This file is part of FlexPHP.
 *
 * (c) Freddie Gar <freddie.gar@outlook.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace FlexPHP\Bundle\LocationBundle\Domain\Currency\Gateway;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Types\Types as DB;
use FlexPHP\Bundle\LocationBundle\Domain\Currency\Currency;
use FlexPHP\Bundle\LocationBundle\Domain\Currency\CurrencyGateway;
use FlexPHP\Bundle\HelperBundle\Domain\Helper\DbalCriteriaHelper;

class MySQLCurrencyGateway implements CurrencyGateway
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
            'currency.Id as id',
            'currency.Name as name',
            'currency.IsActive as isActive',
        ]);
        $query->from('`Currencies`', '`currency`');

        $query->orderBy('currency.UpdatedAt', 'DESC');

        $criteria = new DbalCriteriaHelper($query, $offset);

        foreach ($wheres as $column => $value) {
            $criteria->getCriteria('currency', $column, $value, $this->operator[$column] ?? DbalCriteriaHelper::OP_EQUALS);
        }

        $query->setFirstResult($page ? ($page - 1) * $limit : 0);
        $query->setMaxResults($limit);

        return $query->execute()->fetchAll();
    }

    public function push(Currency $currency): string
    {
        $query = $this->conn->createQueryBuilder();

        $query->insert('`Currencies`');

        $query->setValue('Id', ':id');
        $query->setValue('Name', ':name');
        $query->setValue('IsActive', ':isActive');
        $query->setValue('CreatedAt', ':createdAt');
        $query->setValue('UpdatedAt', ':updatedAt');
        $query->setValue('CreatedBy', ':createdBy');

        $query->setParameter(':id', $currency->id(), DB::STRING);
        $query->setParameter(':name', $currency->name(), DB::STRING);
        $query->setParameter(':isActive', $currency->isActive(), DB::BOOLEAN);
        $query->setParameter(':createdAt', $currency->createdAt() ?? new \DateTime(date('Y-m-d H:i:s')), DB::DATETIME_MUTABLE);
        $query->setParameter(':updatedAt', $currency->updatedAt() ?? new \DateTime(date('Y-m-d H:i:s')), DB::DATETIME_MUTABLE);
        $query->setParameter(':createdBy', $currency->createdBy(), DB::INTEGER);

        $query->execute();

        return $currency->id();
    }

    public function get(Currency $currency): array
    {
        $query = $this->conn->createQueryBuilder();

        $query->select([
            'currency.Id as id',
            'currency.Name as name',
            'currency.IsActive as isActive',
            'currency.CreatedAt as createdAt',
            'currency.UpdatedAt as updatedAt',
            'currency.CreatedBy as createdBy',
            'currency.UpdatedBy as updatedBy',
            'createdBy.id as `createdBy.id`',
            'createdBy.name as `createdBy.name`',
            'updatedBy.id as `updatedBy.id`',
            'updatedBy.name as `updatedBy.name`',
        ]);
        $query->from('`Currencies`', '`currency`');
        $query->leftJoin('`currency`', '`Users`', '`createdBy`', 'currency.CreatedBy = createdBy.id');
        $query->leftJoin('`currency`', '`Users`', '`updatedBy`', 'currency.UpdatedBy = updatedBy.id');
        $query->where('currency.Id = :id');
        $query->setParameter(':id', $currency->id(), DB::STRING);

        return $query->execute()->fetch() ?: [];
    }

    public function shift(Currency $currency): void
    {
        $query = $this->conn->createQueryBuilder();

        $query->update('`Currencies`');

        $query->set('Id', ':id');
        $query->set('Name', ':name');
        $query->set('IsActive', ':isActive');
        $query->set('UpdatedAt', ':updatedAt');
        $query->set('UpdatedBy', ':updatedBy');

        $query->setParameter(':id', $currency->id(), DB::STRING);
        $query->setParameter(':name', $currency->name(), DB::STRING);
        $query->setParameter(':isActive', $currency->isActive(), DB::BOOLEAN);
        $query->setParameter(':updatedAt', $currency->updatedAt() ?? new \DateTime(date('Y-m-d H:i:s')), DB::DATETIME_MUTABLE);
        $query->setParameter(':updatedBy', $currency->updatedBy(), DB::INTEGER);

        $query->where('Id = :id');
        $query->setParameter(':id', $currency->id(), DB::STRING);

        $query->execute();
    }

    public function pop(Currency $currency): void
    {
        $query = $this->conn->createQueryBuilder();

        $query->delete('`Currencies`');

        $query->where('Id = :id');
        $query->setParameter(':id', $currency->id(), DB::STRING);

        $query->execute();
    }
}
