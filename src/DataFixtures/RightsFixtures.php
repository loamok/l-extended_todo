<?php

namespace App\DataFixtures;

use App\Entity\Rights;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Persistence\ObjectManager;

class RightsFixtures extends AppFixtures implements FixtureGroupInterface {
    
    public static function getGroups(): array {
        return ['group_init', 'group_init_dev', 'group_rdt'];
    }
    
    public function load(ObjectManager $manager) {
        $definition = [
            'dir' => null,
            'file' => "rights.yaml",
            'class' => Rights::class,
            'fields' => []
        ];
        
        $this->loadFromYaml($definition, $manager);
        
    }
}
