<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Entity\BehavioursTraits;

/**
 *
 * @author symio
 */
trait CodeAble {
    
    /**
     * @ORM\Column(type="string", length=255)
     */
    private $code;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $label;

    public function getCode(): ?string {
        return $this->code;
    }

    public function setCode(string $code): self {
        $this->code = $code;

        return $this;
    }

    public function getLabel(): ?string {
        return $this->label;
    }

    public function setLabel(string $label): self {
        $this->label = $label;

        return $this;
    }
    
}
