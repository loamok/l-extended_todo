<?php

namespace App\DataFixtures;

use App\Entity\Timezone;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Persistence\ObjectManager;

class TimezoneFixtures extends Fixture implements FixtureGroupInterface {
    
    public static function getGroups(): array {
        return ['group_init', 'group_init_dev', 'group_timezone'];
    }
    
    public function load(ObjectManager $manager) {
         $timezone = new Timezone();
         $timezone->setName("europe/paris")
            ->setLabel("Paris (UTC +1)")
            ->setCode("+01:00")
            ;
         $manager->persist($timezone);

        $manager->flush();
    }
    
}
