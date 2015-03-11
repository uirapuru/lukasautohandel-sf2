<?php
namespace Dende\FrontBundle\Service;

use Dende\FrontBundle\Entity\Brand;
use Dende\FrontBundle\Entity\Model;
use Dende\FrontBundle\Entity\Type;
use Dende\FrontBundle\Model\SearchQuery;
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
