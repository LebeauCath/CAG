<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Livraison
 * @ORM\Entity(repositoryClass="App\Repository\LivraisonRepository")
 * @ORM\Table(name="livraison", indexes={@ORM\Index(name="idx_livraison_contrat", columns={"nocontrat"}), @ORM\Index(name="idx_livraison_date", columns={"dateliv"})})
 * @ORM\Entity
 */
class Livraison
{
    /**
     * @var int
     *
     * @ORM\Column(name="nolivraison", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $nolivraison;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="dateliv", type="datetime", nullable=false)
     */
    private $dateliv;

    /**
     * @var int
     *
     * @ORM\Column(name="qteliv", type="integer", nullable=false)
     */
    private $qteliv;

    /**
     * @var string|null
     *
     * @ORM\Column(name="codesilo", type="string", length=1, nullable=true, options={"fixed"=true})
     */
    private $codesilo;

    /**
     * @var \Contrat
     *
     * @ORM\ManyToOne(targetEntity="Contrat")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="nocontrat", referencedColumnName="nocontrat")
     * })
     */
    private $contrat;

    public function getNolivraison(): ?int
    {
        return $this->nolivraison;
    }

    public function getDateliv(): ?\DateTimeInterface
    {
        return $this->dateliv;
    }

    public function setDateliv(\DateTimeInterface $dateliv): self
    {
        $this->dateliv = $dateliv;

        return $this;
    }

    public function getQteliv(): ?int
    {
        return $this->qteliv;
    }

    public function setQteliv(int $qteliv): self
    {
        $this->qteliv = $qteliv;

        return $this;
    }

    public function getCodesilo(): ?string
    {
        return $this->codesilo;
    }

    public function setCodesilo(?string $codesilo): self
    {
        $this->codesilo = $codesilo;

        return $this;
    }

    public function getContrat(): ?Contrat
    {
        return $this->contrat;
    }

    public function setContrat(?Contrat $contrat): self
    {
        $this->contrat = $contrat;

        return $this;
    }


}
