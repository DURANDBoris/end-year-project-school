<?php

namespace App\Entity;

use App\Repository\IndicesRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: IndicesRepository::class)]
#[ORM\Table(name: 'Indices')]
class Indices
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 255)]
    private $value;

    #[ORM\ManyToOne(targetEntity: Rule::class, inversedBy: 'indices')]
    private $idRule;

    #[ORM\ManyToOne(targetEntity: Document::class, inversedBy: 'indices')]
    private $idDocument;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getValue(): ?string
    {
        return $this->value;
    }

    public function setValue(string $value): self
    {
        $this->value = $value;

        return $this;
    }

    public function getIdRule(): ?Rule
    {
        return $this->idRule;
    }

    public function setIdRule(?Rule $idRule): self
    {
        $this->idRule = $idRule;

        return $this;
    }

    public function getIdDocument(): ?Document
    {
        return $this->idDocument;
    }

    public function setIdDocument(?Document $idDocument): self
    {
        $this->idDocument = $idDocument;

        return $this;
    }

    public function toString() {
        return array(
            "id" => $this->getId(),
            "rule" => $this->getIdRule()->toString(),
            "document" => $this->getIdDocument()->toString(),
            "value" => $this->getValue()
        );
    }
}
