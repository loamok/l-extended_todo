<?php

namespace App\Entity\BehavioursTraits;

use ApiPlatform\Core\Annotation\ApiSubresource;

use Doctrine\ORM\Mapping as ORM;

/**
 *
 * @author symio
 */
trait Relatable {
    
    protected $parentType;
    
    protected function initRelatable() {
        $this->relateds = new ArrayCollection();
        if(is_a($this, Event::class)) {
            $this->parentType = ucfirst('event');
        } elseif(is_a($this, Todo::class)) {
            $this->parentType = ucfirst('todo');
        } elseif(is_a($this, Journal::class)) {
            $this->parentType = ucfirst('journal');
        } elseif(is_a($this, Freebusy::class)) {
            $this->parentType = ucfirst('freebusy');
        }
    }
    
    /**
     * @return Collection|Related[]
     */
    public function getRelateds() {
        return $this->relateds;
    }

    public function addRelated(Related $related): self {
        if (!$this->relateds->contains($related)) {
            $this->relateds[] = $related;
            $related->setParent($this);
        }

        return $this;
    }

    public function removeRelated(Related $related): self {
        if ($this->relateds->removeElement($related)) {
            // set the owning side to null (unless already changed)
            if ($related->getParent($this->parentType) === $this) {
                $related->setParent(null, $this->parentType);
            }
        }

        return $this;
    }
    
}
