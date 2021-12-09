<?php declare(strict_types=1);

namespace FlexPHP\Bundle\LocationBundle\Domain\Currency\Response;

use FlexPHP\Messages\ResponseInterface;

final class FindCurrencyUserResponse implements ResponseInterface
{
    public $users;

    public function __construct(array $users)
    {
        $this->users = $users;
    }
}
