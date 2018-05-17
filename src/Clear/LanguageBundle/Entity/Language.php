<?php

namespace Clear\LanguageBundle\Entity;

use JMS\Serializer\Annotation\Groups;
/**
 * Language
 */
class Language
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
     * @var string
     * @Groups({"details"})
     */
    private $shortName;
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
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     * @return Language
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return string
     */
    public function getShortName()
    {
        return $this->shortName;
    }

    /**
     * @param string $shortName
     * @return Language
     */
    public function setShortName($shortName)
    {
        $this->shortName = $shortName;

        return $this;
    }


}

