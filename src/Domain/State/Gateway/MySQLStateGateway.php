<?php declare(strict_types=1);
/*
 * This file is part of FlexPHP.
 *
 * (c) Freddie Gar <freddie.gar@outlook.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace FlexPHP\Bundle\LocationBundle\Domain\State\Gateway;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Types\Types as DB;
use FlexPHP\Bundle\HelperBundle\Domain\Helper\DbalCriteriaHelper;
use FlexPHP\Bundle\LocationBundle\Domain\State\Request\FindStateCountryRequest;
use FlexPHP\Bundle\LocationBundle\Domain\State\State;
use FlexPHP\Bundle\LocationBundle\Domain\State\StateGateway;

class MySQLStateGateway implements StateGateway
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
            'state.Id as id',
            'state.CountryId as countryId',
            'state.Name as name',
            'state.Code as code',
            'countryId.id as `countryId.id`',
            'countryId.name as `countryId.name`',
        ]);
        $query->from('`States`', '`state`');
        $query->join('`state`', '`Countries`', '`countryId`', 'state.CountryId = countryId.id');

        $query->orderBy('state.UpdatedAt', 'DESC');

        $criteria = new DbalCriteriaHelper($query, $offset);

        foreach ($wheres as $column => $value) {
            $criteria->getCriteria('state', $column, $value, $this->operator[$column] ?? DbalCriteriaHelper::OP_EQUALS);
        }

        $query->setFirstResult($page ? ($page - 1) * $limit : 0);
        $query->setMaxResults($limit);

        return $query->execute()->fetchAll();
    }

    public function push(State $state): int
    {
        $query = $this->conn->createQueryBuilder();

        $query->insert('`States`');

        $query->setValue('CountryId', ':countryId');
        $query->setValue('Name', ':name');
        $query->setValue('Code', ':code');
        $query->setValue('CreatedAt', ':createdAt');
        $query->setValue('UpdatedAt', ':updatedAt');
        $query->setValue('CreatedBy', ':createdBy');

        $query->setParameter(':countryId', $state->countryId(), DB::INTEGER);
        $query->setParameter(':name', $state->name(), DB::STRING);
        $query->setParameter(':code', $state->code(), DB::STRING);
        $query->setParameter(':createdAt', $state->createdAt() ?? new \DateTime(date('Y-m-d H:i:s')), DB::DATETIME_MUTABLE);
        $query->setParameter(':updatedAt', $state->updatedAt() ?? new \DateTime(date('Y-m-d H:i:s')), DB::DATETIME_MUTABLE);
        $query->setParameter(':createdBy', $state->createdBy(), DB::INTEGER);

        $query->execute();

        return (int)$query->getConnection()->lastInsertId();
    }

    public function get(State $state): array
    {
        $query = $this->conn->createQueryBuilder();

        $query->select([
            'state.Id as id',
            'state.CountryId as countryId',
            'state.Name as name',
            'state.Code as code',
            'state.CreatedAt as createdAt',
            'state.UpdatedAt as updatedAt',
            'state.CreatedBy as createdBy',
            'state.UpdatedBy as updatedBy',
            'countryId.id as `countryId.id`',
            'countryId.name as `countryId.name`',
            'createdBy.id as `createdBy.id`',
            'createdBy.name as `createdBy.name`',
            'updatedBy.id as `updatedBy.id`',
            'updatedBy.name as `updatedBy.name`',
        ]);
        $query->from('`States`', '`state`');
        $query->join('`state`', '`Countries`', '`countryId`', 'state.CountryId = countryId.id');
        $query->leftJoin('`state`', '`Users`', '`createdBy`', 'state.CreatedBy = createdBy.id');
        $query->leftJoin('`state`', '`Users`', '`updatedBy`', 'state.UpdatedBy = updatedBy.id');
        $query->where('state.Id = :id');
        $query->setParameter(':id', $state->id(), DB::INTEGER);

        return $query->execute()->fetch() ?: [];
    }

    public function shift(State $state): void
    {
        $query = $this->conn->createQueryBuilder();

        $query->update('`States`');

        $query->set('CountryId', ':countryId');
        $query->set('Name', ':name');
        $query->set('Code', ':code');
        $query->set('UpdatedAt', ':updatedAt');
        $query->set('UpdatedBy', ':updatedBy');

        $query->setParameter(':countryId', $state->countryId(), DB::INTEGER);
        $query->setParameter(':name', $state->name(), DB::STRING);
        $query->setParameter(':code', $state->code(), DB::STRING);
        $query->setParameter(':updatedAt', $state->updatedAt() ?? new \DateTime(date('Y-m-d H:i:s')), DB::DATETIME_MUTABLE);
        $query->setParameter(':updatedBy', $state->updatedBy(), DB::INTEGER);

        $query->where('Id = :id');
        $query->setParameter(':id', $state->id(), DB::INTEGER);

        $query->execute();
    }

    public function pop(State $state): void
    {
        $query = $this->conn->createQueryBuilder();

        $query->delete('`States`');

        $query->where('Id = :id');
        $query->setParameter(':id', $state->id(), DB::INTEGER);

        $query->execute();
    }

    public function filterCountries(FindStateCountryRequest $request, int $page, int $limit): array
    {
        $query = $this->conn->createQueryBuilder();

        $query->select([
            'country.id as id',
            'country.name as text',
        ]);
        $query->from('`Countries`', '`country`');

        $query->where('country.name like :country_name');
        $query->setParameter(':country_name', "%{$request->term}%");

        $query->setFirstResult($page ? ($page - 1) * $limit : 0);
        $query->setMaxResults($limit);

        return $query->execute()->fetchAll();
    }
}
