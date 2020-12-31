<?php
namespace App\Repository\BehavioursTraits;

use Doctrine\Common\Collections\Criteria;


/**
 *
 * @author symio
 */
trait UuidIdentifiable {
    
//    public function find($id, $lockMode = null, $lockVersion = null) {
//        if (is_array($id)) {
//            $uuidInstance = reset($id);
////            $uuidCriteria = Criteria::create()->where(Criteria::expr()->eq('e.id', $uuidInstance->getBytes()));
//            $id = $uuidInstance->getBytes();
//        } 
//        
////        if (is_a($id, \Ramsey\Uuid\UuidInterface::class)) {
////            $id = $uuidInstance->getBytes();
////        }
//        
//        return parent::find($id, $lockMode, $lockVersion);
//    }
    
}
