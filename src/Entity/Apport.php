<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Apport
 *
 * @ORM\Table(name="apport", indexes={@ORM\Index(name="idx_fk_apport_cereale", columns={"codecereale"}), @ORM\Index(name="idx_fk_apport_silo", columns={"codesilo"}), @ORM\Index(name="idx_fk_apport_fournisseur", columns={"nofourn"}), @ORM\Index(name="idx_fk_apport_qualite", columns={"qualite"}), @ORM\Index(name="idx_fk_apport_virement", columns={"idreleve"})})
 * @ORM\Entity
 */
class Apport
{
    /**
     * @var int
     *
     * @ORM\Column(name="noapport", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $noapport;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="dateapport", type="datetime", nullable=false, options={"default"="CURRENT_TIMESTAMP"})
     */
    private $dateapport = 'CURRENT_TIMESTAMP';

    /**
     * @var int
     *
     * @ORM\Column(name="qteapport", type="integer", nullable=false)
     */
    private $qteapport;

    /**
     * @var string
     *
     * @ORM\Column(name="prixachateff", type="decimal", precision=7, scale=2, nullable=false)
     */
    private $prixachateff;

    /**
     * @var int|null
     *
     * @ORM\Column(name="idreleve", type="integer", nullable=true)
     */
    private $idreleve;

    /**
     * @var \Cereale
     *
     * @ORM\ManyToOne(targetEntity="Cereale")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="codecereale", referencedColumnName="codecereale")
     * })
     */
    private $codecereale;

    /**
     * @var \Fournisseur
     *
     * @ORM\ManyToOne(targetEntity="Fournisseur")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="nofourn", referencedColumnName="nofourn")
     * })
     */
    private $nofourn;

    /**
     * @var \CoeffQualite
     *
     * @ORM\ManyToOne(targetEntity="CoeffQualite")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="qualite", referencedColumnName="qualite")
     * })
     */
    private $qualite;

    /**
     * @var \Silo
     *
     * @ORM\ManyToOne(targetEntity="Silo")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="codesilo", referencedColumnName="codesilo")
     * })
     */
    private $codesilo;

    public function getNoapport(): ?int
    {
        return $this->noapport;
    }

    public function getDateapport(): ?\DateTimeInterface
    {
        return $this->dateapport;
    }

    public function setDateapport(\DateTimeInterface $dateapport): self
    {
        $this->dateapport = $dateapport;

        return $this;
    }

    public function getQteapport(): ?int
    {
        return $this->qteapport;
    }

    public function setQteapport(int $qteapport): self
    {
        $this->qteapport = $qteapport;

        return $this;
    }

    public function getPrixachateff()
    {
        return $this->prixachateff;
    }

    public function setPrixachateff($prixachateff): self
    {
        $this->prixachateff = $prixachateff;

        return $this;
    }

    public function getIdreleve(): ?int
    {
        return $this->idreleve;
    }

    public function setIdreleve(?int $idreleve): self
    {
        $this->idreleve = $idreleve;

        return $this;
    }

    public function getCodecereale(): ?Cereale
    {
        return $this->codecereale;
    }

    public function setCodecereale(?Cereale $codecereale): self
    {
        $this->codecereale = $codecereale;

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

    public function getQualite(): ?CoeffQualite
    {
        return $this->qualite;
    }

    public function setQualite(?CoeffQualite $qualite): self
    {
        $this->qualite = $qualite;

        return $this;
    }

    public function getCodesilo(): ?Silo
    {
        return $this->codesilo;
    }

    public function setCodesilo(?Silo $codesilo): self
    {
        $this->codesilo = $codesilo;

        return $this;
    }


}
