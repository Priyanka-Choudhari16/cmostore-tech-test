<?php

namespace App\Entity;

use App\Repository\CalculationRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CalculationRepository::class)]
class Calculation
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?float $exVatValue = null;

    #[ORM\Column]
    private ?float $incVatvalue = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $createdAt = null;

    #[ORM\Column]
    private ?float $originalValue = null;

    #[ORM\Column]
    private ?float $vatRate = null;

    #[ORM\Column]
    private ?float $exVatTotalAmount = null;

    #[ORM\Column]
    private ?float $incVatTotalAmount = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getExVatValue(): ?float
    {
        return $this->exVatValue;
    }

    public function setExVatValue(float $exVatValue): static
    {
        $this->exVatValue = $exVatValue;

        return $this;
    }

    public function getIncVatvalue(): ?float
    {
        return $this->incVatvalue;
    }

    public function setIncVatvalue(float $incVatvalue): static
    {
        $this->incVatvalue = $incVatvalue;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): static
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getOriginalValue(): ?float
    {
        return $this->originalValue;
    }

    public function setOriginalValue(float $originalValue): static
    {
        $this->originalValue = $originalValue;

        return $this;
    }

    public function getVatRate(): ?float
    {
        return $this->vatRate;
    }

    public function setVatRate(float $vatRate): static
    {
        $this->vatRate = $vatRate;

        return $this;
    }

    public function getExVatTotalAmount(): ?float
    {
        return $this->exVatTotalAmount;
    }

    public function setExVatTotalAmount(float $exVatTotalAmount): static
    {
        $this->exVatTotalAmount = $exVatTotalAmount;

        return $this;
    }

    public function getIncVatTotalAmount(): ?float
    {
        return $this->incVatTotalAmount;
    }

    public function setIncVatTotalAmount(float $incVatTotalAmount): static
    {
        $this->incVatTotalAmount = $incVatTotalAmount;

        return $this;
    }
}
