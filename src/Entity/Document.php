<?php

namespace App\Entity;

use App\Repository\DocumentRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: DocumentRepository::class)]
class Document
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 255)]
    private $name;

    #[ORM\Column(type: 'string', length: 255)]
    private $type;

    #[ORM\Column(type: 'string', length: 255)]
    private $size;

    #[ORM\Column(type: 'string', length: 255)]
    private $path;

    #[ORM\Column(type: 'boolean')]
    private $toIndex;

    #[ORM\ManyToOne(targetEntity: Folder::class, inversedBy: 'Document')]
    private $idFolder;

    #[ORM\OneToMany(mappedBy: 'idDocument', targetEntity: Indices::class)]
    private $indices;

    #[ORM\OneToMany(mappedBy: 'idDocument', targetEntity: History::class)]
    private $histories;

    public function __construct()
    {
        $this->indices = new ArrayCollection();
        $this->histories = new ArrayCollection();
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
        return $this->type;
    }

    public function setType(string $type): self
    {
        $this->type = $type;

        return $this;
    }

    public function getSize(): ?string
    {
        return $this->size;
    }

    public function setSize(string $size): self
    {
        $this->size = $size;

        return $this;
    }

    public function getPath(): ?string
    {
        return $this->path;
    }

    public function setPath(string $path): self
    {
        $this->path = $path;

        return $this;
    }

    public function isToIndex(): ?bool
    {
        return $this->toIndex;
    }

    public function setToIndex(bool $toIndex): self
    {
        $this->toIndex = $toIndex;

        return $this;
    }

    public function getIdFolder(): ?Folder
    {
        return $this->idFolder;
    }

    public function setIdFolder(?Folder $idFolder): self
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

    public function addIndices(Indices $Indices): self
    {
        if (!$this->indices->contains($Indices)) {
            $this->indices[] = $Indices;
            $Indices->setIdDocument($this);
        }

        return $this;
    }

    public function removeIndices(Indices $Indices): self
    {
        if ($this->indices->removeElement($Indices)) {
            // set the owning side to null (unless already changed)
            if ($Indices->getIdDocument() === $this) {
                $Indices->setIdDocument(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, History>
     */
    public function getHistories(): Collection
    {
        return $this->histories;
    }

    public function addHistory(History $history): self
    {
        if (!$this->histories->contains($history)) {
            $this->histories[] = $history;
            $history->setIdDocument($this);
        }

        return $this;
    }

    public function removeHistory(History $history): self
    {
        if ($this->histories->removeElement($history)) {
            // set the owning side to null (unless already changed)
            if ($history->getIdDocument() === $this) {
                $history->setIdDocument(null);
            }
        }

        return $this;
    }
}
