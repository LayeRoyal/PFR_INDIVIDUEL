<?php

namespace App\DataFixtures;

use App\Entity\Cm;
use Faker\Factory;
use App\Entity\User;
use App\Entity\Apprenant;
use App\Entity\Formateur;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserFixtures extends Fixture
{

    private $encoder;

    public function __construct(UserPasswordEncoderInterface $encoder) {
        $this->encoder = $encoder;
    }

    public function load(ObjectManager $manager)
    {
        $faker = Factory::create('fr_FR');
        for ($i=0; $i <4; $i++) { 
            //creation des utilisateurs
            for ($j=0; $j < 3 ; $j++) { 
                if($i==0){
                    $user = new User();
                }
                elseif($i==1){
                    $user = new Formateur();
                }
                elseif($i==2){
                    $user = new Apprenant();
                }
                else{
                    $user = new Cm();
                }
                
                $user->setPrenom($faker->firstName)
                    ->setNom($faker->lastName)
                    ->setEmail($faker->safeEmail)
                    ->setAvatar($faker->imageUrl(640,480,'cats'))
                    ->setArchivage(false)
                    ->setStatut("Actif")
                    ->setProfil($this->getReference('profil'.$i))
                    ->setUsername($this->getReference('profil'.$i)->getLibelle().($j+1))
                    ->setPassword($this->encoder->encodePassword($user, "password".($j+1)));
                    if($i==2){
                    $user->setGenre($faker->randomElement(['F','M']))
                        ->setStatut($faker->randomElement(['Actif','Attente','Renvoyé']));
                    }
            $manager->persist($user);
        }
        }
        $manager->flush();
    }
}
