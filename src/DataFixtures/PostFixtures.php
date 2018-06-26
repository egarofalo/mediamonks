<?php

namespace App\DataFixtures;

use Doctrine\Common\Persistence\ObjectManager;
use App\Service\Seeder;
use App\Entity\Post;
use App\Entity\User;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use App\DataFixtures\UserFixtures;
use App\DataFixtures\TagFixtures;

class PostFixtures extends Seeder implements DependentFixtureInterface
{  
    
    public function load(ObjectManager $manager)
    {
        $users = [
            $this->getReference('user_admin'),
            $this->getReference('user_egarofalo')
        ];
        $tags = [];
        for ($i = 0; $i < 11; $i++) {
            $tags[] = $this->getReference("tag_{$i}");
        }        
        for ($i = 0; $i < 11; $i++) {
            shuffle($tags);
            $this->create(
                    $this->faker->sentence(rand(3, 4)),
                    $this->faker->text(),
                    array_slice($tags, 0, rand(0, 3)),
                    $users[rand(0, 1)]
                    );
        }
        
        $manager->flush();
    }
    
    private function create(string $title, string $body, array $tags, User $user): Post
    {
        $post = new Post();
        $post->setTitle($title);
        $post->setBody($body);
        foreach ($tags as $tag) {
            $post->addTag($tag);
        }
        $post->setUser($user);

        $this->om->persist($post);
        
        return $post;
    }

    public function getDependencies(): array
    {
        return [
            UserFixtures::class,
            TagFixtures::class
        ];
    }

}
