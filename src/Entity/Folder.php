<?php

namespace App\Entity;

use App\Repository\FolderRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: FolderRepository::class)]
class Folder
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 255)]
    private $name;

    #[ORM\ManyToOne(targetEntity: Organization::class, inversedBy: 'rules')]
    private $idOrganization;

    #[ORM\OneToMany(mappedBy: 'idFolder', targetEntity: Rule::class)]
    private $rules;

    #[ORM\OneToMany(mappedBy: 'idFolder', targetEntity: Document::class)]
    private $Document;

    public function __construct()
    {
        $this->rules = new ArrayCollection();
        $this->Document = new ArrayCollection();
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

    public function getIdOrganization(): ?Organization
    {
        return $this->idOrganization;
    }

    public function setIdOrganization(?Organization $idOrganization): self
    {
        $this->idOrganization = $idOrganization;

        return $this;
    }

    /**
     * @return Collection<int, Rules>
     */
    public function getRules(): Collection
    {
        return $this->rules;
    }

    public function addRule(Rule $rule): self
    {
        if (!$this->rules->contains($rule)) {
            $this->rules[] = $rule;
            $rule->setIdFolder($this);
        }

        return $this;
    }

    public function removeRule(Rule $rule): self
    {
        if ($this->rules->removeElement($rule)) {
            // set the owning side to null (unless already changed)
            if ($rule->getIdFolder() === $this) {
                $rule->setIdFolder(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Document>
     */
    public function getDocument(): Collection
    {
        return $this->Document;
    }

    public function addDocument(Document $document): self
    {
        if (!$this->Document->contains($document)) {
            $this->Document[] = $document;
            $document->setIdFolder($this);
        }

        return $this;
    }

    public function removeDocument(Document $document): self
    {
        if ($this->Document->removeElement($document)) {
            // set the owning side to null (unless already changed)
            if ($document->getIdFolder() === $this) {
                $document->setIdFolder(null);
            }
        }

        return $this;
    }
}
