<?php

namespace App\Entity;

use App\Repository\OprationsLogRepository;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\SoftDeleteable\Traits\SoftDeleteableEntity;
use Gedmo\Timestampable\Traits\TimestampableEntity;

/**
 * @ORM\Entity(repositoryClass=OprationsLogRepository::class)
 */
class OperationsLog
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
     * @ORM\Column(type="string", length=32)
     */
    private $operationType;

    /**
     * @ORM\ManyToOne(targetEntity=EquationLog::class, inversedBy="operation")
     */
    private $equation;



    public function getId(): ?int
    {
        return $this->id;
    }

    public function getOperationType(): ?string
    {
        return $this->operationType;
    }

    public function setOperationType(string $operationType): self
    {
        $this->operationType = $operationType;

        return $this;
    }

    public function getEquation(): ?string
    {
        return $this->equation->getEquation();
    }

    public function setEquation(?EquationLog $equation): self
    {
        $this->equation = $equation;

        return $this;
    }

    /**
     * @return string|null
     */
    public function __toString()
    {
        return $this->getOperationType();
    }
}
