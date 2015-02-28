<?php
namespace Dende\FrontBundle\Model;

use Dende\FrontBundle\Entity\Brand;
use Dende\FrontBundle\Entity\Model;
use Dende\FrontBundle\Entity\Type;
use Symfony\Component\Validator\Constraints as Assert;
use JMS\Serializer\Annotation as Serializer;


/**
 * @Serializer\ExclusionPolicy('none')
 */

class SearchQuery {

    /**
     * @var Brand $brand
     * @Serializer\Type("Dende\FrontBundle\Entity\Brand")
     */
    private $brand;

    /**
     * @var Model $model
     * @Serializer\Type("Dende\FrontBundle\Entity\Model")
     */
    private $model;

    /**
     * @var Type $type
     * @Serializer\Type("Dende\FrontBundle\Entity\Type")
     */
    private $type;

    /**
     * @return Brand
     */
    public function getBrand()
    {
        return $this->brand;
    }

    /**
     * @param Brand $brand
     */
    public function setBrand($brand)
    {
        $this->brand = $brand;
    }

    /**
     * @return Model
     */
    public function getModel()
    {
        return $this->model;
    }

    /**
     * @param Model $model
     */
    public function setModel($model)
    {
        $this->model = $model;
    }

    /**
     * @return Type
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param Type $type
     */
    public function setType($type)
    {
        $this->type = $type;
    }
}