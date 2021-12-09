<?php declare(strict_types=1);
/*
 * This file is part of FlexPHP.
 *
 * (c) Freddie Gar <freddie.gar@outlook.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace FlexPHP\Bundle\LocationBundle\Domain\City\UseCase;

use FlexPHP\Bundle\LocationBundle\Domain\City\CityRepository;
use FlexPHP\Bundle\LocationBundle\Domain\City\Request\FindCityStateRequest;
use FlexPHP\Bundle\LocationBundle\Domain\City\Response\FindCityStateResponse;

final class FindCityStateUseCase
{
    private CityRepository $cityRepository;

    public function __construct(CityRepository $cityRepository)
    {
        $this->cityRepository = $cityRepository;
    }

    public function execute(FindCityStateRequest $request): FindCityStateResponse
    {
        $states = $this->cityRepository->findStatesBy($request);

        return new FindCityStateResponse($states);
    }
}
