<?php

namespace Clear\CompanyBundle\Entity;

use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use JMS\Serializer\Annotation\MaxDepth;
/**
 * SpecificTheme
 */
class SpecificTheme
{
    /**
     * @var int
     */
    private $id;

    /**
     * @var string
     */
    private $name;

    /**
     * @var Collection
     * @MaxDepth(1)
     */
    private $companies;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->companies  = new ArrayCollection();
    }

    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set name
     *
     * @param string $name
     *
     * @return SpecificTheme
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }


    /**
     * Add company
     *
     * @param Company $company
     *
     * @return SpecificTheme
     */
    public function addCompany(Company $company)
    {
        $this->companies[] = $company;

        return $this;
    }

    /**
     * Remove company
     *
     * @param Company $company
     */
    public function removeCompany(Company $company)
    {
        $this->companies->removeElement($company);
    }

    /**
     * Get companies
     *
     * @return Collection
     */
    public function getCompanies()
    {
        return $this->companies;
    }
}
