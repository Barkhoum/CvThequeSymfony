<?php

namespace App\DataFixtures;

use App\Entity\Job;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class JobFixture extends Fixture

{
    public function load(ObjectManager $manager): void
    {
        $data =[
            'Developer Web',
            'Developer Javascript',
            'Developer Php Symfony',
            'Developer Python',
            'Developer Java'
        ];
        for ($i =0; $i<count($data); $i++){
            $job = new Job();
            $job->setDesignation($data[$i]);
            $manager->persist($job);
        }
        $manager->flush();
    }

    private function designation(mixed $i)
    {
    }
}
