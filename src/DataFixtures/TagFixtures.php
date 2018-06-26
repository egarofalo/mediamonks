<?php

namespace App\DataFixtures;

use Doctrine\Common\Persistence\ObjectManager;
use App\Service\Seeder;
use App\Entity\Tag;

class TagFixtures extends Seeder
{   
    public function load(ObjectManager $manager)
    {
        for ($i = 0; $i < 11; $i++) {
            $this->addReference("tag_{$i}", $this->create($this->faker->sentence(rand(1, 3))));
        }

        $manager->flush();
    }
    
    private function create(string $name): Tag
    {
        $tag = new Tag();
        $tag->setName($name);

        $this->om->persist($tag);
        
        return $tag;
    }    
}
