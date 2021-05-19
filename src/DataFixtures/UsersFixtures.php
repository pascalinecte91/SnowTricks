<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker;

class UsersFixtures extends Fixture implements OrderedFixtureInterface
{
    private $encoder;

    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;
    }

    public function load(ObjectManager $manager)
    {
        $faker = Faker\Factory::create('fr_FR');

        for($i = 0; $i <= 5; $i++){
            $user = new User();

            $user->setEmail($faker->email());
            $user->setPassword($this->encoder->encodePassword($user,'toto'));
            $user->setUsername($faker->lastName());
            $user->setEmailConfirm($faker->email());
            $manager->persist($user);

            // enregistre l user dans une addReference
            $this->addReference('user_' . $i, $user);
        }

        $manager->flush();
    }

    public function getOrder()
    {
        return 1;
    }
}
