<?php declare(strict_types=1);
/*
 * This file is part of FlexPHP.
 *
 * (c) Freddie Gar <freddie.gar@outlook.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace FlexPHP\Bundle\LocationBundle\Domain\Currency;

interface CurrencyGateway
{
    public function search(array $wheres, array $orders, int $page, int $limit, int $offset): array;

    public function push(Currency $currency): string;

    public function get(Currency $currency): array;

    public function shift(Currency $currency): void;

    public function pop(Currency $currency): void;
}
