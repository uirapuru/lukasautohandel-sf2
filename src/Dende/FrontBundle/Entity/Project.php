<?php
namespace Dende\FrontBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="projects")
 * @codeCoverageIgnore
 */
class Project
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\Column(name="name", type="string", length=255, nullable=true)
     * @var string $name
     */
    protected $name;

    /**
     * @ORM\Column(name="company", type="string", length=255, nullable=true)
     * @var string $company
     */
    protected $company;

    /**
     * @ORM\Column(name="tags", type="simple_array", length=100000, nullable=true)
     * @var array $tags
     */
    protected $tags = array();

    /**
     * @ORM\Column(name="features", type="simple_array", length=100000, nullable=true)
     * @var array $features
     */
    protected $features = array();

    /**
     * @ORM\Column(name="pictures", type="simple_array", length=100000, nullable=true)
     * @var array $pictures
     */
    protected $pictures = array();

    /**
     * @ORM\Column(name="description", type="string", length=100000, nullable=true)
     * @var string $pictures
     */
    protected $description;

    public function getName()
    {
        return $this->name;
    }

    public function getTags()
    {
        return $this->tags;
    }

    public function getFeatures()
    {
        return $this->features;
    }

    public function getPictures()
    {
        return $this->pictures;
    }

    public function getDescription()
    {
        return $this->description;
    }

    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    public function setTags(array $tags)
    {
        $this->tags = $tags;

        return $this;
    }

    public function setFeatures(array $features)
    {
        $this->features = $features;

        return $this;
    }

    public function setPictures(array $pictures)
    {
        $this->pictures = $pictures;

        return $this;
    }

    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    public function getCompany()
    {
        return $this->company;
    }

    public function setCompany($company)
    {
        $this->company = $company;

        return $this;
    }
}
