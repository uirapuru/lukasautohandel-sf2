<?php
namespace Dende\FrontBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Gedmo\Translatable\Translatable;

/**
 * @ORM\Entity(repositoryClass="Dende\FrontBundle\Repository\CarRepository")
 * @ORM\Table(name="cars")
 * @codeCoverageIgnore
 * @Gedmo\SoftDeleteable(fieldName="deleted")
 * @Gedmo\TranslationEntity(class="Dende\FrontBundle\Entity\Translation\CarTranslation")
 * @SuppressWarnings(PHPMD.TooManyFields)
 */

class Car implements Translatable
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\ManyToOne(targetEntity="Dende\FrontBundle\Entity\Type")
     * @ORM\JoinColumn(name="type_id", referencedColumnName="id")
     * @var string $type
     */
    protected $type;

    /**
     * @ORM\ManyToOne(targetEntity="Dende\FrontBundle\Entity\Model")
     * @ORM\JoinColumn(name="model_id", referencedColumnName="id")
     * @var Model $model
     */
    protected $model;

    /**
     * @ORM\ManyToOne(targetEntity="Dende\FrontBundle\Entity\Color")
     * @ORM\JoinColumn(name="color_id", referencedColumnName="id")
     * @var Color $color
     */
    protected $color;

    /**
     * @ORM\Column(type="integer")
     * @var integer $year
     */
    protected $year;

    /**
     * @ORM\Column(type="integer")
     * @var integer $distance
     */
    protected $distance;

    /**
     * @ORM\Column(type="string", length=255)
     * @var string $fuel
     */
    protected $fuel;

    /**
     * @ORM\Column(type="string", length=255)
     * @var string $engine
     */
    protected $engine;

    /**
     * @ORM\Column(type="string", length=255)
     * @var string $gearbox
     */
    protected $gearbox;

    /**
     * @ORM\Column(type="string", length=255)
     * @var string $registrationCountry
     */
    protected $registrationCountry;

    /**
     * @ORM\OneToMany(targetEntity="Dende\FrontBundle\Entity\Price", mappedBy="car")
     * @var Price[] $prices
     */
    protected $prices;

    /**
     * @ORM\OneToOne(targetEntity="Dende\FrontBundle\Entity\Image")
     * @ORM\JoinColumn(name="image_id", referencedColumnName="id")
     * @var Image $image
     */
    protected $image;

    /**
     * @ORM\OneToMany(targetEntity="Dende\FrontBundle\Entity\Image", mappedBy="car")
     * @var Image[] $images
     */
    protected $images;

    /**
     * @ORM\Column(type="boolean")
     * @var boolean $promoteCarousel
     */
    protected $promoteCarousel = false;

    /**
     * @ORM\Column(type="boolean")
     * @var boolean $promoteFrontpage
     */
    protected $promoteFrontpage = false;

    /**
     * @ORM\Column(type="string", length=4096, nullable=true)
     * @Gedmo\Translatable
     * @var string $title
     */
    protected $title;

    /**
     * @ORM\Column(type="text", nullable=true)
     * @Gedmo\Translatable
     * @var string $description
     */
    protected $description;

    /**
     * @ORM\Column(type="text")
     * @var string $adminNotes
     */
    protected $adminNotes;

    /**
     * @ORM\Column(type="boolean")
     * @var boolean $hidden
     */
    protected $hidden = false;

    /**
     * @Gedmo\Timestampable(on="create")
     * @ORM\Column(type="datetime")
     * @var \DateTime $createdAt
     */
    protected $created;

    /**
     * @Gedmo\Timestampable(on="update")
     * @ORM\Column(type="datetime")
     * @var \DateTime $modifiedAt
     */
    protected $modified;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     * @var \DateTime $deletedAt
     */
    protected $deleted;

    /**
     * @Gedmo\Locale
     * @var string $locale
     */
    protected $locale;

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
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param string $type
     */
    public function setType($type)
    {
        $this->type = $type;
    }

    /**
     * @return Model
     */
    public function getModel()
    {
        return $this->model;
    }

    /**
     * @param Model $model
     */
    public function setModel($model)
    {
        $this->model = $model;
    }

    /**
     * @return Color
     */
    public function getColor()
    {
        return $this->color;
    }

    /**
     * @param Color $color
     */
    public function setColor($color)
    {
        $this->color = $color;
    }

    /**
     * @return int
     */
    public function getYear()
    {
        return $this->year;
    }

    /**
     * @param int $year
     */
    public function setYear($year)
    {
        $this->year = $year;
    }

    /**
     * @return int
     */
    public function getDistance()
    {
        return $this->distance;
    }

    /**
     * @param int $distance
     */
    public function setDistance($distance)
    {
        $this->distance = $distance;
    }

    /**
     * @return string
     */
    public function getFuel()
    {
        return $this->fuel;
    }

    /**
     * @param string $fuel
     */
    public function setFuel($fuel)
    {
        $this->fuel = $fuel;
    }

    /**
     * @return string
     */
    public function getEngine()
    {
        return $this->engine;
    }

    /**
     * @param string $engine
     */
    public function setEngine($engine)
    {
        $this->engine = $engine;
    }

    /**
     * @return string
     */
    public function getGearbox()
    {
        return $this->gearbox;
    }

    /**
     * @param string $gearbox
     */
    public function setGearbox($gearbox)
    {
        $this->gearbox = $gearbox;
    }

    /**
     * @return string
     */
    public function getRegistrationCountry()
    {
        return $this->registrationCountry;
    }

    /**
     * @param string $registrationCountry
     */
    public function setRegistrationCountry($registrationCountry)
    {
        $this->registrationCountry = $registrationCountry;
    }

    /**
     * @return Price[]
     */
    public function getPrices()
    {
        return $this->prices;
    }

    /**
     * @param Price[] $prices
     */
    public function setPrices($prices)
    {
        $this->prices = $prices;
    }

    /**
     * @return Image
     */
    public function getImage()
    {
        return $this->image;
    }

    /**
     * @param Image $image
     */
    public function setImage($image)
    {
        $this->image = $image;
    }

    /**
     * @return Image[]
     */
    public function getImages()
    {
        return $this->images;
    }

    /**
     * @param Image[] $images
     */
    public function setImages($images)
    {
        $this->images = $images;
    }

    /**
     * @return boolean
     */
    public function isPromoteCorousel()
    {
        return $this->promoteCarousel;
    }

    /**
     * @param boolean $promoteCorousel
     */
    public function setPromoteCorousel($promoteCorousel)
    {
        $this->promoteCarousel = $promoteCorousel;
    }

    /**
     * @return boolean
     */
    public function isPromoteFrontpage()
    {
        return $this->promoteFrontpage;
    }

    /**
     * @param boolean $promoteFrontpage
     */
    public function setPromoteFrontpage($promoteFrontpage)
    {
        $this->promoteFrontpage = $promoteFrontpage;
    }

    /**
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @param string $title
     */
    public function setTitle($title)
    {
        $this->title = $title;
    }

    /**
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param string $description
     */
    public function setDescription($description)
    {
        $this->description = $description;
    }

    /**
     * @return string
     */
    public function getAdminNotes()
    {
        return $this->adminNotes;
    }

    /**
     * @param string $adminNotes
     */
    public function setAdminNotes($adminNotes)
    {
        $this->adminNotes = $adminNotes;
    }

    /**
     * @return boolean
     */
    public function isHidden()
    {
        return $this->hidden;
    }

    /**
     * @param boolean $hidden
     */
    public function setHidden($hidden)
    {
        $this->hidden = $hidden;
    }

    /**
     * @return \DateTime
     */
    public function getCreated()
    {
        return $this->created;
    }

    /**
     * @param \DateTime $created
     */
    public function setCreated($created)
    {
        $this->created = $created;
    }

    /**
     * @return \DateTime
     */
    public function getModified()
    {
        return $this->modified;
    }

    /**
     * @param \DateTime $modified
     */
    public function setModified($modified)
    {
        $this->modified = $modified;
    }

    /**
     * @return \DateTime
     */
    public function getDeleted()
    {
        return $this->deleted;
    }

    /**
     * @param \DateTime $deleted
     */
    public function setDeleted($deleted)
    {
        $this->deleted = $deleted;
    }

    /**
     * @param string $locale
     */
    public function setTranslatableLocale($locale)
    {
        $this->locale = $locale;
    }

    /**
     * @return boolean
     */
    public function isPromoteCarousel()
    {
        return $this->promoteCarousel;
    }

    /**
     * @param boolean $promoteCarousel
     */
    public function setPromoteCarousel($promoteCarousel)
    {
        $this->promoteCarousel = $promoteCarousel;
    }
}
