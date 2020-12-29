<?php

namespace App\DataFixtures;

use App\Entity\Status;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Persistence\ObjectManager;

class StatusFixtures extends AppFixtures implements FixtureGroupInterface {
    
    public static function getGroups(): array {
        return ['group_init', 'group_init_dev', 'group_status'];
    }
    
    public function load(ObjectManager $manager) {
        $definition = [
            'dir' => null,
            'file' => "status.yaml",
            'class' => Status::class,
            'fields' => []
        ];
        
        $this->loadFromYaml($definition, $manager);
        
    }
}
