<?php

namespace App\DataFixtures;

use App\Entity\AgType;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Persistence\ObjectManager;

class AgTypes extends AppFixtures implements FixtureGroupInterface {
    
    public static function getGroups(): array {
        return ['group_init', 'group_init_dev', 'group_agtype'];
    }
    
    public function load(ObjectManager $manager) {
        $definition = [
            'dir' => null,
            'file' => "agTypes.yaml",
            'class' => AgType::class,
            'fields' => []
        ];
        
        $this->loadFromYaml($definition, $manager);
        
    }
}
