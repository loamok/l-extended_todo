<?php

namespace App\DataFixtures;

use App\Entity\DelegationType;
use App\Entity\Rights;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class DelegationTypeFixtures extends AppFixtures implements FixtureGroupInterface, DependentFixtureInterface{
    
    protected $manager;
    
    public static function getGroups(): array {
        return ['group_init', 'group_init_dev', 'group_rdt'];
    }
    
    public function getDependencies() {
        return array(
            RightsFixtures::class,
        );
    }
    
    public function load(ObjectManager $manager) {
        $this->manager = $manager;
        $definition = [
            'dir' => null,
            'file' => "delegationTypes.yaml",
            'class' => DelegationType::class,
            'fields' => ['rights' => "joinRights"]
        ];
        
        $this->loadFromYaml($definition, $manager);
        
    }
    
    protected function joinRights(array $rights, DelegationType &$dt) {
        foreach ($rights as $value) {
            $right = $this->manager->getRepository(Rights::class)->findOneBy(['code' => $value]);
            $dt->addRight($right);
        }
    }
}
