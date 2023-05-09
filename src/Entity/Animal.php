<?php

namespace App\Entity;

use App\Repository\AnimalRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: AnimalRepository::class)]
class Animal
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $codigo = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 6, scale: 3)]
    private ?string $leite = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 6, scale: 3)]
    private ?string $racao = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 6, scale: 3)]
    private ?string $peso = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $nascimento = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCodigo(): ?string
    {
        return $this->codigo;
    }

    public function setCodigo(string $codigo): self
    {
        $this->codigo = $codigo;

        return $this;
    }

    public function getLeite(): ?string
    {
        return $this->leite;
    }

    public function setLeite(string $leite): self
    {
        $this->leite = $leite;

        return $this;
    }

    public function getRacao(): ?string
    {
        return $this->racao;
    }

    public function setRacao(string $racao): self
    {
        $this->racao = $racao;

        return $this;
    }

    public function getPeso(): ?string
    {
        return $this->peso;
    }

    public function setPeso(string $peso): self
    {
        $this->peso = $peso;

        return $this;
    }

    public function getNascimento(): ?\DateTimeInterface
    {
        return $this->nascimento;
    }

    public function setNascimento(\DateTimeInterface $nascimento): self
    {
        $this->nascimento = $nascimento;

        return $this;
    }
}
