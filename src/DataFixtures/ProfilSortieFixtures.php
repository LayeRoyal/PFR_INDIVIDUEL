<?php

namespace App\DataFixtures;

use App\Entity\ProfilSortie;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;

class ProfilSortieFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $profilSorties = ["DEV BACK", "DEV FRONT", "FULLSTACK", "DESIGNER"];
        for ($i=0; $i<4 ; $i++) { 
            $profilSortie = new ProfilSortie();
            $profilSortie->setLibelle($profilSorties[$i])
                    ->setArchivage(false);
            $manager->persist($profilSortie);

            $this->addReference('profilSortie'.$i,$profilSortie);
        }

        $manager->flush();
    }
}
