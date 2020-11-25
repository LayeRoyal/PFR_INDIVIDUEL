<?php

namespace App\DataFixtures;

use App\Entity\Profil;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;

class ProfilFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $profiles = ["ADMIN", "FORMATEUR", "APPRENANT", "CM"];

        for ($i=0; $i<4 ; $i++) { 
            $profile = new Profil();
            $profile->setLibelle($profiles[$i])
                    ->setArchivage(false);
            $manager->persist($profile);

            $this->addReference('profil'.$i,$profile);
        }

        $manager->flush();
    }
}
