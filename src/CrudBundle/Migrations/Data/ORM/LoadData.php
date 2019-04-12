<?php

namespace CrudBundle\Migrations\Data\ORM;

use CrudBundle\Entity\Data;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Oro\Bundle\UserBundle\Entity\User;

class LoadData implements FixtureInterface
{
    public function load(ObjectManager $manager)
    {

        $data = new Data();
        $data->setCreatedAt(new \DateTime());
        $data->setName('First element');
        $user = $manager->getRepository(User::class)->findOneById(1);
        $data->setCreatedBy($user);
        $data->setVisibility(true);
        $manager->persist($data);

        $manager->flush();
    }
}