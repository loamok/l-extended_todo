<?php
namespace App\DTO\BehavioursTraits;

/**
 *
 * @author symio
 */
trait Blameable {
    
    /**
     * @var string
     */
    public $createdBy;
    
    /**
     * @var string
     */
    public $updatedBy;
    
    public function fromBlameableEntity($entity) {
        $this->createdBy = $entity->getCreatedBy();
        $this->updatedBy = $entity->getUpdatedBy();
    }
}
