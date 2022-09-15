<?php

namespace App\Entity;

use App\Repository\UserInOrganizationRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: UserInOrganizationRepository::class)]
class UserInOrganization
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 255)]
    private $roles;

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'userInOrganization')]
    private $idUser;

    #[ORM\ManyToOne(targetEntity: Organization::class, inversedBy: 'userInOrganization')]
    private $idOrganization;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getRoles(): ?string
    {
        return $this->roles;
    }

    public function setRoles(string $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    public function getIdUser(): ?User
    {
        return $this->idUser;
    }

    public function setIdUser(?User $idUser): self
    {
        $this->idUser = $idUser;

        return $this;
    }

    public function getIdOrganization(): ?Organization
    {
        return $this->idOrganization;
    }

    public function setIdOrganization(?Organization $idOrganization): self
    {
        $this->idOrganization = $idOrganization;

        return $this;
    }
}
