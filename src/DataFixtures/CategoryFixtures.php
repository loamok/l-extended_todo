<?php

namespace App\DataFixtures;

use App\Entity\Category;
use App\Entity\AgType;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Persistence\ObjectManager;

class CategoryFixtures extends AppFixtures implements FixtureGroupInterface {
    
    protected $manager;
    
    public static function getGroups(): array {
        return ['group_init', 'group_init_dev', 'group_agtype', 'group_category',];
    }
    
    public function getDependencies() {
        return array(
            AgTypes::class,
        );
    }
    
    public function load(ObjectManager $manager) {
        $this->manager = $manager;
        $definition = [
            'dir' => null,
            'file' => "categories.yaml",
            'class' => Category::class,
            'fields' => ['agtype' => 'joinCategoryToType']
        ];
        
        $this->loadFromYaml($definition, $manager);
        
    }
    
    protected function joinCategoryToType(string $agtype, Category &$category) {
        /* @var $agType AgType */
        $agType = $this->manager->getRepository(AgType::class)->findOneBy(['code' => $agtype]);
        $agType->addCategory($category);
    }
}
