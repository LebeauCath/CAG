<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Silo
 * @ORM\Entity(repositoryClass="App\Repository\SiloRepository")
 * @ORM\Table(name="silo", indexes={@ORM\Index(name="idx_silo_cereale", columns={"codecereale"})})
 * @ORM\Entity
 */
class Silo
{
    /**
     * @var string
     *
     * @ORM\Column(name="codesilo", type="string", length=1, nullable=false, options={"fixed"=true})
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="NONE")
     */
    private $codesilo;

    /**
     * @var int
     *
     * @ORM\Column(name="qtestock", type="integer", nullable=false)
     */
    private $qtestock;

    /**
     * @var int|null
     *
     * @ORM\Column(name="capacite", type="integer", nullable=true)
     */
    private $capacite;

    /**
     * @var \Cereale
     *
     * @ORM\ManyToOne(targetEntity="Cereale")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="codecereale", referencedColumnName="codecereale")
     * })
     */
    private $cereale;

    public function getCodesilo(): ?string
    {
        return $this->codesilo;
    }

    public function getQtestock(): ?int
    {
        return $this->qtestock;
    }

    public function setQtestock(int $qtestock): self
    {
        $this->qtestock = $qtestock;

        return $this;
    }

    public function getCapacite(): ?int
    {
        return $this->capacite;
    }

    public function setCapacite(?int $capacite): self
    {
        $this->capacite = $capacite;

        return $this;
    }

    public function getCereale(): ?Cereale
    {
        return $this->cereale;
    }

    public function setCereale(?Cereale $cereale): self
    {
        $this->cereale = $cereale;

        return $this;
    }
}
