<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Contrat
 * @ORM\Entity(repositoryClass="App\Repository\ContratRepository")
 * @ORM\Table(name="contrat", indexes={@ORM\Index(name="idx_contrat_negociant", columns={"nonegociant"}), @ORM\Index(name="idx_contrat_cereale", columns={"codecereale"})})
 * @ORM\Entity
 */
class Contrat
{
    /**
     * @var int
     *
     * @ORM\Column(name="nocontrat", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $nocontrat;


    /**
     * @var \Date
     *
     * @Assert\Date(message = "La date n'est pas valide.")
     * @Assert\NotBlank(message ="La date doit être saisie")
     * @ORM\Column(name="datecontrat", type="datetime", nullable=false, options={"default"="CURRENT_TIMESTAMP"})
     */
    private $datecontrat = 'CURRENT_TIMESTAMP';

    /**
     * @var int
     *
     * @Assert\NotBlank(message ="La quantité doit être saisie")
     * @Assert\Type(
     *     type="integer",
     *     message="Nombre entier attendu."
     * )
     * @ORM\Column(name="qtecde", type="integer", nullable=false)
     */
    private $qtecde;

    /**
     * @var float
     *
     * @Assert\NotBlank(message ="Le prix doit être saisi")
     * @ORM\Column(name="prixcontrat", type="decimal", precision=7, scale=2, nullable=false)
     */
    private $prixcontrat;

    /**
     * @var string|null
     *
     * @ORM\Column(name="etatcontrat", type="string", length=1, nullable=true, options={"fixed"=true})
     */
    private $etatcontrat;

    /**
     * @var \Cereale
     *
     * @ORM\ManyToOne(targetEntity="Cereale")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="codecereale", referencedColumnName="codecereale")
     * })
     */
    private $cereale;

    /**
     * @var \Negociant
     *
     * @ORM\ManyToOne(targetEntity="Negociant")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="nonegociant", referencedColumnName="nonegociant")
     * })
     */
    private $negociant;


    /**
     * attribut non mappé
     * Quantité totale livrée du contrat
     * @var int
     * @Assert\Type(
     *     type="integer",
     *      message="Nombre entier attendu"
     * )
     */
    private $qteTotLiv = 0;

    public function getNocontrat(): ?int
    {
        return $this->nocontrat;
    }

    public function getDatecontrat(): ?\DateTimeInterface
    {
        return $this->datecontrat;
    }

    public function setDatecontrat($datecontrat): self
    {
        $this->datecontrat = $datecontrat;

        return $this;
    }

    public function getQtecde(): ?int
    {
        return $this->qtecde;
    }

    public function setQtecde(int $qtecde): self
    {
        $this->qtecde = $qtecde;

        return $this;
    }

    public function getPrixcontrat()
    {
        return $this->prixcontrat;
    }

    public function setPrixcontrat($prixcontrat): self
    {
        $this->prixcontrat = $prixcontrat;

        return $this;
    }

    public function getEtatcontrat(): ?string
    {
        return $this->etatcontrat;
    }

    public function setEtatcontrat(?string $etatcontrat): self
    {
        $this->etatcontrat = $etatcontrat;

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

    public function getNegociant(): ?Negociant
    {
        return $this->negociant;
    }

    public function setNegociant(?Negociant $negociant): self
    {
        $this->negociant = $negociant;

        return $this;
    }

    public function getQteTotLiv(): ?int
    {
        return $this->qteTotLiv;
    }

    public function setQteTotLiv(int $qteTotLiv): self
    {
        $this->qteTotLiv = $qteTotLiv;

        return $this;
    }
}
