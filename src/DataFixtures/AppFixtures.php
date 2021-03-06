<?php

namespace App\DataFixtures;

use App\Entity\Client;
use App\Entity\Product;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    /**
     * @var UserPasswordHasherInterface
     */
    private $encoder;

    public function __construct(UserPasswordHasherInterface $encoder)
    {
        $this->encoder = $encoder;
    }

    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create();

        for ($i = 1; $i <= 3; $i++) {
            $client = new Client();
            $client->setEmail("client$i@bilmo.com");
            $client->setName($faker->name());
            $client->setRoles(['ROLE_USER']);
            $client->setPassword($this->encoder->hashPassword($client, 'password'));
            $manager->persist($client);
        }
        $manager->flush();

        $users = $manager->getRepository(Client::class)->findAll();
        for ($i = 1; $i <= 10; $i++) {
            $user = new User();
            $user->setFirstname($faker->firstName());
            $user->setLastname($faker->lastName());
            /** @var Client $client */
            $client = $faker->randomElement($users);
            $client->addUser($user);
            $manager->persist($client);
        }
        $manager->flush();

        $marks = [
            'Apple', 'Samsung'
        ];
        $models = [
            "Apple" => ["iPhone 4", "iPhone 5", "iPhone 6", "iPhone 7", "iPhone 8", "iPhone 9", "iPhone X", "iPhone X Pro", "iPhone X Max", "iPhone 12", "iPhone 12 Pro"],
            "Samsung" => ["A7", "S12", "Galaxy S", "Galaxy S Pro", "Galaxy Note", "Galaxy ote Max", "Galaxy S20"]
        ];
        $colors = ["Red", "White", "Gold", "Silver", "Metal"];
        $capacities = ["32 Go", "64 Go", "128 Go", "1 To"];

        $users = $manager->getRepository(User::class)->findAll();
        for ($i = 1; $i <= 113; $i++) {
            $phone = new Product();
            $phone->setPrice($faker->numberBetween(600, 1000));
            $mark = $faker->randomElement($marks);
            $phone->setMark($mark);
            $phone->setModel($faker->randomElement($models[$mark]));
            $phone->setColor($faker->randomElement($colors));
            $phone->setCapacity($faker->randomElement($capacities));
            /** @var User $user */
            $user = $faker->randomElement($users);
            $user->addProduct($phone);
            $manager->persist($phone);
        }
        $manager->flush();
    }
}
