<?php

namespace App\DataFixtures;

use App\Entity\Contacts;
use App\Entity\Groups;
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
        for ($i = 0; $i <40; $i++) {
            $group = new Groups();
            $group->setName($this->faker->unique()->word);
            $group->setDescription($this->faker->sentence);
            $manager->persist($group);
        }
        $manager->flush();
        $groups = $manager->getRepository(Groups::class)->findAll();

        for ($i = 0; $i < 200; ++$i) {
            $contact = new Contacts();
            $contact->setFirstname($this->faker->firstName);
            $contact->setLastname($this->faker->lastName);
            $contact->setPhone($this->faker->phoneNumber);
            $contact->setEmail($this->faker->email);
            for ($j = 0; $j < 3; ++$j) {
                $randomGroup = $groups[array_rand($groups)];
                $contact->addGroups($randomGroup);
            }

            $manager->persist($contact);
        }

        $manager->flush();
    }
}
