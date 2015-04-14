<?php
namespace LAH\FrontBundle\Twig;

use LAH\FrontBundle\Entity\Image;

class InstanceOfExtension extends \Twig_Extension
{
    public function getTests()
    {
        return [
            new \Twig_SimpleTest('CarImage', function ($value) {
                return $value instanceof Image;
            }),
        ];
    }

    public function getName()
    {
        return 'instanceof_extension';
    }
}
