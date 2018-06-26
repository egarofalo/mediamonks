<?php

namespace App\Service;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Faker\Factory;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;


abstract class Seeder extends Fixture
{
    protected $om;
    protected $faker;
    protected $encoder;
    
    public function __construct(ObjectManager $manager, ContainerInterface $container, UserPasswordEncoderInterface $encoder)
    {
        $this->om = $manager;
        $locale = $container->hasParameter('locale') ? $container->getParameter('locale') : Factory::DEFAULT_LOCALE;
        $this->faker = Factory::create($locale);
        $this->encoder = $encoder;
        $this->references = [];
    }
}
