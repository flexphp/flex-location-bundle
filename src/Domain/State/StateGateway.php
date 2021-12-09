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

use FlexPHP\Bundle\LocationBundle\Domain\State\Request\FindStateCountryRequest;

interface StateGateway
{
    public function search(array $wheres, array $orders, int $page, int $limit, int $offset): array;

    public function push(State $state): int;

    public function get(State $state): array;

    public function shift(State $state): void;

    public function pop(State $state): void;

    public function filterCountries(FindStateCountryRequest $request, int $page, int $limit): array;
}
