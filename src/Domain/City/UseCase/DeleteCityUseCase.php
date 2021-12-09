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
use FlexPHP\Bundle\LocationBundle\Domain\City\Request\DeleteCityRequest;
use FlexPHP\Bundle\LocationBundle\Domain\City\Response\DeleteCityResponse;

final class DeleteCityUseCase
{
    private CityRepository $cityRepository;

    public function __construct(CityRepository $cityRepository)
    {
        $this->cityRepository = $cityRepository;
    }

    public function execute(DeleteCityRequest $request): DeleteCityResponse
    {
        return new DeleteCityResponse($this->cityRepository->remove($request));
    }
}
