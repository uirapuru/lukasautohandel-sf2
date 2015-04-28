<?php
namespace LAH\MainBundle\Entity;

use LAH\MainBundle\Entity\Translation\ColorTranslation;
use LAH\MainBundle\Entity\Translation\TranslatedTrait;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Table("colors")
 * @ORM\Entity(repositoryClass="LAH\MainBundle\Repository\ColorRepository")
 * @Gedmo\TranslationEntity(class="LAH\MainBundle\Entity\Translation\ColorTranslation")
 */
class Color
{
    use TranslatedTrait;

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Gedmo\Translatable
     *
     * @var string
     */
    protected $name;

    /**
     * @ORM\OneToMany(
     *   targetEntity="LAH\MainBundle\Entity\Translation\ColorTranslation",
     *   mappedBy="object",
     *   cascade={"persist", "remove"}
     * )
     */
    private $translations;

    public function __construct()
    {
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
     * @return string
     */
    public function getName()
    {
        return $this->getTranslated('name', 'pl');
    }

    /**
     * @param Price $translation
     */
    public function removeTranslation(ColorTranslation $translation)
    {
        $this->translations->removeElement($translation);
    }

    public function getFullname()
    {
        return sprintf(
            '%s (ru: %s, de: %s)',
            $this->getTranslated('name', 'pl'),
            $this->getTranslated('name', 'ru'),
            $this->getTranslated('name', 'de')
        );
    }
}
