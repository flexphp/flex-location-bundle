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
use FlexPHP\Bundle\LocationBundle\Domain\Country\Request\DeleteCountryRequest;
use FlexPHP\Bundle\LocationBundle\Domain\Country\Response\DeleteCountryResponse;

final class DeleteCountryUseCase
{
    private CountryRepository $countryRepository;

    public function __construct(CountryRepository $countryRepository)
    {
        $this->countryRepository = $countryRepository;
    }

    public function execute(DeleteCountryRequest $request): DeleteCountryResponse
    {
        return new DeleteCountryResponse($this->countryRepository->remove($request));
    }
}
