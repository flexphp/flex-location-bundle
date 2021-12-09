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

final class UpdateStateRequest implements RequestInterface
{
    public $id;

    public $countryId;

    public $name;

    public $code;

    public $updatedBy;

    public function __construct(int $id, array $data, int $updatedBy)
    {
        $this->id = $id;
        $this->countryId = $data['countryId'] ?? null;
        $this->name = $data['name'] ?? null;
        $this->code = $data['code'] ?? null;
        $this->updatedBy = $updatedBy;
    }
}
