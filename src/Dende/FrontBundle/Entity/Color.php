<?php
namespace Dende\FrontBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Table("colors")
 * @ORM\Entity(repositoryClass="Dende\FrontBundle\Repository\ColorRepository")
 * @Gedmo\TranslationEntity(class="Dende\FrontBundle\Entity\Translation\ColorTranslation")
 */
class Color {
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\Column(type="string", length=8, nullable=false)
     * @var string $hex
     */
    protected $hex;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Gedmo\Translatable
     * @var string $name
     */
    protected $name;
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
     * @return string
     */
    public function getHex()
    {
        return $this->hex;
    }

    /**
     * @param string $hex
     */
    public function setHex($hex)
    {
        $this->hex = $hex;
    }
}