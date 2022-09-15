<?php

namespace App\Entity;

use App\Repository\RuleRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: RuleRepository::class)]
class Rule
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 255)]
    private $name;

    #[ORM\Column(type: 'string', length: 255)]
    private $Type;

    #[ORM\Column(type: 'boolean')]
    private $mandatory;

    #[ORM\ManyToOne(targetEntity: Folder::class, inversedBy: 'Rule')]
    private $idFolder;

    #[ORM\OneToMany(mappedBy: 'idRule', targetEntity: Indices::class)]
    private $indices;

    public function __construct()
    {
        $this->indices = new ArrayCollection();
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

    public function getType(): ?string
    {
        return $this->Type;
    }

    public function setType(string $Type): self
    {
        $this->Type = $Type;

        return $this;
    }

    public function isMandatory(): ?bool
    {
        return $this->mandatory;
    }

    public function setMandatory(bool $mandatory): self
    {
        $this->mandatory = $mandatory;

        return $this;
    }

    public function getIdFolder(): ?folder
    {
        return $this->idFolder;
    }

    public function setIdFolder(?folder $idFolder): self
    {
        $this->idFolder = $idFolder;

        return $this;
    }

    /**
     * @return Collection<int, Indices>
     */
    public function getIndices(): Collection
    {
        return $this->indices;
    }

    public function addIndices(Indices $indices): self
    {
        if (!$this->indices->contains($indices)) {
            $this->indices[] = $indices;
            $indices->setIdRule($this);
        }

        return $this;
    }

    public function removeIndices(Indices $indices): self
    {
        if ($this->indices->removeElement($indices)) {
            // set the owning side to null (unless already changed)
            if ($indices->getIdRule() === $this) {
                $indices->setIdRule(null);
            }
        }

        return $this;
    }
}
