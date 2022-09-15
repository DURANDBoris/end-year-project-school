<?php

namespace App\Entity;

use App\Repository\OrganizationRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: OrganizationRepository::class)]
class Organization
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 255)]
    private $name;

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'Organization')]
    private $owner;

    #[ORM\OneToMany(mappedBy: 'idOrganization', targetEntity: UserInOrganization::class)]
    private $userInOrganization;

    #[ORM\OneToMany(mappedBy: 'idOrganization', targetEntity: Folder::class)]
    private $rules;

    public function __construct()
    {
        $this->userInOrganization = new ArrayCollection();
        $this->rules = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getOwner(): ?User
    {
        return $this->owner;
    }

    public function setOwner(?User $owner): self
    {
        $this->owner = $owner;

        return $this;
    }

    /**
     * @return Collection<int, UserInOrganization>
     */
    public function getUserInOrganization(): Collection
    {
        return $this->userInOrganization;
    }

    public function addUserInOrganization(UserInOrganization $userInOrganization): self
    {
        if (!$this->userInOrganization->contains($userInOrganization)) {
            $this->userInOrganization[] = $userInOrganization;
            $userInOrganization->setIdOrganization($this);
        }

        return $this;
    }

    public function removeUserInOrganization(UserInOrganization $userInOrganization): self
    {
        if ($this->userInOrganization->removeElement($userInOrganization)) {
            // set the owning side to null (unless already changed)
            if ($userInOrganization->getIdOrganization() === $this) {
                $userInOrganization->setIdOrganization(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Folder>
     */
    public function getRules(): Collection
    {
        return $this->rules;
    }

    public function addRule(Folder $rule): self
    {
        if (!$this->rules->contains($rule)) {
            $this->rules[] = $rule;
            $rule->setIdOrganization($this);
        }

        return $this;
    }

    public function removeRule(Folder $rule): self
    {
        if ($this->rules->removeElement($rule)) {
            // set the owning side to null (unless already changed)
            if ($rule->getIdOrganization() === $this) {
                $rule->setIdOrganization(null);
            }
        }

        return $this;
    }
}
