<?php

namespace AppBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
//use AppBundle\Entity\Band;
use Nelmio\Alice\Fixtures;

class LoadFixtures implements FixtureInterface
{
    public function load(ObjectManager $manager)
    {
//        $band = new Band();
//        $band->setName('Obituary' . rand(1, 100));
//        $band->setSubGenre('Death Metal');
//
//        $manager->persist($band);
//        $manager->flush();
        
        Fixtures::load(__DIR__.'/fixtures.yml', $manager, [ 'providers' => [$this] ]);
        
    }
    
    public function band()
    {
        $genera = [
            'Iron Maiden',
            'Obituary',
            'Carcass',
            'Fates Warning',
            'Armored Saint',
            'Pantera',
            'Antimatter',
            'Pestilence',
            'Anthrax',
            'Megadeth',
            'Magnum',
            'Rush'
        ];
        
        $key = array_rand($genera);
        
        return $genera[$key];       
    } 
}
