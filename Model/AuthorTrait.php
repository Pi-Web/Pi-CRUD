<?php

declare(strict_types=1);

namespace PiWeb\PiCRUD\Model;

use DateTime;
use DateTimeInterface;
use Doctrine\ORM\Mapping as ORM;
use PiWeb\PiCRUD\Annotation as PiCRUD;
use Symfony\Component\Security\Core\User\UserInterface;

Trait AuthorTrait
{
    /**
     * @PiCRUD\Property(
     *      label="Créé par",
     *      admin={"class": "d-none d-lg-table-cell"}
     * )
     */
    #[ORM\ManyToOne(targetEntity: 'App\Entity\User')]
    #[ORM\JoinColumn(nullable: true)]
    protected ?UserInterface $createBy = null;

    /**
     * @PiCRUD\Property(
     *      label="Créé le",
     *      type="datetime",
     *      admin={"class": "d-none d-lg-table-cell", "options": {"date": "short", "time": "short"}}
     * )
     */
    #[ORM\Column(type: 'datetime', nullable: true)]
    protected ?DateTimeInterface $createAt = null;

    /**
     * @PiCRUD\Property(
     *      label="Modifié par",
     *      admin={"class": "d-none d-lg-table-cell"}
     * )
     */
    #[ORM\ManyToOne(targetEntity: 'App\Entity\User')]
    #[ORM\JoinColumn(nullable: true)]
    protected ?UserInterface $updateBy = null;

    /**
     * @PiCRUD\Property(
     *      label="Modifié le",
     *      type="datetime",
     *      admin={"class": "d-none d-lg-table-cell", "options": {"date": "short", "time": "short"}}
     * )
     */
    #[ORM\Column(type: 'datetime', nullable: true)]
    protected ?DateTimeInterface $updateAt = null;

    public function getCreateBy(): ?UserInterface
    {
        return $this->createBy;
    }

    public function setCreateBy(?UserInterface $user): self
    {
        $this->createBy = $user;

        return $this;
    }

    public function getCreateAt(): ?DateTimeInterface
    {
        return $this->createAt;
    }

    public function setCreateAt(): self
    {
        $this->createAt = new DateTime();

        return $this;
    }

    public function getUpdateAt(): ?DateTimeInterface
    {
        return $this->updateAt;
    }

    #[ORM\PrePersist]
    #[ORM\PreUpdate]
    public function setUpdateAt(): self
    {
        $this->updateAt = new DateTime();

        return $this;
    }

    public function getUpdateBy(): ?UserInterface
    {
        return $this->updateBy;
    }

    public function setUpdateBy(?UserInterface $user): self
    {
        $this->updateBy = $user;

        return $this;
    }
}
