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

use FlexPHP\Bundle\LocationBundle\Domain\State\Request\CreateStateRequest;
use FlexPHP\Bundle\LocationBundle\Domain\State\Request\DeleteStateRequest;
use FlexPHP\Bundle\LocationBundle\Domain\State\Request\FindStateCountryRequest;
use FlexPHP\Bundle\LocationBundle\Domain\State\Request\IndexStateRequest;
use FlexPHP\Bundle\LocationBundle\Domain\State\Request\ReadStateRequest;
use FlexPHP\Bundle\LocationBundle\Domain\State\Request\UpdateStateRequest;

final class StateRepository
{
    private StateGateway $gateway;

    public function __construct(StateGateway $gateway)
    {
        $this->gateway = $gateway;
    }

    /**
     * @return array<State>
     */
    public function findBy(IndexStateRequest $request): array
    {
        return \array_map(function (array $state) {
            return (new StateFactory())->make($state);
        }, $this->gateway->search((array)$request, [], $request->_page, $request->_limit, $request->_offset));
    }

    public function add(CreateStateRequest $request): State
    {
        $state = (new StateFactory())->make($request);

        $state->setId($this->gateway->push($state));

        return $state;
    }

    public function getById(ReadStateRequest $request): State
    {
        $factory = new StateFactory();
        $data = $this->gateway->get($factory->make($request));

        return $factory->make($data);
    }

    public function change(UpdateStateRequest $request): State
    {
        $state = (new StateFactory())->make($request);

        $this->gateway->shift($state);

        return $state;
    }

    public function remove(DeleteStateRequest $request): State
    {
        $factory = new StateFactory();
        $data = $this->gateway->get($factory->make($request));

        $state = $factory->make($data);

        $this->gateway->pop($state);

        return $state;
    }

    public function findCountriesBy(FindStateCountryRequest $request): array
    {
        return $this->gateway->filterCountries($request, $request->_page, $request->_limit);
    }
}
