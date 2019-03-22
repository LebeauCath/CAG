<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Fournisseur
 *
 * @ORM\Table(name="fournisseur")
 * @ORM\Entity
 */
class Fournisseur
{
    /**
     * @var int
     *
     * @ORM\Column(name="nofourn", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $nofourn;

    /**
     * @var string
     *
     * @ORM\Column(name="nomfourn", type="string", length=50, nullable=false)
     */
    private $nomfourn;

    /**
     * @var string|null
     *
     * @ORM\Column(name="adrfourn", type="text", length=65535, nullable=true)
     */
    private $adrfourn;

    public function getNofourn(): ?int
    {
        return $this->nofourn;
    }

    public function getNomfourn(): ?string
    {
        return $this->nomfourn;
    }

    public function setNomfourn(string $nomfourn): self
    {
        $this->nomfourn = $nomfourn;

        return $this;
    }

    public function getAdrfourn(): ?string
    {
        return $this->adrfourn;
    }

    public function setAdrfourn(?string $adrfourn): self
    {
        $this->adrfourn = $adrfourn;

        return $this;
    }


}
