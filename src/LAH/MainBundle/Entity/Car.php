<?php
namespace LAH\MainBundle\Entity;

use LAH\MainBundle\Entity\Translation\CarTranslation;
use LAH\MainBundle\Entity\Translation\TranslatedTrait;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Gedmo\Translatable\Translatable;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="LAH\MainBundle\Repository\CarRepository")
 * @ORM\Table(name="cars")
 * @codeCoverageIgnore
 * @Gedmo\SoftDeleteable(fieldName="deleted")
 * @Gedmo\TranslationEntity(class="LAH\MainBundle\Entity\Translation\CarTranslation")
 * @SuppressWarnings(PHPMD.TooManyFields)
 */
class Car implements Translatable
{
    use TranslatedTrait;

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\ManyToOne(targetEntity="LAH\MainBundle\Entity\Type")
     * @ORM\JoinColumn(name="type_id", referencedColumnName="id")
     *
     * @var string
     */
    protected $type;

    /**
     * @ORM\ManyToOne(targetEntity="LAH\MainBundle\Entity\Model", inversedBy="cars")
     * @ORM\JoinColumn(name="model_id", referencedColumnName="id")
     *
     * @var Model
     */
    protected $model;

    /**
     * @ORM\ManyToOne(targetEntity="LAH\MainBundle\Entity\Color")
     * @ORM\JoinColumn(name="color_id", referencedColumnName="id")
     *
     * @var Color
     */
    protected $color;

    /**
     * @ORM\Column(type="integer")
     *
     * @var int
     */
    protected $year;

    /**
     * @ORM\Column(type="integer")
     *
     * @var int
     */
    protected $distance;

    /**
     * @ORM\Column(type="string", length=255)
     *
     * @var string
     */
    protected $fuel;

    /**
     * @ORM\Column(type="string", length=255)
     *
     * @var string
     */
    protected $engine;

    /**
     * @ORM\Column(type="string", length=255)
     *
     * @var string
     */
    protected $gearbox;

    /**
     * @ORM\Column(type="string", length=255)
     *
     * @var string
     */
    protected $registrationCountry;

    /**
     * @ORM\OneToMany(targetEntity="LAH\MainBundle\Entity\Price", mappedBy="car", cascade={"remove", "persist"})
     *
     * @var Price[]
     *
     * @Assert\Count(
     *      min = "1",
     *      max = "5",
     *      minMessage = "car.validation.prices.min",
     *      maxMessage = "car.validation.prices.max"
     * )
     */
    protected $prices;

    /**
     * @ORM\OneToOne(targetEntity="LAH\MainBundle\Entity\Image")
     * @ORM\JoinColumn(name="image_id", referencedColumnName="id", onDelete="CASCADE")
     * @var Image
     */
    protected $image;

    /**
     * @ORM\OneToMany(targetEntity="LAH\MainBundle\Entity\Image", mappedBy="car", cascade={"remove", "persist"})
     * @ORM\OrderBy({"position" = "ASC"})
     * @var ArrayCollection<Image>
     *
     * @Assert\Count(
     *      min = "1",
     *      max = "20",
     *      minMessage = "car.validation.images.min",
     *      maxMessage = "car.validation.images.max"
     * )
     */
    protected $images;

    /**
     * @ORM\Column(type="boolean")
     *
     * @var bool
     */
    protected $promoteCarousel = false;

    /**
     * @ORM\Column(type="boolean")
     *
     * @var bool
     */
    protected $promoteFrontpage = false;

    /**
     * @ORM\Column(type="string", length=4096, nullable=false)
     * @Gedmo\Translatable
     *
     * @var string
     */
    protected $title;

    /**
     * @ORM\Column(type="text", nullable=true)
     * @Gedmo\Translatable
     *
     * @var string
     */
    protected $description;

    /**
     * @ORM\Column(type="text", nullable=true)
     *
     * @var string
     */
    protected $adminNotes;

    /**
     * @ORM\Column(type="boolean")
     *
     * @var bool
     */
    protected $hidden = false;

    /**
     * @ORM\Column(type="string", length=4096, nullable=false)
     * @Gedmo\Slug(fields={"title"})
     *
     * @var string
     */
    protected $slug;

    /**
     * @Gedmo\Timestampable(on="create")
     * @ORM\Column(type="datetime")
     *
     * @var \DateTime
     */
    protected $created;

    /**
     * @Gedmo\Timestampable(on="update")
     * @ORM\Column(type="datetime")
     *
     * @var \DateTime
     */
    protected $modified;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     *
     * @var \DateTime
     */
    protected $deleted;

    /**
     * @ORM\OneToMany(
     *   targetEntity="LAH\MainBundle\Entity\Translation\CarTranslation",
     *   mappedBy="object",
     *   cascade={"persist", "remove"}
     * )
     */
    private $translations;

    public function __construct()
    {
        $this->images       = new ArrayCollection();
        $this->prices       = new ArrayCollection();
        $this->translations = new ArrayCollection();
    }

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
     * @return Type
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param Type|null $type
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
     * @param Model|null $model
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
     * @param Color|null $color
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
     * @return ArrayCollection<Image>
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
     * @return bool
     */
    public function isPromoteCorousel()
    {
        return $this->promoteCarousel;
    }

    /**
     * @param bool $promoteCorousel
     */
    public function setPromoteCorousel($promoteCorousel)
    {
        $this->promoteCarousel = $promoteCorousel;
    }

    /**
     * @return bool
     */
    public function isPromoteFrontpage()
    {
        return $this->promoteFrontpage;
    }

    /**
     * @param bool $promoteFrontpage
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
     * @return bool
     */
    public function isHidden()
    {
        return $this->hidden;
    }

    /**
     * @param bool $hidden
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
     * @return bool
     */
    public function isPromoteCarousel()
    {
        return $this->promoteCarousel;
    }

    /**
     * @param bool $promoteCarousel
     */
    public function setPromoteCarousel($promoteCarousel)
    {
        $this->promoteCarousel = $promoteCarousel;
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

    /**
     * @param Image $image
     */
    public function addImage(Image $image)
    {
        $image->setCar($this);
        $this->images->add($image);
    }

    /**
     * @param Image $image
     */
    public function removeImage(Image $image)
    {
        $this->images->removeElement($image);
    }

    /**
     * @param Price $price
     */
    public function addPrice(Price $price)
    {
        $price->setCar($this);
        $this->prices->add($price);
    }

    /**
     * @param Price $price
     */
    public function removePrice(Price $price)
    {
        $this->prices->removeElement($price);
    }

    public function getFirstImage()
    {
        return $this->images->first()->getName();
    }
}
