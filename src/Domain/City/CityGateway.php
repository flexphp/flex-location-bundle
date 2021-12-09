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

use FlexPHP\Bundle\LocationBundle\Domain\City\Request\FindCityStateRequest;

interface CityGateway
{
    public function search(array $wheres, array $orders, int $page, int $limit, int $offset): array;

    public function push(City $city): int;

    public function get(City $city): array;

    public function shift(City $city): void;

    public function pop(City $city): void;

    public function filterStates(FindCityStateRequest $request, int $page, int $limit): array;
}
