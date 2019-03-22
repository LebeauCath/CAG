<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * CoeffQualite
 *
 * @ORM\Table(name="coeff_qualite")
 * @ORM\Entity
 */
class CoeffQualite
{
    /**
     * @var bool
     *
     * @ORM\Column(name="qualite", type="boolean", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $qualite;

    /**
     * @var string
     *
     * @ORM\Column(name="coefficient", type="decimal", precision=3, scale=2, nullable=false)
     */
    private $coefficient;

    public function getQualite(): ?bool
    {
        return $this->qualite;
    }

    public function getCoefficient()
    {
        return $this->coefficient;
    }

    public function setCoefficient($coefficient): self
    {
        $this->coefficient = $coefficient;

        return $this;
    }


}
