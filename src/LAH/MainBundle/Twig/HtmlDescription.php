<?php
namespace LAH\MainBundle\Twig;

use LAH\MainBundle\Entity\Car;

class HtmlDescription extends \Twig_Extension
{
    private $language;

    private $address;

    /**
     * @param mixed $language
     */
    public function setLanguage($language)
    {
        $this->language = $language;
    }

    /**
     * @param mixed $address
     */
    public function setAddress($address)
    {
        $this->address = $address;
    }

    public function getFunctions()
    {

        return [
            new \Twig_SimpleFunction('html_description', [$this, "getHtmlDescription"], ["is_safe" => ["html"]]),
            new \Twig_SimpleFunction('html_address', [$this, "getAddress"], ["is_safe" => ["html"]]),
        ];
    }

    public function getAddress() {
        return $this->address;
    }

    public function getHtmlDescription (Car $car, $language = null) {
        if (!$language) {
           $language = $this->language;
        }

        return $car->getTranslated("description", $language);
    }

    public function getName()
    {
        return 'html_description';
    }
}
