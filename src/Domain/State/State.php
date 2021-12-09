<?php declare(strict_types=1);
/*
 * This file is part of FlexPHP.
 *
 * (c) Freddie Gar <freddie.gar@outlook.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace FlexPHP\Bundle\LocationBundle\Domain\State;

use FlexPHP\Bundle\LocationBundle\Domain\Country\Country;
use FlexPHP\Bundle\HelperBundle\Domain\Helper\ToArrayTrait;
use FlexPHP\Bundle\UserBundle\Domain\User\User;

final class State
{
    use ToArrayTrait;

    private $id;

    private $countryId;

    private $name;

    private $code;

    private $createdAt;

    private $updatedAt;

    private $createdBy;

    private $updatedBy;

    private $countryIdInstance;

    private $createdByInstance;

    private $updatedByInstance;

    public function id(): ?int
    {
        return $this->id;
    }

    public function countryId(): ?int
    {
        return $this->countryId;
    }

    public function name(): ?string
    {
        return $this->name;
    }

    public function code(): ?string
    {
        return $this->code;
    }

    public function createdAt(): ?\DateTime
    {
        return $this->createdAt;
    }

    public function updatedAt(): ?\DateTime
    {
        return $this->updatedAt;
    }

    public function createdBy(): ?int
    {
        return $this->createdBy;
    }

    public function updatedBy(): ?int
    {
        return $this->updatedBy;
    }

    public function countryIdInstance(): Country
    {
        return $this->countryIdInstance ?: new Country;
    }

    public function createdByInstance(): ?User
    {
        return $this->createdByInstance;
    }

    public function updatedByInstance(): ?User
    {
        return $this->updatedByInstance;
    }

    public function setId(int $id): void
    {
        $this->id = $id;
    }

    public function setCountryId(int $countryId): void
    {
        $this->countryId = $countryId;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function setCode(string $code): void
    {
        $this->code = $code;
    }

    public function setCreatedAt(?\DateTime $createdAt): void
    {
        $this->createdAt = $createdAt;
    }

    public function setUpdatedAt(?\DateTime $updatedAt): void
    {
        $this->updatedAt = $updatedAt;
    }

    public function setCreatedBy(?int $createdBy): void
    {
        $this->createdBy = $createdBy;
    }

    public function setUpdatedBy(?int $updatedBy): void
    {
        $this->updatedBy = $updatedBy;
    }

    public function setCountryIdInstance(Country $country): void
    {
        $this->countryIdInstance = $country;
    }

    public function setCreatedByInstance(?User $user): void
    {
        $this->createdByInstance = $user;
    }

    public function setUpdatedByInstance(?User $user): void
    {
        $this->updatedByInstance = $user;
    }
}
