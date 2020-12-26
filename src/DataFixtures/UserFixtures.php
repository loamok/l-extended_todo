<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserFixtures extends AppFixtures implements FixtureGroupInterface {
    
    public static function getGroups(): array {
        return ['group_init_dev', 'group_users'];
    }
    
    /**
     *
     * @var UserPasswordEncoderInterface
     */
    private $passwordEncoder;

    public function __construct(UserPasswordEncoderInterface $passwordEncoder) {
        $this->passwordEncoder = $passwordEncoder;
    }
    
    public function load(ObjectManager $manager) {
        $definition = [
            'dir' => null,
            'file' => "users.yaml",
            'class' => User::class,
            'fields' => [ 
                'clear' => "setPassword"
            ] 
        ];
        
        $this->loadFromYaml($definition, $manager);
        
    }
    
    protected function setPassword($value, User &$user) {
        $user->setPassword($this->passwordEncoder->encodePassword($user, $value));
    }
}
