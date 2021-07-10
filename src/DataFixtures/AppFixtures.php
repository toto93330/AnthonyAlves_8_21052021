<?php

namespace App\DataFixtures;

use App\Entity\Task;
use App\Entity\User;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    private $passwordhash;

    public function __construct(UserPasswordHasherInterface $passwordhash)
    {
        $this->passwordhash = $passwordhash;
    }


    public function load(ObjectManager $manager)
    {

        $content = array(
            "0" => "Devenir un meilleur dev",
            "1" => "Dîner dans le noir",
            "2" => "Faites l'amour dans toutes les pièces de la maison",
            "3" => "Investir dans l'immobilier",
            "4" => "Terminer toutes les saisons de lost",
            "5" => "Monter un taureau",
            "6" => "Soyez à l'heure le soir du nouvel ans",
            "7" => "Avoir un travail le week-end",
            "8" => "Couvrir mon plafond d'étoiles'",
            "9" => "Participez à une bataille d'eau",
            "10" => "Courez un marathon dans les 50 États",
            "11" => "Apprenez à surfer",
            "12" => "Manger de la nourriture mexicaine au Mexique",
            "13" => "Faites la course avec un buggy",
            "14" => "Plonger dans une épave",
            "15" => "Avoir un compte Twitter",
            "16" => "Porter une perruque pour une journée",
            "17" => "Devenez un avocat très performant",
            "18" => "Regardez chaque épisode de Seinfeld",
            "19" => "Aller à un concert par moi-même",
            "20" => "Nager dans le golfe du Mexique",
        );


        // ROOT

        $user = new User();
        $user->setUsername("root");
        $user->setEmail("root@root.fr");
        $user->setRoles(["ROLE_USER", "ROLE_ADMIN"]);
        $user->setPassword($this->passwordhash->hashPassword($user, 'root'));
        $manager->persist($user);
        $manager->flush();


        for ($i = 0; $i < 20; $i++) {
            $task = new Task();
            $task->setUser($user);
            $task->setTitle($content[$i]);
            $task->setContent($content[$i]);
            $task->setIsDone(0);
            $task->setCreatedAt(new \DateTime("now"));
            $manager->persist($task);
            $manager->flush();
        }


        // JOHN DOE

        $user = new User();
        $user->setUsername("John");
        $user->setEmail("john@doe.fr");
        $user->setRoles(["ROLE_USER"]);
        $user->setPassword($this->passwordhash->hashPassword($user, 'root'));
        $manager->persist($user);
        $manager->flush();

        // USER

        $user = new User();
        $user->setUsername("User");
        $user->setEmail("user@user.fr");
        $user->setRoles(["ROLE_USER"]);
        $user->setPassword($this->passwordhash->hashPassword($user, 'root'));
        $manager->persist($user);
        $manager->flush();
    }
}
