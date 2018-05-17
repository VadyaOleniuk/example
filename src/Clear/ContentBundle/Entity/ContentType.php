<?php

namespace Clear\ContentBundle\Entity;

use JMS\Serializer\Annotation\Groups;
/**
 * ContentType
 */
class ContentType
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
    private $type;

    /**
     * @var bool
     * @Groups({"details"})
     */
    private $isActive;

    /**
     * @var string
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
     * @var array
     * @Groups({"details"})
     */
    private $form;

    /**
     * @var string
     * @Groups({"details"})
     */
    private $icon;

    /**
     * @var string
     * @Groups({"details"})
     */
    private $iconLigth;

    /**
     * @var string
     * @Groups({"details"})
     */
    private $iconDark;

    /**
     * @var string
     * @Groups({"details"})
     */
    private $iconBlack;

    /**
     * @var string
     * @Groups({"details"})
     */
    private $iconBlue;
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
     * Set type
     *
     * @param string $type
     *
     * @return ContentType
     */
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Get type
     *
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Set icon
     *
     * @param string $icon
     *
     * @return ContentType
     */
    public function setIcon($icon)
    {
        $this->icon = $icon;

        return $this;
    }

    /**
     * Get icon
     *
     * @return string
     */
    public function getIcon()
    {
        return $this->icon;
    }

    /**
     * Set isActive
     *
     * @param boolean $isActive
     *
     * @return ContentType
     */
    public function setIsActive($isActive)
    {
        $this->isActive = $isActive;

        return $this;
    }

    /**
     * Get isActive
     *
     * @return bool
     */
    public function getIsActive()
    {
        return $this->isActive;
    }

    /**
     * Set description
     *
     * @param string $description
     *
     * @return ContentType
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
     * @return ContentType
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
     * @return ContentType
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
     * @return array
     */
    public function getForm()
    {
        return $this->form;
    }

    /**
     * @param array $form
     * @return ContentType
     */
    public function setForm($form)
    {
        $this->form = $form;

        return $this;
    }

    /**
     * Set iconLigth
     *
     * @param string $iconLigth
     *
     * @return ContentType
     */
    public function setIconLigth($iconLigth)
    {
        $this->iconLigth = $iconLigth;

        return $this;
    }

    /**
     * Get iconLigth
     *
     * @return string
     */
    public function getIconLigth()
    {
        return $this->iconLigth;
    }

    /**
     * Set iconDark
     *
     * @param string $iconDark
     *
     * @return ContentType
     */
    public function setIconDark($iconDark)
    {
        $this->iconDark = $iconDark;

        return $this;
    }

    /**
     * Get iconDark
     *
     * @return string
     */
    public function getIconDark()
    {
        return $this->iconDark;
    }

    /**
     * @return string
     */
    public function getIconBlack()
    {
        return $this->iconBlack;
    }

    /**
     * @param string $iconBlack
     * @return ContentType
     */
    public function setIconBlack($iconBlack)
    {
        $this->iconBlack = $iconBlack;

        return $this;
    }

    /**
     * @return string
     */
    public function getIconBlue()
    {
        return $this->iconBlue;
    }

    /**
     * @param string $iconBlue
     * @return ContentType
     */
    public function setIconBlue($iconBlue)
    {
        $this->iconBlue = $iconBlue;

        return $this;
    }


}
