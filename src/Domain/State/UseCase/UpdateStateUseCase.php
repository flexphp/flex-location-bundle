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

use FlexPHP\Bundle\LocationBundle\Domain\State\Request\UpdateStateRequest;
use FlexPHP\Bundle\LocationBundle\Domain\State\Response\UpdateStateResponse;
use FlexPHP\Bundle\LocationBundle\Domain\State\StateRepository;

final class UpdateStateUseCase
{
    private StateRepository $stateRepository;

    public function __construct(StateRepository $stateRepository)
    {
        $this->stateRepository = $stateRepository;
    }

    public function execute(UpdateStateRequest $request): UpdateStateResponse
    {
        return new UpdateStateResponse($this->stateRepository->change($request));
    }
}
