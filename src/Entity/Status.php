<?php

namespace App\Entity;

use App\Repository\StatusRepository;

use ApiPlatform\Core\Annotation\ApiResource;

use App\Entity\BehavioursTraits\CodeAble;
use App\Entity\BehavioursTraits\UuidIdentifiable;

use Doctrine\ORM\Mapping as ORM;

use Symfony\Bridge\Doctrine\IdGenerator\UuidV4Generator;
use Symfony\Component\Uid\AbstractUid;
use Symfony\Component\Uid\UuidV4;

/**
 * @ApiResource(
 *     attributes={
 *          "security"="is_granted('ROLE_USER')", 
 *          "pagination_items_per_page"=10
 *     },
 *     collectionOperations={
 *          "get" = { "security_post_denormalize" = "is_granted('ROLE_USER')" }, 
 *          "post"= { "security_post_denormalize" = "is_granted('admin')" }
 *     },
 *     itemOperations={
 *          "get" = { "security" = "is_granted('ROLE_USER')" },
 *          "put" = { "security" = "is_granted('admin')" },
 *          "delete" = { "security" = "is_granted('admin')" }
 *     },
 * )
 * @ORM\Entity(repositoryClass=StatusRepository::class)
 */
class Status {
    
    use UuidIdentifiable,
        CodeAble {
            Codeable::setCode as parentSetCode;
            Codeable::getCode as parentGetCode;
        }
    
    /**
     * 
     * @var boolean
     */
    protected $forEvents;
    /**
     * 
     * @var boolean
     */
    protected $forJournals;
    /**
     * 
     * @var boolean
     */
    protected $forTodos;
    
    public function getForEvents() : bool {
        $this->forEvents = in_array($this->code, Event::STATUSES);
        
        return $this->forEvents;
    }
    public function getForJournals() : bool {
        $this->forJournals = in_array($this->code, Journal::STATUSES);
        
        return $this->forJournals;
    }
    public function getForTodos() : bool {
        $this->forTodos = in_array($this->code, Todo::STATUSES);
        
        return $this->forTodos;
    }
    
    /**
     * 
     * @return string|null
     */
    public function getCode(): ?string {
        $this->getForEvents();
        $this->getForTodos();
        $this->getForJournals();
        
        return $this->code;
    }

    /**
     * 
     * @param string $code
     * @return self
     */
    public function setCode(string $code): self {
        $this->code = $code;
        
        $this->getForEvents();
        $this->getForTodos();
        $this->getForJournals();
        
        return $this;
    }
    
}
