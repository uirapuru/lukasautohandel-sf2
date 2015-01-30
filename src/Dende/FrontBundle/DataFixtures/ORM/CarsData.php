<?php

namespace Dende\FrontBundle\DataFixtures\ORM;

use Dende\FrontBundle\DataFixtures\BaseFixture;
use Dende\FrontBundle\Entity\Car;
use Dende\FrontBundle\Entity\Translation\CarTranslation;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Yaml\Yaml;

class CarsData extends BaseFixture
{
    public function getOrder()
    {
        return 10;
    }

    public function load(ObjectManager $manager)
    {
        $this->manager = $manager;

        $file = $this->translateClassToFilename($this);

        $value = Yaml::parse(file_get_contents(__DIR__."/../Yaml/".$file));

        foreach ($value as $key => $params) {
            $car = $this->insert($params);
            $this->addReference($key, $car);
            $this->manager->persist($car);
            $this->manager->flush();

            $carTranslations = $this->prepareTranslations($params, $car->getId());

            foreach ($carTranslations as $carTranslation) {
                $this->manager->persist($carTranslation);
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
        list(
            $type,
            $model,
            $color,
            $year,
            $distance,
            $fuel,
            $engine,
            $gearbox,
            $registrationCountry,
            $image,
            $images,
            $promoteCarousel,
            $promoteFrontpage,
            $title,
            $description,
            $adminNotes,
            $hidden
        ) = array_values($params);

        $car = new Car();
        $car->setYear($year);
        $car->setDistance($distance);
        $car->setFuel($fuel);
        $car->setEngine($engine);
        $car->setGearbox($gearbox);
        $car->setRegistrationCountry($registrationCountry);
        $car->setPromoteCorousel($promoteCarousel);
        $car->setPromoteFrontpage($promoteFrontpage);
        $car->setAdminNotes($adminNotes);
        $car->setHidden($hidden);

        $car->setType(
            $this->getReference($type)
        );
        $car->setModel(
            $this->getReference($model)
        );
        $car->setColor(
            $this->getReference($color)
        );

        return $car;
    }

    /**
     * @param array   $params
     * @param integer $getId
     */
    private function prepareTranslations($params, $getId)
    {
        $titles = $params["title"];
        $descriptions = $params["description"];

        $tmpArray = ["title" => $titles, "description" => $descriptions];
        $result = [];

        foreach ($tmpArray as $fieldName => $field) {
            foreach ($field as $language => $value) {
                $carTranslation = new CarTranslation();
                $carTranslation->setField($fieldName);
                $carTranslation->setContent($value);
                $carTranslation->setLocale($language);
                $carTranslation->setObjectClass("Dende\FrontBundle\Entity\Car");
                $carTranslation->setForeignKey($getId);
                $result[] = $carTranslation;
            }
        }

        return $result;
    }
}
