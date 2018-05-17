<?php

namespace Clear\CompanyBundle\Entity;

use Doctrine\Common\Collections\Collection;
use Clear\ContentBundle\Entity\Content;
use Doctrine\Common\Collections\ArrayCollection;
use JMS\Serializer\Annotation\MaxDepth;
use JMS\Serializer\Annotation\Groups;

/**
 * Company
 */
class Company
{
    /**
     * @var int
     * @Groups({"details"})
     */
    private $id;

    /**
     * @var string
     * @Groups({"details"})
     */
    private $name;

    /**
     * @var Brand
     * @Groups({"details"})
     */
    private $brand;

    /**
     * @var Collection
     * @Groups({"details"})
     * @MaxDepth(1)
     */
    private $contents;

    /**
     * @var Collection
     * @Groups({"details"})
     * @MaxDepth(1)
     */
    private $specifics;

    /**
     * @var string
     */
    private $url;


    /**
     * Constructor
     */
    public function __construct()
    {
        $this->contents = new ArrayCollection();
        $this->specifics = new ArrayCollection();
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
     * @return Company
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
     * Set brand
     *
     * @param Brand $brand
     *
     * @return Company
     */
    public function setBrand(Brand $brand = null)
    {
        $this->brand = $brand;

        return $this;
    }

    /**
     * Get brand
     *
     * @return Brand
     */
    public function getBrand()
    {
        return $this->brand;
    }

    /**
     * Add content
     *
     * @param Content $content
     *
     * @return Company
     */
    public function addContent(Content $content)
    {
        $this->contents[] = $content;

        return $this;
    }

    /**
     * Remove content
     *
     * @param Content $content
     */
    public function removeContent(Content $content)
    {
        $this->contents->removeElement($content);
    }

    /**
     * Get contents
     *
     * @return Collection
     */
    public function getContents()
    {
        return $this->contents;
    }


    /**
     * Add specific
     *
     * @param SpecificTheme $specific
     *
     * @return Company
     */
    public function addSpecific(SpecificTheme $specific)
    {
        $this->specifics[] = $specific;

        return $this;
    }

    /**
     * Remove specific
     *
     * @param SpecificTheme $specific
     */
    public function removeSpecific(SpecificTheme $specific)
    {
        $this->specifics->removeElement($specific);
    }

    /**
     * Get specifics
     *
     * @return Collection
     */
    public function getSpecifics()
    {
        return $this->specifics;
    }

    /**
     * Set url
     *
     * @param string $url
     *
     * @return Company
     */
    public function setUrl($url)
    {
        $this->url = $url;

        return $this;
    }

    /**
     * Get url
     *
     * @return string
     */
    public function getUrl()
    {
        return $this->url;
    }
}
