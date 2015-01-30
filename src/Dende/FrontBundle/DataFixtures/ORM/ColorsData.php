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
            $color = $this->insert($params);
            $this->addReference($key, $color);
            $this->manager->persist($color);
            $this->manager->flush();

            $colorTranslations = $this->prepareTranslations($params, $color->getId());

            foreach ($colorTranslations as $colorTranslation) {
                $this->manager->persist($colorTranslation);
            }
            $this->manager->flush();
        }
    }

    /**
     * @param $params
     * @return Car
     */
    public function insert($params)
    {
        $color = new Color();
        $color->setHex($params["hex"]);

        return $color;
    }

    /**
     * @param array   $params
     * @param integer $getId
     */
    private function prepareTranslations($params, $getId)
    {
        $result = [];

        foreach ($params["name"] as $language => $value) {
            $carTranslation = new ColorTranslation();
            $carTranslation->setField("name");
            $carTranslation->setContent($value);
            $carTranslation->setLocale($language);
            $carTranslation->setObjectClass("Dende\FrontBundle\Entity\Color");
            $carTranslation->setForeignKey($getId);
            $result[] = $carTranslation;
        }

        return $result;
    }
}
