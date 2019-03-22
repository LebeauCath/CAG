<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Virement
 *
 * @ORM\Table(name="virement", indexes={@ORM\Index(name="idx_fk_virement_fournisseur", columns={"nofourn"})})
 * @ORM\Entity
 */
class Virement
{
    /**
     * @var int
     *
     * @ORM\Column(name="idreleve", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idreleve;

    /**
     * @var int
     *
     * @ORM\Column(name="annee", type="integer", nullable=false)
     */
    private $annee;

    /**
     * @var bool
     *
     * @ORM\Column(name="mois", type="boolean", nullable=false)
     */
    private $mois;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="datevirement", type="date", nullable=false)
     */
    private $datevirement;

    /**
     * @var string
     *
     * @ORM\Column(name="montant", type="decimal", precision=7, scale=2, nullable=false)
     */
    private $montant;

    /**
     * @var \Fournisseur
     *
     * @ORM\ManyToOne(targetEntity="Fournisseur")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="nofourn", referencedColumnName="nofourn")
     * })
     */
    private $nofourn;

    public function getIdreleve(): ?int
    {
        return $this->idreleve;
    }

    public function getAnnee(): ?int
    {
        return $this->annee;
    }

    public function setAnnee(int $annee): self
    {
        $this->annee = $annee;

        return $this;
    }

    public function getMois(): ?bool
    {
        return $this->mois;
    }

    public function setMois(bool $mois): self
    {
        $this->mois = $mois;

        return $this;
    }

    public function getDatevirement(): ?\DateTimeInterface
    {
        return $this->datevirement;
    }

    public function setDatevirement(\DateTimeInterface $datevirement): self
    {
        $this->datevirement = $datevirement;

        return $this;
    }

    public function getMontant()
    {
        return $this->montant;
    }

    public function setMontant($montant): self
    {
        $this->montant = $montant;

        return $this;
    }

    public function getNofourn(): ?Fournisseur
    {
        return $this->nofourn;
    }

    public function setNofourn(?Fournisseur $nofourn): self
    {
        $this->nofourn = $nofourn;

        return $this;
    }


}
