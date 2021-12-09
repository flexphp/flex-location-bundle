<?php declare(strict_types=1);
/*
 * This file is part of FlexPHP.
 *
 * (c) Freddie Gar <freddie.gar@outlook.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace FlexPHP\Bundle\LocationBundle\Domain\State\Request;

use FlexPHP\Messages\RequestInterface;

final class CreateStateRequest implements RequestInterface
{
    public $countryId;

    public $name;

    public $code;

    public $createdBy;

    public function __construct(array $data, int $createdBy)
    {
        $this->countryId = $data['countryId'] ?? null;
        $this->name = $data['name'] ?? null;
        $this->code = $data['code'] ?? null;
        $this->createdBy = $createdBy;
    }
}
