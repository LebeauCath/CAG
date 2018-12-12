<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Cereale
 * @ORM\Entity(repositoryClass="App\Repository\CerealeRepository")
 * @ORM\Table(name="cereale")
 * @ORM\Entity
 */
class Cereale
{
    /**
     * @var string
     *
     * @ORM\Column(name="codecereale", type="string", length=5, nullable=false, options={"fixed"=true})
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="NONE")
     */
    private $codecereale;

    /**
     * @var string
     *
     * @ORM\Column(name="variete", type="string", length=30, nullable=false)
     */
    private $variete;

    /**
     * @var float|null
     *
     * @ORM\Column(name="prixachatref", type="decimal", precision=7, scale=2, nullable=true)
     */
    private $prixachatref;

    /**
     * @var float|null
     *
     * @ORM\Column(name="prixvente", type="decimal", precision=7, scale=2, nullable=true)
     */
    private $prixvente;

    public function getCodecereale(): ?string
    {
        return $this->codecereale;
    }

    public function getVariete(): ?string
    {
        return $this->variete;
    }

    public function setVariete(string $variete): self
    {
        $this->variete = $variete;

        return $this;
    }

    public function getPrixachatref()
    {
        return $this->prixachatref;
    }

    public function setPrixachatref($prixachatref): self
    {
        $this->prixachatref = $prixachatref;

        return $this;
    }

    public function getPrixvente()
    {
        return $this->prixvente;
    }

    public function setPrixvente($prixvente): self
    {
        $this->prixvente = $prixvente;

        return $this;
    }


}
