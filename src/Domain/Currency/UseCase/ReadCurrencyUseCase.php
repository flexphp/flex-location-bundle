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
use FlexPHP\Bundle\LocationBundle\Domain\Currency\Request\ReadCurrencyRequest;
use FlexPHP\Bundle\LocationBundle\Domain\Currency\Response\ReadCurrencyResponse;

final class ReadCurrencyUseCase
{
    private CurrencyRepository $currencyRepository;

    public function __construct(CurrencyRepository $currencyRepository)
    {
        $this->currencyRepository = $currencyRepository;
    }

    public function execute(ReadCurrencyRequest $request): ReadCurrencyResponse
    {
        $currency = $this->currencyRepository->getById($request);

        return new ReadCurrencyResponse($currency);
    }
}
