<?php declare(strict_types=1);
/*
 * This file is part of FlexPHP.
 *
 * (c) Freddie Gar <freddie.gar@outlook.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace FlexPHP\Bundle\LocationBundle\Domain\Country\Gateway;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Types\Types as DB;
use FlexPHP\Bundle\LocationBundle\Domain\Country\Country;
use FlexPHP\Bundle\LocationBundle\Domain\Country\CountryGateway;
use FlexPHP\Bundle\HelperBundle\Domain\Helper\DbalCriteriaHelper;

class MySQLCountryGateway implements CountryGateway
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
            'country.Id as id',
            'country.Name as name',
            'country.Code as code',
        ]);
        $query->from('`Countries`', '`country`');

        $query->orderBy('country.UpdatedAt', 'DESC');

        $criteria = new DbalCriteriaHelper($query, $offset);

        foreach ($wheres as $column => $value) {
            $criteria->getCriteria('country', $column, $value, $this->operator[$column] ?? DbalCriteriaHelper::OP_EQUALS);
        }

        $query->setFirstResult($page ? ($page - 1) * $limit : 0);
        $query->setMaxResults($limit);

        return $query->execute()->fetchAll();
    }

    public function push(Country $country): int
    {
        $query = $this->conn->createQueryBuilder();

        $query->insert('`Countries`');

        $query->setValue('Name', ':name');
        $query->setValue('Code', ':code');
        $query->setValue('CreatedAt', ':createdAt');
        $query->setValue('UpdatedAt', ':updatedAt');
        $query->setValue('CreatedBy', ':createdBy');

        $query->setParameter(':name', $country->name(), DB::STRING);
        $query->setParameter(':code', $country->code(), DB::STRING);
        $query->setParameter(':createdAt', $country->createdAt() ?? new \DateTime(date('Y-m-d H:i:s')), DB::DATETIME_MUTABLE);
        $query->setParameter(':updatedAt', $country->updatedAt() ?? new \DateTime(date('Y-m-d H:i:s')), DB::DATETIME_MUTABLE);
        $query->setParameter(':createdBy', $country->createdBy(), DB::INTEGER);

        $query->execute();

        return (int)$query->getConnection()->lastInsertId();
    }

    public function get(Country $country): array
    {
        $query = $this->conn->createQueryBuilder();

        $query->select([
            'country.Id as id',
            'country.Name as name',
            'country.Code as code',
            'country.CreatedAt as createdAt',
            'country.UpdatedAt as updatedAt',
            'country.CreatedBy as createdBy',
            'country.UpdatedBy as updatedBy',
            'createdBy.id as `createdBy.id`',
            'createdBy.name as `createdBy.name`',
            'updatedBy.id as `updatedBy.id`',
            'updatedBy.name as `updatedBy.name`',
        ]);
        $query->from('`Countries`', '`country`');
        $query->leftJoin('`country`', '`Users`', '`createdBy`', 'country.CreatedBy = createdBy.id');
        $query->leftJoin('`country`', '`Users`', '`updatedBy`', 'country.UpdatedBy = updatedBy.id');
        $query->where('country.Id = :id');
        $query->setParameter(':id', $country->id(), DB::INTEGER);

        return $query->execute()->fetch() ?: [];
    }

    public function shift(Country $country): void
    {
        $query = $this->conn->createQueryBuilder();

        $query->update('`Countries`');

        $query->set('Name', ':name');
        $query->set('Code', ':code');
        $query->set('UpdatedAt', ':updatedAt');
        $query->set('UpdatedBy', ':updatedBy');

        $query->setParameter(':name', $country->name(), DB::STRING);
        $query->setParameter(':code', $country->code(), DB::STRING);
        $query->setParameter(':updatedAt', $country->updatedAt() ?? new \DateTime(date('Y-m-d H:i:s')), DB::DATETIME_MUTABLE);
        $query->setParameter(':updatedBy', $country->updatedBy(), DB::INTEGER);

        $query->where('Id = :id');
        $query->setParameter(':id', $country->id(), DB::INTEGER);

        $query->execute();
    }

    public function pop(Country $country): void
    {
        $query = $this->conn->createQueryBuilder();

        $query->delete('`Countries`');

        $query->where('Id = :id');
        $query->setParameter(':id', $country->id(), DB::INTEGER);

        $query->execute();
    }
}
