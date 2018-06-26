<?php

namespace App\DataFixtures;

use Doctrine\Common\Persistence\ObjectManager;
use App\Service\Seeder;
use App\Entity\User;

class UserFixtures extends Seeder
{
    public function load(ObjectManager $manager)
    {   
        $this->addReference('user_admin', $this->create('admin', '1234', ['ROLE_ADMIN']));
        $this->addReference('user_egarofalo', $this->create('egarofalo', '1234', ['ROLE_ADMIN']));
        
        $manager->flush();
    }
    
    private function create(string $username, string $password, array $roles): User
    {
        $user = new User();
        $user->setUsername($username);
        $user->setPassword($this->encoder->encodePassword($user, $password));
        $user->setRoles($roles);

        $this->om->persist($user);
        
        return $user;
    }
}
