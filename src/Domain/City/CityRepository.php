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

use FlexPHP\Bundle\LocationBundle\Domain\City\Request\CreateCityRequest;
use FlexPHP\Bundle\LocationBundle\Domain\City\Request\DeleteCityRequest;
use FlexPHP\Bundle\LocationBundle\Domain\City\Request\FindCityStateRequest;
use FlexPHP\Bundle\LocationBundle\Domain\City\Request\IndexCityRequest;
use FlexPHP\Bundle\LocationBundle\Domain\City\Request\ReadCityRequest;
use FlexPHP\Bundle\LocationBundle\Domain\City\Request\UpdateCityRequest;

final class CityRepository
{
    private CityGateway $gateway;

    public function __construct(CityGateway $gateway)
    {
        $this->gateway = $gateway;
    }

    /**
     * @return array<City>
     */
    public function findBy(IndexCityRequest $request): array
    {
        return \array_map(function (array $city) {
            return (new CityFactory())->make($city);
        }, $this->gateway->search((array)$request, [], $request->_page, $request->_limit, $request->_offset));
    }

    public function add(CreateCityRequest $request): City
    {
        $city = (new CityFactory())->make($request);

        $city->setId($this->gateway->push($city));

        return $city;
    }

    public function getById(ReadCityRequest $request): City
    {
        $factory = new CityFactory();
        $data = $this->gateway->get($factory->make($request));

        return $factory->make($data);
    }

    public function change(UpdateCityRequest $request): City
    {
        $city = (new CityFactory())->make($request);

        $this->gateway->shift($city);

        return $city;
    }

    public function remove(DeleteCityRequest $request): City
    {
        $factory = new CityFactory();
        $data = $this->gateway->get($factory->make($request));

        $city = $factory->make($data);

        $this->gateway->pop($city);

        return $city;
    }

    public function findStatesBy(FindCityStateRequest $request): array
    {
        return $this->gateway->filterStates($request, $request->_page, $request->_limit);
    }
}
