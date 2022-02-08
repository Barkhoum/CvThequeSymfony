<?php

namespace App\DataFixtures;

use App\Entity\Profile;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class ProfileFixture extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $profile = new Profile();
        $profile->setRs(rs: 'Facebook');
        $profile->setUrl(url: 'https://fr-fr.facebook.com/');

        $profile1 = new Profile();
        $profile1->setRs(rs: 'Twitter');
        $profile1->setUrl(url: 'https://twitter.com/?lang=fr');

        $profile2 = new Profile();
        $profile2->setRs(rs: 'LinkdIN');
        $profile2->setUrl(url: 'https://fr-fr.facebook.com/');

        $profile3 = new Profile();
        $profile3->setRs(rs: 'GitHub');
        $profile3->setUrl(url: 'https://github.com/');

        $manager->persist($profile);
        $manager->persist($profile1);
        $manager->persist($profile2);
        $manager->persist($profile3);
        $manager->flush();
    }
}
