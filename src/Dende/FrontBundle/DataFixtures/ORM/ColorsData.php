<?php

namespace Dende\FrontBundle\DataFixtures\ORM;

use Dende\FrontBundle\DataFixtures\BaseFixture;
use Dende\FrontBundle\Entity\Color;
use Dende\FrontBundle\Entity\Translation\ColorTranslation;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Yaml\Yaml;

class ColorsData extends BaseFixture
{
    public function getOrder()
    {
        return 1;
    }

    public function load(ObjectManager $manager)
    {
        $this->manager = $manager;

        $file = $this->translateClassToFilename($this);

        $value = Yaml::parse(file_get_contents(__DIR__."/../Yaml/".$file));

        foreach ($value as $key => $params) {
            $color = new Color();
            $colorTranslations = $this->prepareTranslations($params, $color);

            $this->addReference($key, $color);

            foreach ($colorTranslations as $colorTranslation) {
                $color->addTranslation($colorTranslation);
                $this->manager->persist($colorTranslation);
            }

            $this->manager->persist($color);
            $this->manager->flush();
        }
    }

    /**
     * @param array   $params
     * @param integer $getId
     */
    private function prepareTranslations($params, Color $color)
    {
        $result = [];

        foreach ($params["name"] as $language => $value) {
            $carTranslation = new ColorTranslation();
            $carTranslation->setObject($color);
            $carTranslation->setField("name");
            $carTranslation->setContent($value);
            $carTranslation->setLocale($language);
            $result[] = $carTranslation;
        }

        return $result;
    }
}
