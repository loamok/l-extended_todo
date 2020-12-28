<?php

namespace App\Entity\BehavioursTraits;

use Symfony\Component\Serializer\Annotation\Groups;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 *
 * @author symio
 */
trait CodeAble {
    
    /**
     * @var string Code of the resource
     * 
     * @ORM\Column(type="string", length=255)
     * @Groups({"read", "write"})
     */
    private $code;

    /**
     * @var string Display label of the resource
     * 
     * @ORM\Column(type="string", length=255)
     * @Groups({"read", "write"})
     */
    private $label;

    /**
     * 
     * @return string|null
     */
    public function getCode(): ?string {
        return $this->code;
    }

    /**
     * 
     * @param string $code
     * @return self
     */
    public function setCode(string $code): self {
        $this->code = $code;

        return $this;
    }

    /**
     * 
     * @return string|null
     */
    public function getLabel(): ?string {
        return $this->label;
    }

    /**
     * 
     * @param string $label
     * @return self
     */
    public function setLabel(string $label): self {
        $this->label = $label;

        return $this;
    }
    
}
