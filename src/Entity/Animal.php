<?php

namespace App\Entity;

use App\Repository\AnimalRepository;
use DateTime;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints\LessThan;
use Symfony\Component\Validator\Constraints\LessThanOrEqual;
use Symfony\Component\Validator\Constraints\NotBlank;

#[UniqueEntity(fields: ['codigo'], message: 'Esto código ja pertence a algum animal.')]
#[ORM\Entity(repositoryClass: AnimalRepository::class)]
class Animal
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[NotBlank(message: 'Digite o código!')]
    private ?string $codigo = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 6, scale: 3)]
    #[NotBlank(message: 'Digite a quantidade de leite em litros!')]
    private ?string $leite = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 6, scale: 3)]
    #[NotBlank(message: 'Digite a quantidade de ração em quilos!')]
    private ?string $racao = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 6, scale: 3)]
    #[NotBlank(message: 'Digite o peso do animal em quilos!')]
    private ?string $peso = null;

    #[ORM\Column(type: Types::STRING)]
    #[LessThan(['value'=> 'today UTC','message'=>"A data não pode ser futura!"])]
    #[NotBlank(message: 'Digite a data de nascimento do animal!')]
    private ?string $nascimento = null;

    #[ORM\Column(type: Types::BOOLEAN)]
    private ?string $status = null;


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

    public function getNascimento(): ?string
    {
        return $this->nascimento;
    }

    public function setNascimento(string $nascimento): self
    {
        $this->nascimento = $nascimento;

        return $this;
    }

    public function getStatus(): ?bool
    {
        return $this->status;
    }

    public function setStatus(bool $status): self
    {
        $this->status = $status;

        return $this;
    }

    public function atualizaStatus(): self
    {
        $this->status = 0;

        return $this;
    }


    public function getPesoEmArroba(): ?string
    {
        return $this->peso / 15;
    }

    public function getRacaoPorDia(): ?string
    {
        return $this->racao / 7;
    }

    public function getIdade(): ?string
    {
        $date1 = date_create(date('Y-m-d'));
        $interval = $date1->diff(date_create($this->nascimento));
        $idade = '';
        if ($interval->y === 1) {
            $idade .= $interval->y . " ano";
        } else if ($interval->y > 1) {
            $idade .= $interval->y . " anos";
        }
        if ($interval->y !== 0 && $interval->m !== 0) {
            $idade .= " e ";
        }

        if ($interval->m === 1) {
            $idade .= $interval->m . " mês";
        } else if ($interval->m > 1) {
            $idade .= $interval->m . " meses";
        }
        return $idade;
    }
}
