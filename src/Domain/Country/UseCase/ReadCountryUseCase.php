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
use FlexPHP\Bundle\LocationBundle\Domain\Country\Request\ReadCountryRequest;
use FlexPHP\Bundle\LocationBundle\Domain\Country\Response\ReadCountryResponse;

final class ReadCountryUseCase
{
    private CountryRepository $countryRepository;

    public function __construct(CountryRepository $countryRepository)
    {
        $this->countryRepository = $countryRepository;
    }

    public function execute(ReadCountryRequest $request): ReadCountryResponse
    {
        $country = $this->countryRepository->getById($request);

        return new ReadCountryResponse($country);
    }
}
