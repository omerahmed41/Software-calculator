<?php

namespace App\Entity;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use Gedmo\SoftDeleteable\Traits\SoftDeleteableEntity;
use App\Repository\EquationRepository;
use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=EquationRepository::class)
 */
class EquationLog
{

    use TimestampableEntity;
    use SoftDeleteableEntity;

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

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $result;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $steps;

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

    /**
     * @return string|null
     */
    public function __toString()
    {
        return $this->getEquation();
    }

    public function getResult(): ?float
    {
        return $this->result;
    }

    public function setResult(?float $result): self
    {
        $this->result = $result;

        return $this;
    }

    public function getSteps(): ?string
    {
        return $this->steps;
    }

    public function setSteps(?string $steps): self
    {
        $this->steps = $steps;

        return $this;
    }

}
