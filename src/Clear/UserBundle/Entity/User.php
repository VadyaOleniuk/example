<?php

namespace Clear\UserBundle\Entity;

use FOS\UserBundle\Model\User as BaseUser;
use Clear\UserBundle\Entity\Role;
use Clear\CompanyBundle\Entity\Company;
use Doctrine\Common\Collections\Collection;
use JMS\Serializer\Annotation\MaxDepth;
use JMS\Serializer\Annotation\Groups;
use JMS\Serializer\Annotation as JMS;

/**
 * User
 */
class User extends BaseUser
{
    /**
     * @var int
     * @Groups({"details"})
     */
    protected $id;
    /**
     * @Groups({"details"})
     */
    protected $function;

    /**
     * @Groups({"details"})
     */
    protected $lastName;

    /**
     * @Groups({"details"})
     */
    protected $jobTitle;

    /**
     * @var Collection
     * @Groups({"details"})
     * @MaxDepth(2)
     */
    private $roleUsers;

    /**
     * @var Company
     * @Groups({"details"})
     * @MaxDepth(1)
     */
    private $company;

    /**
     * @var string
     * @Groups({"details"})
     * @MaxDepth(1)
     */
    private $name;


    public function __construct()
    {
        parent::__construct();
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

    public function setEmail($email)
    {

        parent::setUsername($email);
        return parent::setEmail($email);
    }

    public function setEmailCanonical($emailCanonical)
    {
        parent::setUsernameCanonical($emailCanonical);
        return parent::setEmailCanonical($emailCanonical);
    }


    /**
     * Set function
     *
     * @param string $function
     *
     * @return User
     */
    public function setFunction($function)
    {
        $this->function = $function;

        return $this;
    }

    /**
     * Get function
     *
     * @return string
     */
    public function getFunction()
    {
        return $this->function;
    }

    /**
     * Set lastName
     *
     * @param string $lastName
     *
     * @return User
     */
    public function setLastName($lastName)
    {
        $this->lastName = $lastName;

        return $this;
    }

    /**
     * Get lastName
     *
     * @return string
     */
    public function getLastName()
    {
        return $this->lastName;
    }

    /**
     * Set jobTitle
     *
     * @param string $jobTitle
     *
     * @return User
     */
    public function setJobTitle($jobTitle)
    {
        $this->jobTitle = $jobTitle;

        return $this;
    }

    /**
     * Get jobTitle
     *
     * @return string
     */
    public function getJobTitle()
    {
        return $this->jobTitle;
    }

    /**
     * Add roleUser
     *
     * @param Role $roleUser
     *
     * @return User
     */
    public function addRoleUser(Role $roleUser)
    {
        $this->roleUsers[] = $roleUser;

        return $this;
    }

    /**
     * Remove roleUser
     *
     * @param Role $roleUser
     */
    public function removeRoleUser(Role $roleUser)
    {
        $this->roleUsers->removeElement($roleUser);
    }

    /**
     * Get roleUsers
     *
     * @return Collection
     */
    public function getRoleUsers()
    {
        return $this->roleUsers;
    }

    /**
     * Set company
     *
     * @param Company $company
     *
     * @return User
     */
    public function setCompany(Company $company = null)
    {
        $this->company = $company;

        return $this;
    }

    /**
     * Get company
     *
     * @return Company
     */
    public function getCompany()
    {
        return $this->company;
    }

    public function isRole($userRole='Admin')
    {
        foreach ($this->getRoleUsers() as $role){
            if($role->getName() === $userRole) {
                return true;
            }
        }
        return false;
    }

    /**
     * Set name
     *
     * @param string $name
     *
     * @return User
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
     * Set name
     *
     * @param string $name
     *
     * @return User
     */
    public function setUsername($username)
    {

        $this->name = $username;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getUsername()
    {
        return $this->name;
    }
}
