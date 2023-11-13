<?php

namespace App\DataFixtures;

use App\Entity\Contact;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Faker\Generator;

class AppFixtures extends Fixture
{
    private Generator $faker;

    public function __construct()
    {
        $this->faker = Factory::create("fr_FR");
    }
    public function load(ObjectManager $manager): void
    {
        for ($i = 0; $i < 50; ++$i) {
            $contact = new Contact();
            $contact->setFirstname($this->faker->firstName);
            $contact->setLastname($this->faker->lastName);
            $contact->setPhone($this->faker->phoneNumber);
            $contact->setEmail($this->faker->email);

            $manager->persist($contact);

        }

        $manager->flush();
    }
}
