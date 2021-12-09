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
use FlexPHP\Bundle\LocationBundle\Domain\City\Request\FindCityUserRequest;
use FlexPHP\Bundle\LocationBundle\Domain\City\Response\FindCityUserResponse;

final class FindCityUserUseCase
{
    private CityRepository $cityRepository;

    public function __construct(CityRepository $cityRepository)
    {
        $this->cityRepository = $cityRepository;
    }

    public function execute(FindCityUserRequest $request): FindCityUserResponse
    {
        $users = $this->cityRepository->findUsersBy($request);

        return new FindCityUserResponse($users);
    }
}
