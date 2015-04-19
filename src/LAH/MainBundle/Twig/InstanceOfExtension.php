<?php
namespace LAH\MainBundle\Twig;

use LAH\MainBundle\Entity\Image;

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
