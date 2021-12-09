<?php declare(strict_types=1);

namespace FlexPHP\Bundle\LocationBundle\Domain\City\Response;

use FlexPHP\Messages\ResponseInterface;

final class FindCityUserResponse implements ResponseInterface
{
    public $users;

    public function __construct(array $users)
    {
        $this->users = $users;
    }
}
