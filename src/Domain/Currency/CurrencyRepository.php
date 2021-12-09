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

use FlexPHP\Bundle\LocationBundle\Domain\Currency\Request\CreateCurrencyRequest;
use FlexPHP\Bundle\LocationBundle\Domain\Currency\Request\DeleteCurrencyRequest;
use FlexPHP\Bundle\LocationBundle\Domain\Currency\Request\IndexCurrencyRequest;
use FlexPHP\Bundle\LocationBundle\Domain\Currency\Request\ReadCurrencyRequest;
use FlexPHP\Bundle\LocationBundle\Domain\Currency\Request\UpdateCurrencyRequest;

final class CurrencyRepository
{
    private CurrencyGateway $gateway;

    public function __construct(CurrencyGateway $gateway)
    {
        $this->gateway = $gateway;
    }

    /**
     * @return array<Currency>
     */
    public function findBy(IndexCurrencyRequest $request): array
    {
        return \array_map(function (array $currency) {
            return (new CurrencyFactory())->make($currency);
        }, $this->gateway->search((array)$request, [], $request->_page, $request->_limit, $request->_offset));
    }

    public function add(CreateCurrencyRequest $request): Currency
    {
        $currency = (new CurrencyFactory())->make($request);

        $currency->setId($this->gateway->push($currency));

        return $currency;
    }

    public function getById(ReadCurrencyRequest $request): Currency
    {
        $factory = new CurrencyFactory();
        $data = $this->gateway->get($factory->make($request));

        return $factory->make($data);
    }

    public function change(UpdateCurrencyRequest $request): Currency
    {
        $currency = (new CurrencyFactory())->make($request);

        $this->gateway->shift($currency);

        return $currency;
    }

    public function remove(DeleteCurrencyRequest $request): Currency
    {
        $factory = new CurrencyFactory();
        $data = $this->gateway->get($factory->make($request));

        $currency = $factory->make($data);

        $this->gateway->pop($currency);

        return $currency;
    }
}
