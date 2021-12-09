<?php declare(strict_types=1);
/*
 * This file is part of FlexPHP.
 *
 * (c) Freddie Gar <freddie.gar@outlook.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace FlexPHP\Bundle\LocationBundle\Domain\Currency\UseCase;

use FlexPHP\Bundle\LocationBundle\Domain\Currency\CurrencyRepository;
use FlexPHP\Bundle\LocationBundle\Domain\Currency\Request\DeleteCurrencyRequest;
use FlexPHP\Bundle\LocationBundle\Domain\Currency\Response\DeleteCurrencyResponse;

final class DeleteCurrencyUseCase
{
    private CurrencyRepository $currencyRepository;

    public function __construct(CurrencyRepository $currencyRepository)
    {
        $this->currencyRepository = $currencyRepository;
    }

    public function execute(DeleteCurrencyRequest $request): DeleteCurrencyResponse
    {
        return new DeleteCurrencyResponse($this->currencyRepository->remove($request));
    }
}
