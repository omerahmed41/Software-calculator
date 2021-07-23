<?php

namespace App\Entity;

use App\Repository\EquationRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=EquationRepository::class)
 */
class EquationLog
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\OneToMany(targetEntity=OperationsLog::class, mappedBy="equation")
     */
    private $operation;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $equation;

    public function __construct()
    {
        $this->operation = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return Collection|OperationsLog[]
     */
    public function getOperation(): Collection
    {
        return $this->operation;
    }

    public function addOperation(OperationsLog $operation): self
    {
        if (!$this->operation->contains($operation)) {
            $this->operation[] = $operation;
            $operation->setEquation($this);
        }

        return $this;
    }

    public function removeOperation(OperationsLog $operation): self
    {
        if ($this->operation->removeElement($operation)) {
            // set the owning side to null (unless already changed)
            if ($operation->getEquation() === $this) {
                $operation->setEquation(null);
            }
        }

        return $this;
    }

    public function getEquation(): ?string
    {
        return $this->equation;
    }

    public function setEquation(string $equation): self
    {
        $this->equation = $equation;

        return $this;
    }
}
