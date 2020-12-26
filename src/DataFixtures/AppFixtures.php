<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Config\Exception\FileLocatorFileNotFoundException;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\Yaml\Yaml;

class AppFixtures extends Fixture implements FixtureGroupInterface {
    
    public static function getGroups(): array {
        return ['group_init', 'group_init_dev', 'group_timezone'];
    }
    
    public function load(ObjectManager $manager) {
        // $product = new Product();
        // $manager->persist($product);

        $manager->flush();
    }
    
    /**
     * Load fixtures from yaml files
     * definition must be an array like : 
     * [
     *  'dir' => null for the DataFixtures directory|[the path(s) to the .yaml file as an array of strings],
     *  'file' => the yaml file name including the .yaml extension,
     *  'class' => the entity class name,
     *  'fields' => [ : optionnal fields definition
     *      'fieldKey' => "fixture_protected_transformation_function"
     *  ] 
     * ]
     * 
     * @param array $definition
     * @param ObjectManager $manager
     */
    protected function loadFromYaml($definition, ObjectManager $manager) {
        $dir = $definition['dir'] ?? [__DIR__];
        $locator = new FileLocator($dir);
        try {
            $filePath = $locator->locate($definition['file'], null, false);
        } catch (FileLocatorFileNotFoundException $e) {
            $filePath = null;
        }
        
        if(!is_null($filePath)) {
            $entries = Yaml::parseFile($filePath[0], Yaml::PARSE_OBJECT);
            foreach ($entries as $entry) {
                $obj = new $definition['class']();
                foreach ($entry as $key => $value) {
                    if(array_key_exists($key, $definition['fields'])) {
                        $this->{$definition['fields'][$key]}($value, $obj);
                    } else {
                        $func = "set".ucfirst($key);
                        $obj->{$func}($value);
                    }
                }
                
                $manager->persist($obj);
            }
        }

        $manager->flush();
        
    }
}
