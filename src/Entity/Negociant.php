<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Negociant
 * @ORM\Entity(repositoryClass="App\Repository\NegociantRepository")
 * @ORM\Table(name="negociant")
 * @ORM\Entity
 */
class Negociant
{
    /**
     * @var int
     *
     * @ORM\Column(name="nonegociant", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $nonegociant;

    /**
     * @var string
     *
     * @ORM\Column(name="nomnegociant", type="string", length=50, nullable=false)
     */
    private $nomnegociant;

    /**
     * @var string|null
     *
     * @ORM\Column(name="adrnegociant", type="text", length=65535, nullable=true)
     */
    private $adrnegociant;

    public function getNonegociant(): ?int
    {
        return $this->nonegociant;
    }

    public function getNomnegociant(): ?string
    {
        return $this->nomnegociant;
    }

    public function setNomnegociant(string $nomnegociant): self
    {
        $this->nomnegociant = $nomnegociant;

        return $this;
    }

    public function getAdrnegociant(): ?string
    {
        return $this->adrnegociant;
    }

    public function setAdrnegociant(?string $adrnegociant): self
    {
        $this->adrnegociant = $adrnegociant;

        return $this;
    }


}
