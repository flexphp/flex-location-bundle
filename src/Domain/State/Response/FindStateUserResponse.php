<?php declare(strict_types=1);

namespace FlexPHP\Bundle\LocationBundle\Domain\State\Response;

use FlexPHP\Messages\ResponseInterface;

final class FindStateUserResponse implements ResponseInterface
{
    public $users;

    public function __construct(array $users)
    {
        $this->users = $users;
    }
}
