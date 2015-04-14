<?php
namespace LAH\FrontBundle\Model;

use LAH\FrontBundle\Entity\Brand;
use LAH\FrontBundle\Entity\Model;
use LAH\FrontBundle\Entity\Type;
use JMS\Serializer\Annotation\Expose;
use JMS\Serializer\Annotation\Type as FieldType;

class SearchQuery
{
    /**
     * @var Brand
     * @FieldType("LAH\FrontBundle\Entity\Brand")
     * @Expose()
     */
    private $brand;

    /**
     * @var Model
     * @FieldType("LAH\FrontBundle\Entity\Model")
     * @Expose()
     */
    private $model;

    /**
     * @var Type
     * @Expose()
     * @FieldType("LAH\FrontBundle\Entity\Type")
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

    public function getQueryAsString()
    {
        $vars = $this->getQueryAsArray();

        return http_build_query($vars);
    }

    public function __toString()
    {
        return $this->getQueryAsString();
    }

    public function getQueryAsArray()
    {
        $vars = get_class_vars(__CLASS__);

        foreach ($vars as $var => $value) {
            if ($this->$var) {
                $vars[$var] = $this->$var->getId();
            } else {
                $vars[$var] = 'null';
            }
        }

        return $vars;
    }
}
