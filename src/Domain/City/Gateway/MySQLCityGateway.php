<?php declare(strict_types=1);
/*
 * This file is part of FlexPHP.
 *
 * (c) Freddie Gar <freddie.gar@outlook.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace FlexPHP\Bundle\LocationBundle\Domain\City\Gateway;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Types\Types as DB;
use FlexPHP\Bundle\LocationBundle\Domain\City\City;
use FlexPHP\Bundle\LocationBundle\Domain\City\CityGateway;
use FlexPHP\Bundle\LocationBundle\Domain\City\Request\FindCityStateRequest;
use FlexPHP\Bundle\HelperBundle\Domain\Helper\DbalCriteriaHelper;

class MySQLCityGateway implements CityGateway
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
            'city.Id as id',
            'city.StateId as stateId',
            'city.Name as name',
            'city.Code as code',
            'stateId.id as `stateId.id`',
            'stateId.name as `stateId.name`',
        ]);
        $query->from('`Cities`', '`city`');
        $query->join('`city`', '`States`', '`stateId`', 'city.StateId = stateId.id');

        $query->orderBy('city.UpdatedAt', 'DESC');

        $criteria = new DbalCriteriaHelper($query, $offset);

        foreach ($wheres as $column => $value) {
            $criteria->getCriteria('city', $column, $value, $this->operator[$column] ?? DbalCriteriaHelper::OP_EQUALS);
        }

        $query->setFirstResult($page ? ($page - 1) * $limit : 0);
        $query->setMaxResults($limit);

        return $query->execute()->fetchAll();
    }

    public function push(City $city): int
    {
        $query = $this->conn->createQueryBuilder();

        $query->insert('`Cities`');

        $query->setValue('StateId', ':stateId');
        $query->setValue('Name', ':name');
        $query->setValue('Code', ':code');
        $query->setValue('CreatedAt', ':createdAt');
        $query->setValue('UpdatedAt', ':updatedAt');
        $query->setValue('CreatedBy', ':createdBy');

        $query->setParameter(':stateId', $city->stateId(), DB::INTEGER);
        $query->setParameter(':name', $city->name(), DB::STRING);
        $query->setParameter(':code', $city->code(), DB::STRING);
        $query->setParameter(':createdAt', $city->createdAt() ?? new \DateTime(date('Y-m-d H:i:s')), DB::DATETIME_MUTABLE);
        $query->setParameter(':updatedAt', $city->updatedAt() ?? new \DateTime(date('Y-m-d H:i:s')), DB::DATETIME_MUTABLE);
        $query->setParameter(':createdBy', $city->createdBy(), DB::INTEGER);

        $query->execute();

        return (int)$query->getConnection()->lastInsertId();
    }

    public function get(City $city): array
    {
        $query = $this->conn->createQueryBuilder();

        $query->select([
            'city.Id as id',
            'city.StateId as stateId',
            'city.Name as name',
            'city.Code as code',
            'city.CreatedAt as createdAt',
            'city.UpdatedAt as updatedAt',
            'city.CreatedBy as createdBy',
            'city.UpdatedBy as updatedBy',
            'stateId.id as `stateId.id`',
            'stateId.name as `stateId.name`',
            'createdBy.id as `createdBy.id`',
            'createdBy.name as `createdBy.name`',
            'updatedBy.id as `updatedBy.id`',
            'updatedBy.name as `updatedBy.name`',
        ]);
        $query->from('`Cities`', '`city`');
        $query->join('`city`', '`States`', '`stateId`', 'city.StateId = stateId.id');
        $query->leftJoin('`city`', '`Users`', '`createdBy`', 'city.CreatedBy = createdBy.id');
        $query->leftJoin('`city`', '`Users`', '`updatedBy`', 'city.UpdatedBy = updatedBy.id');
        $query->where('city.Id = :id');
        $query->setParameter(':id', $city->id(), DB::INTEGER);

        return $query->execute()->fetch() ?: [];
    }

    public function shift(City $city): void
    {
        $query = $this->conn->createQueryBuilder();

        $query->update('`Cities`');

        $query->set('StateId', ':stateId');
        $query->set('Name', ':name');
        $query->set('Code', ':code');
        $query->set('UpdatedAt', ':updatedAt');
        $query->set('UpdatedBy', ':updatedBy');

        $query->setParameter(':stateId', $city->stateId(), DB::INTEGER);
        $query->setParameter(':name', $city->name(), DB::STRING);
        $query->setParameter(':code', $city->code(), DB::STRING);
        $query->setParameter(':updatedAt', $city->updatedAt() ?? new \DateTime(date('Y-m-d H:i:s')), DB::DATETIME_MUTABLE);
        $query->setParameter(':updatedBy', $city->updatedBy(), DB::INTEGER);

        $query->where('Id = :id');
        $query->setParameter(':id', $city->id(), DB::INTEGER);

        $query->execute();
    }

    public function pop(City $city): void
    {
        $query = $this->conn->createQueryBuilder();

        $query->delete('`Cities`');

        $query->where('Id = :id');
        $query->setParameter(':id', $city->id(), DB::INTEGER);

        $query->execute();
    }

    public function filterStates(FindCityStateRequest $request, int $page, int $limit): array
    {
        $query = $this->conn->createQueryBuilder();

        $query->select([
            'state.id as id',
            'state.name as text',
        ]);
        $query->from('`States`', '`state`');

        $query->where('state.name like :state_name');
        $query->setParameter(':state_name', "%{$request->term}%");

        $query->setFirstResult($page ? ($page - 1) * $limit : 0);
        $query->setMaxResults($limit);

        return $query->execute()->fetchAll();
    }
}
