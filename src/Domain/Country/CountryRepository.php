<?php declare(strict_types=1);
/*
 * This file is part of FlexPHP.
 *
 * (c) Freddie Gar <freddie.gar@outlook.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace FlexPHP\Bundle\LocationBundle\Domain\Country;

use FlexPHP\Bundle\LocationBundle\Domain\Country\Request\CreateCountryRequest;
use FlexPHP\Bundle\LocationBundle\Domain\Country\Request\DeleteCountryRequest;
use FlexPHP\Bundle\LocationBundle\Domain\Country\Request\IndexCountryRequest;
use FlexPHP\Bundle\LocationBundle\Domain\Country\Request\ReadCountryRequest;
use FlexPHP\Bundle\LocationBundle\Domain\Country\Request\UpdateCountryRequest;

final class CountryRepository
{
    private CountryGateway $gateway;

    public function __construct(CountryGateway $gateway)
    {
        $this->gateway = $gateway;
    }

    /**
     * @return array<Country>
     */
    public function findBy(IndexCountryRequest $request): array
    {
        return \array_map(function (array $country) {
            return (new CountryFactory())->make($country);
        }, $this->gateway->search((array)$request, [], $request->_page, $request->_limit, $request->_offset));
    }

    public function add(CreateCountryRequest $request): Country
    {
        $country = (new CountryFactory())->make($request);

        $country->setId($this->gateway->push($country));

        return $country;
    }

    public function getById(ReadCountryRequest $request): Country
    {
        $factory = new CountryFactory();
        $data = $this->gateway->get($factory->make($request));

        return $factory->make($data);
    }

    public function change(UpdateCountryRequest $request): Country
    {
        $country = (new CountryFactory())->make($request);

        $this->gateway->shift($country);

        return $country;
    }

    public function remove(DeleteCountryRequest $request): Country
    {
        $factory = new CountryFactory();
        $data = $this->gateway->get($factory->make($request));

        $country = $factory->make($data);

        $this->gateway->pop($country);

        return $country;
    }
}
