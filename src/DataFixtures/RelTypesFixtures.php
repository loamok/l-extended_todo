<?php

namespace App\DataFixtures;

use App\Entity\RelType;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Persistence\ObjectManager;

class RelTypesFixtures extends AppFixtures implements FixtureGroupInterface {
    
    public static function getGroups(): array {
        return ['group_init', 'group_init_dev', 'group_reltype'];
    }
    
    public function load(ObjectManager $manager) {
        $definition = [
            'dir' => null,
            'file' => "relTypes.yaml",
            'class' => RelType::class,
            'fields' => []
        ];
        
        $this->loadFromYaml($definition, $manager);
        
    }
}
