<?php

namespace App\Entity\BehavioursTraits;

/**
 *
 * @author symio
 */
trait CodeAble {
    
    /**
     * @var string Code of the resource
     * 
     * @ORM\Column(type="string", length=255)
     */
    private $code;

    /**
     * @var string Display label of the resource
     * 
     * @ORM\Column(type="string", length=255)
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
