<?php
namespace Clear\SearchBundle\Model;

use Clear\CompanyBundle\Entity\Company;
use Clear\ContentBundle\Entity\Content;
use Clear\UserBundle\Entity\Role;
use Clear\UserBundle\Entity\User;
use Symfony\Component\Validator\Constraints as Assert;

class Search
{
    /**
     * @var string
     * @Assert\NotBlank
     * @Assert\Type("string")
     */
    private $search;

    /**
     * @var int
     */
    private $categories;

    /**
     * @var int
     */
    private $contentType;

    /**
     * @var array<integer>
     */
    private $tags;

    /**
     * @var int
     * @Assert\Type("integer")
     */
    private $size;

    /**
     * @var int
     * @Assert\Type("integer")
     */
    private $page;

    /**
     * @var boolean
     * @Assert\Type("boolean")
     */
    private $isArticle;

    /**
     * @var \DateTime
     * @Assert\DateTime()
     */
    private $from;

    /**
     * @var \DateTime
     * @Assert\DateTime()
     */
    private $to;

    /**
     * @var int
     */
    private $status;

    /**
     * @var User
     */
    private $authorId;

    /**
     * @var Role
     */
    private $roles;

    /**
     * @var Company
     */
    private $companies;

    /**
     * @var Content
     */
    private $content;
    /**
     * @return string
     */
    public function getSearch()
    {
        return $this->search;
    }

    /**
     * @param string $search
     */
    public function setSearch($search)
    {
        $this->search = $search;
    }

    /**
     * @return int
     */
    public function getCategories()
    {
        return $this->categories;
    }

    /**
     * @param int $categories
     */
    public function setCategories($categories)
    {
        $this->categories = $categories;
    }

    /**
     * @return int
     */
    public function getContentType()
    {
        return $this->contentType;
    }

    /**
     * @param int $contentType
     */
    public function setContentType($contentType)
    {
        $this->contentType = $contentType;
    }

    /**
     * @return array
     */
    public function getTags()
    {
        return $this->tags;
    }

    /**
     * @param array $tags
     */
    public function setTags($tags)
    {
        $this->tags = $tags;
    }

    /**
     * @return int
     */
    public function getSize()
    {
        return $this->size;
    }

    /**
     * @param int $size
     */
    public function setSize($size)
    {
        $this->size = $size;
    }

    /**
     * @return int
     */
    public function getPage()
    {
        return $this->page;
    }

    /**
     * @param int $page
     */
    public function setPage($page)
    {
        $this->page = $page;
    }

    /**
     * @return bool
     */
    public function isArticle()
    {
        return $this->isArticle;
    }

    /**
     * @param bool $isArticle
     * @return Search
     */
    public function setIsArticle($isArticle)
    {
        $this->isArticle = $isArticle;

        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getFrom()
    {
        return $this->from;
    }

    /**
     * @param \DateTime $from
     * @return Search
     */
    public function setFrom($from)
    {
        $this->from = $from;

        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getTo()
    {
        return $this->to;
    }

    /**
     * @param \DateTime $to
     * @return Search
     */
    public function setTo($to)
    {
        $this->to = $to;

        return $this;
    }

    /**
     * @return int
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @param int $isStatus
     * @return Search
     */
    public function setStatus($status)
    {
        $this->status = $status;

        return $this;
    }

    /**
     * @return User
     */
    public function getAuthorId()
    {
        return $this->authorId;
    }

    /**
     * @param User $authorId
     * @return Search
     */
    public function setAuthorId($authorId)
    {
        $this->authorId = $authorId;

        return $this;
    }

    /**
     * @return Role
     */
    public function getRoles()
    {
        return $this->roles;
    }

    /**
     * @param $roles
     * @return Search
     */
    public function setRoles($roles)
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @return Company
     */
    public function getCompanies()
    {
        return $this->companies;
    }

    /**
     * @param $companies
     * @return Search
     */
    public function setCompanies($companies)
    {
        $this->companies = $companies;

        return $this;
    }

    /**
     * @return Content
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * @param Content $content
     * @return Search
     */
    public function setContent($content)
    {
        $this->content = $content;

        return $this;
    }


}