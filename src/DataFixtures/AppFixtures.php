<?php

namespace App\DataFixtures;

use App\Entity\User;
use App\Entity\Groups;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;


class AppFixtures extends Fixture
{
    private $encoder;

    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;
    }

    public function load(ObjectManager $manager)
    {
        // Create Admin User
        $user = new User();
        $user->setName('admin');
        $password =  $this->encoder->encodePassword($user, 'admin');
        $user->setPassword($password);
        $user->setIsAdmin(1);
        $manager->persist($user);

        // Create Some Groups
        $groups = ['Writer', 'Moderator', 'Reader'];
        foreach ($groups as $g) {
            $group = new Groups();
            $group->setName($g);
            $manager->persist($group);
        }


        $manager->flush();
    }
}