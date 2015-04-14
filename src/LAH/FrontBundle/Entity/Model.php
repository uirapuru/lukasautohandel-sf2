<?php
namespace LAH\FrontBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use JMS\Serializer\Annotation\ExclusionPolicy;
use JMS\Serializer\Annotation\Expose;

/**
 * @ORM\Table("models")
 * @ORM\Entity()
 * @ExclusionPolicy("all")
 */
class Model
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     * @Expose()
     */
    protected $id;

    /**
     * @ORM\Column(type="string", length=255)
     *
     * @var string
     * @Expose()
     */
    protected $name;

    /**
     * @ORM\ManyToOne(targetEntity="LAH\FrontBundle\Entity\Brand", inversedBy="models")
     * @ORM\JoinColumn(name="brand_id", referencedColumnName="id")
     *
     * @var Brand
     * @Expose()
     */
    protected $brand;

    /**
     * @Gedmo\Slug(fields={"name"})
     * @ORM\Column(type="string", length=255, unique=true)
     *
     * @var string
     */
    protected $slug;

    /**
     * @ORM\OneToMany(targetEntity="LAH\FrontBundle\Entity\Car", mappedBy="model")
     *
     * @var Car[]
     */
    protected $cars;

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     */
    public function setId($id)
    {
        $this->id = $id;
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
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @return Brand
     */
    public function getBrand()
    {
        return $this->brand;
    }

    /**
     * @param Brand $brand
     */
    public function setBrand(Brand $brand)
    {
        $this->brand = $brand;
    }

    /**
     * @return string
     */
    public function getSlug()
    {
        return $this->slug;
    }

    /**
     * @param string $slug
     */
    public function setSlug($slug)
    {
        $this->slug = $slug;
    }

    public function getFullName()
    {
        return $this->getName();
    }

    /**
     * @return mixed
     */
    public function getCars()
    {
        return $this->cars;
    }

    /**
     * @param mixed $cars
     */
    public function setCars($cars)
    {
        $this->cars = $cars;
    }
}
