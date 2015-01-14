<?php
namespace Dende\FrontBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="jobs")
 * @codeCoverageIgnore
 *
 * @SuppressWarnings(PHPMD.TooManyFields)
 */

class Job
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\Column(name="name", type="string", length=255, nullable=false)
     * @var string $name
     */
    protected $name;

    /**
     * @ORM\Column(name="address", type="string", length=255, nullable=true)
     * @var string $address
     */
    protected $address;

    /**
     * @ORM\Column(name="city", type="string", length=255, nullable=true)
     * @var string $city
     */
    protected $city;

    /**
     * @ORM\Column(name="country", type="string", length=255, nullable=true)
     * @var string $country
     */
    protected $country;

    /**
     * @ORM\Column(name="link", type="string", length=255, nullable=true)
     * @var string $link
     */
    protected $link;

    /**
     * @ORM\Column(name="date_from", type="date", nullable=false)
     * @var DateTime $from
     */
    protected $from;
    /**
     * @ORM\Column(name="date_to", type="date", nullable=true)
     * @var DateTime $to
     */
    protected $to;

    /**
     * @ORM\Column(name="position", type="string", length=255, nullable=false)
     * @var string $position
     */
    protected $position;

    /**
     * @ORM\Column(name="description_en", type="array", nullable=false)
     * @var string $position
     */
    protected $descriptionEn;

    /**
     * @ORM\Column(name="description_pl", type="array", nullable=false)
     * @var string $position
     */
    protected $descriptionPl;

    /**
     * @return mixed
     */
    public function getAddress()
    {
        return $this->address;
    }

    /**
     * @param mixed $address
     */
    public function setAddress($address)
    {
        $this->address = $address;
    }

    /**
     * @return mixed
     */
    public function getCity()
    {
        return $this->city;
    }

    /**
     * @param mixed $city
     */
    public function setCity($city)
    {
        $this->city = $city;
    }

    /**
     * @return mixed
     */
    public function getCountry()
    {
        return $this->country;
    }

    /**
     * @param mixed $country
     */
    public function setCountry($country)
    {
        $this->country = $country;
    }

    /**
     * @return string
     */
    public function getDescriptionEn()
    {
        return $this->descriptionEn;
    }

    /**
     * @param string $descriptionEn
     */
    public function setDescriptionEn($descriptionEn)
    {
        $this->descriptionEn = $descriptionEn;
    }

    /**
     * @return string
     */
    public function getDescriptionPl()
    {
        return $this->descriptionPl;
    }

    /**
     * @param string $descriptionPl
     */
    public function setDescriptionPl($descriptionPl)
    {
        $this->descriptionPl = $descriptionPl;
    }

    /**
     * @return DateTime
     */
    public function getFrom()
    {
        return $this->from;
    }

    /**
     * @param DateTime $from
     */
    public function setFrom($from)
    {
        $this->from = $from;
    }

    /**
     * @return string
     */
    public function getLink()
    {
        return $this->link;
    }

    /**
     * @param string $link
     */
    public function setLink($link)
    {
        $this->link = $link;
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param mixed $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getPosition()
    {
        return $this->position;
    }

    /**
     * @param string $position
     */
    public function setPosition($position)
    {
        $this->position = $position;
    }

    /**
     * @return DateTime
     */
    public function getTo()
    {
        return $this->to;
    }

    /**
     * @param DateTime $to
     */
    public function setTo($to)
    {
        $this->to = $to;
    }

    public function getDescription($culture = "en")
    {
        if ($culture === "pl") {
            return $this->getDescriptionPl();
        }

        return $this->getDescriptionEn();
    }

    /**
     * @return \DateInterval
     */
    public function getDuration()
    {
        $to = $this->getTo();

        if ($to === null) {
            $to = new \DateTime();
        }

        $diff = $this->getFrom()->diff($to);

        return $diff;
    }
}
