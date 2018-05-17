<?php

namespace Clear\ContentBundle\Entity;

use Clear\ContentBundle\Model\ContentList;
use Clear\FileStorageBundle\Entity\FileStorage;
use Clear\LanguageBundle\Entity\Language;
use Doctrine\Common\Collections\ArrayCollection;
use Clear\UserBundle\Entity\User;
use JMS\Serializer\Annotation as JMS;
//use Nelmio\ApiDocBundle\Tests\Fixtures\Form\CollectionType;

use Symfony\Component\HttpFoundation\File\UploadedFile;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use Symfony\Component\HttpFoundation\File\File;
use Doctrine\Common\Collections\Collection;
use JMS\Serializer\Annotation\MaxDepth;
use Symfony\Component\Validator\Constraints as Assert;
use JMS\Serializer\Annotation\Groups;

/**
 * Content
 *
 * @Vich\Uploadable
 */
class Content
{
    /**
     * @Groups({"details"})
     * @var int
     */
    private $id;

    /**
     * @var string
     * @Assert\NotNull()
     * @Groups({"details"})
     */
    private $title;

    /**
     * @var string
     */
    private $content;

    /**
     * @var array
     * @Groups({"details"})
     */
    private $typeValues;

    /**
     * @var string
     * @Assert\NotNull()
     * @Groups({"details"})
     */
    private $description;

    /**
     * @var \DateTime
     * @Groups({"details"})
     */
    private $createdAt;

    /**
     * @var \DateTime
     * @Groups({"details"})
     */
    private $updatedAt;

    /**
     * @var \DateTime
     * @Assert\NotNull()
     * @Groups({"details"})
     */
    private $publishedAt;

    /**
     * @var integer
     * @Assert\NotNull()
     * @Groups({"details"})
     */
    private $status;

    /**
     * @var ContentType
     * @Assert\NotNull()

     * @Groups({"details"})
     * @MaxDepth(2)
     */
    private $contentType;

    /**
     * @var Collection
     *
     * @Groups({"details"})
     * @MaxDepth(2)
     */
    private $tags;

    /**
     * @var string
     */
    private $alt;
    /**
     * @var User
     * @MaxDepth(2)
     * @Groups({"details"})
     */
    private $user;

    /**
     * @var \Doctrine\Common\Collections\Collection
     * @MaxDepth(2)
     * @Groups({"details"})
     */
    private $roles;

    /**
     * NOTE: This is not a mapped field of entity metadata, just a simple property.
     *
     * @Vich\UploadableField(mapping="content_images", fileNameProperty="imageName", size="imageSize")
     *
     * @var File
     * @Groups({"details"})
     */
    private $imageFile;

    /**
     * @var string
     * @Groups({"details"})
     */
    private $imageName;

    /**
     * @var integer
     * @Groups({"details"})
     */
    private $imageSize;

    /**
     * @var boolean
     * @Groups({"details"})
     */

    private $isArticle;

    /**
     * @var Collection
     * @MaxDepth(2)
     * @Groups({"details"})
     */
    private $children;

    /**
     * @var Collection
     * @MaxDepth(2)
     * @Groups({"details"})
     */
    private $categories;

    /**
     * @var \Doctrine\Common\Collections\Collection
     * @MaxDepth(2)
     * @Groups({"details"})
     */
    private $companies;

    /**
     * @var Language
     * @Groups({"details"})
     */
    private $language;

    /**
     * @var string
     * @Groups({"details"})
     */
    private $contentList;
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->tags = new ArrayCollection();
        $this->categories = new ArrayCollection();
        $this->children = new ArrayCollection();
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
     * Set title
     *
     * @param string $title
     *
     * @return Content
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Get title
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set content
     *
     * @param string $content
     *
     * @return Content
     */
    public function setContent($content)
    {
        $this->setContentList($content);
        $this->content = $content;

        return $this;
    }

    /**
     * Get content
     *
     * @return string
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * Set typeValues
     *
     * @param array $typeValues
     *
     * @return Content
     */
    public function setTypeValues($typeValues)
    {
        $this->typeValues = $typeValues;

        return $this;
    }

    /**
     * Get typeValues
     *
     * @return array
     */
    public function getTypeValues()
    {
        return $this->typeValues;
    }

    /**
     * Set description
     *
     * @param string $description
     *
     * @return Content
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     *
     * @return Content
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * Get createdAt
     *
     * @return \DateTime
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * Set updatedAt
     *
     * @param \DateTime $updatedAt
     *
     * @return Content
     */
    public function setUpdatedAt($updatedAt)
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    /**
     * Get updatedAt
     *
     * @return \DateTime
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }

    /**
     * Set publishedAt
     *
     * @param \DateTime $publishedAt
     *
     * @return Content
     */
    public function setPublishedAt($publishedAt)
    {
        $this->publishedAt = $publishedAt;

        return $this;
    }

    /**
     * Get publishedAt
     *
     * @return \DateTime
     */
    public function getPublishedAt()
    {
        return $this->publishedAt;
    }

    /**
     * Set contentType
     *
     * @param ContentType $contentType
     *
     * @return Content
     */
    public function setContentType(ContentType $contentType = null)
    {
        $this->contentType = $contentType;

        return $this;
    }

