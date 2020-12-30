<?php

namespace App\DataFixtures;

use App\Entity\FbType;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Persistence\ObjectManager;

class FbTypesFixtures extends AppFixtures implements FixtureGroupInterface {
    
    public static function getGroups(): array {
        return ['group_init', 'group_init_dev', 'group_fbtype'];
    }
    
    public function load(ObjectManager $manager) {
        $definition = [
            'dir' => null,
            'file' => "fbTypes.yaml",
            'class' => FbType::class,
            'fields' => []
        ];
        
        $this->loadFromYaml($definition, $manager);
        
    }
}
