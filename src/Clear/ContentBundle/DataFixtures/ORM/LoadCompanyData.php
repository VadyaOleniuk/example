<?php

namespace ClearContentBundle\DataFixtures\ORM;

use Clear\CompanyBundle\Entity\Company;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

class LoadCompanyData implements FixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $company = new Company();
        $company->setName('Lloyd\'s of London');

        $manager->persist($company);
        $manager->flush();
    }
}