    /**
     * Get contentType
     *
     * @return ContentType
     */
    public function getContentType()
    {
        return $this->contentType;
    }

    /**
     * Add tag
     *
     * @param Tag $tag
     *
     * @return Content
     */
    public function addTag(Tag $tag)
    {
        $this->tags[] = $tag;

        return $this;
    }

    /**
     * Remove tag
     *
     * @param Tag $tag
     */
    public function removeTag(Tag $tag)
    {
        $this->tags->removeElement($tag);
    }

    /**
     * Get tags
     *
     * @return Collection
     */
    public function getTags()
    {
        return $this->tags;
    }


    /**
     * Set user
     *
     * @param User $user
     *
     * @return Content
     */
    public function setUser(User $user = null)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return User
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getRoles()
    {
        return $this->roles;
    }

    /**
     * @param \Doctrine\Common\Collections\Collection $roles
     * @return Content
     */
    public function setRoles($roles)
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     *
     *
     *
     * @param File|UploadedFile $image
     *
     * @return Content
     */
    public function setImageFile(File $image = null)
    {
        $this->imageFile = $image;
        if ($image) {
            $this->updatedAt = new \DateTime();

        }
        return $this;
    }

    /**
     * @return File|null
     */
    public function getImageFile()
    {
        return $this->imageFile;
    }

    /**
     * @param string $imageName
     *
     * @return Content
     */
    public function setImageName($imageName)
    {
        $this->imageName = $imageName;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getImageName()
    {
        return $this->imageName;
    }

    /**
     * @param integer $imageSize
     *
     * @return Content
     */
    public function setImageSize($imageSize)
    {
        $this->imageSize = $imageSize;

        return $this;
    }

    /**
     * @return integer|null
     */
    public function getImageSize()
    {
        return $this->imageSize;
    }

    /**
     * Add category
     *
     * @param Category $category
     *
     * @return Content
     */
    public function addCategory(Category $category)
    {
        $this->categories[] = $category;

        return $this;
    }

    /**
     * Remove category
     *
     * @param Category $category
     */
    public function removeCategory(Category $category)
    {
        $this->categories->removeElement($category);
    }

    /**
     * Get categories
     *
     * @return Collection
     */
    public function getCategories()
    {
        return $this->categories;
    }

    /**
     * Add role
     *
     * @param \Clear\UserBundle\Entity\Role $role
     *
     * @return Content
     */
    public function addRole(\Clear\UserBundle\Entity\Role $role)
    {
        $this->roles[] = $role;

        return $this;
    }

    /**
     * Remove role
     *
     * @param \Clear\UserBundle\Entity\Role $role
     */
    public function removeRole(\Clear\UserBundle\Entity\Role $role)
    {
        $this->roles->removeElement($role);
    }

    /**
     * Add company
     *
     * @param \Clear\CompanyBundle\Entity\Company $company
     *
     * @return Content
     */
    public function addCompany(\Clear\CompanyBundle\Entity\Company $company)
    {
        $this->companies[] = $company;

        return $this;
    }

    /**
     * Remove company
     *
     * @param \Clear\CompanyBundle\Entity\Company $company
     */
    public function removeCompany(\Clear\CompanyBundle\Entity\Company $company)
    {
        $this->companies->removeElement($company);
    }

    /**
     * Get companies
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getCompanies()
    {
        return $this->companies;
    }

    /**
     * Set status
     *
     * @param integer $status
     *
     * @return Content
     */
    public function setStatus($status)
    {
        $this->status = $status;

        return $this;
    }

    /**
     * Get status
     *
     * @return integer
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @return bool
     */
    public function getIsArticle()
    {
        return $this->isArticle;
    }

    /**
     * @return bool
     */


    /**
     * @param bool $isArticle
     * @return Content
     */
    public function setIsArticle($isArticle)
    {
        $this->isArticle = $isArticle;

        return $this;
    }

    /**
     * Add category
     *
     * @param Content $children
     *
     * @return Content
     */
    public function addChildren(Content $children)
    {
        $this->children[] = $children;

        return $this;
    }

    /**
     * Remove category
     *
     * @param Content $children
     */
    public function removeChildren(Content $children)
    {
        $this->children->removeElement($children);
    }

    /**
     * Get categories
     *
     * @return Collection
     */
    public function getChildren()
    {
        return $this->children;
    }

    /**
     * @return Language
     */
    public function getLanguage()
    {
        return $this->language;
    }

    /**
     * @param Language $language
     * @return Content
     */
    public function setLanguage($language)
    {
        $this->language = $language;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getContentList()
    {
        return $this->contentList;
    }

    /**
     * @param mixed $contentList
     * @return Content
     */
    public function setContentList($content)
    {
        $this->contentList = ContentList::getContentList($content);

        return $this;
    }

    /**
     * @return string
     */
    public function getAlt()
    {
        return $this->alt;
    }

    /**
     * @param string $alt
     * @return Content
     */
    public function setAlt($alt)
    {
        $this->alt = $alt;

        return $this;
    }


}
