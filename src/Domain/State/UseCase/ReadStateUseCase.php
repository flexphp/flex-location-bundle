<?php declare(strict_types=1);
/*
 * This file is part of FlexPHP.
 *
 * (c) Freddie Gar <freddie.gar@outlook.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace FlexPHP\Bundle\LocationBundle\Domain\State\UseCase;

use FlexPHP\Bundle\LocationBundle\Domain\State\Request\ReadStateRequest;
use FlexPHP\Bundle\LocationBundle\Domain\State\Response\ReadStateResponse;
use FlexPHP\Bundle\LocationBundle\Domain\State\StateRepository;

final class ReadStateUseCase
{
    private StateRepository $stateRepository;

    public function __construct(StateRepository $stateRepository)
    {
        $this->stateRepository = $stateRepository;
    }

    public function execute(ReadStateRequest $request): ReadStateResponse
    {
        $state = $this->stateRepository->getById($request);

        return new ReadStateResponse($state);
    }
}
