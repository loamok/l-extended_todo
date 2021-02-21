<?php
namespace App\Entity\BehavioursTraits;

use Doctrine\Common\Collections\Collection;
use App\Entity\Category;

/**
 *
 * @author symio
 */
trait Categorizable {
    
    /**
     * @return Collection|Category[]
     */
    public function getCategories(): Collection {
        return $this->categories;
    }

    public function addCategory(Category $category): self {
        if (is_null($this->categories) || !$this->categories->contains($category)) {
            $this->categories[] = $category;
        }

        return $this;
    }

    public function removeCategory(Category $category): self {
        $this->categories->removeElement($category);

        return $this;
    }
    
    public function hasCategory(Category $category) : bool {
        return $this->categories->contains($category);
    }
    
}
