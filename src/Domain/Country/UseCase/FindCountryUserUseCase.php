<?php declare(strict_types=1);
/*
 * This file is part of FlexPHP.
 *
 * (c) Freddie Gar <freddie.gar@outlook.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace FlexPHP\Bundle\LocationBundle\Domain\Country\UseCase;

use FlexPHP\Bundle\LocationBundle\Domain\Country\CountryRepository;
use FlexPHP\Bundle\LocationBundle\Domain\Country\Request\FindCountryUserRequest;
use FlexPHP\Bundle\LocationBundle\Domain\Country\Response\FindCountryUserResponse;

final class FindCountryUserUseCase
{
    private CountryRepository $countryRepository;

    public function __construct(CountryRepository $countryRepository)
    {
        $this->countryRepository = $countryRepository;
    }

    public function execute(FindCountryUserRequest $request): FindCountryUserResponse
    {
        $users = $this->countryRepository->findUsersBy($request);

        return new FindCountryUserResponse($users);
    }
}
