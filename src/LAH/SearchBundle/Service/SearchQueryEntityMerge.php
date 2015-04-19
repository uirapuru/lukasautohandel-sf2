<?php
namespace LAH\SearchBundle\Service;

use LAH\MainBundle\Entity\Brand;
use LAH\MainBundle\Entity\Model;
use LAH\MainBundle\Entity\Type;
use LAH\SearchBundle\Model\SearchQuery;
use Doctrine\ORM\EntityManager;

class SearchQueryEntityMerge
{
    /**
     * @var EntityManager
     */
    private $entityManager;

    /**
     * @param EntityManager $entityManager
     */
    public function setEntityManager($entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function merge(SearchQuery $query)
    {
        $brand = $query->getBrand();

        if ($brand instanceof Brand) {
            $query->setBrand($this->entityManager->merge($brand));
        }

        $model = $query->getModel();

        if ($model instanceof Model) {
            $query->setModel($this->entityManager->merge($model));
        }

        $type = $query->getType();

        if ($type instanceof Type) {
            $query->setType($this->entityManager->merge($type));
        }
    }
}
