<?php
namespace Dende\FrontBundle\Tests\Unit\Model;

use Dende\FrontBundle\Entity\Brand;
use Dende\FrontBundle\Entity\Model;
use Dende\FrontBundle\Entity\Type;
use Dende\FrontBundle\Model\SearchQuery;

class SearchQueryTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @param $type
     * @param $model
     * @param $brand
     * @dataProvider getQueryAsStringDataProvider
     */
    public function testGetQueryAsString($type, $model, $brand, $url)
    {
        $query = new SearchQuery();
        $query->setBrand($brand);
        $query->setModel($model);
        $query->setType($type);

        $resultUrl = $query->getQueryAsString();

        $this->assertEquals($url, $resultUrl);
    }

    public function getQueryAsStringDataProvider()
    {
        $type = new Type();
        $type->setId(22);

        $brand = new Brand();
        $brand->setId(33);

        $model = new Model();
        $model->setId(44);

        return [
            "full" => [
                "type" => $type,
                "model" => $model,
                "brand" => $brand,
                "url" => "brand=33&model=44&type=22",
            ],
            "type" => [
                "type" => $type,
                "model" => null,
                "brand" => null,
                "url" => "brand=null&model=null&type=22",
            ],
            "model" => [
                "type" => null,
                "model" => $model,
                "brand" => null,
                "url" => "brand=null&model=44&type=null",
            ],
            "brand" => [
                "type" => null,
                "model" => null,
                "brand" => $brand,
                "url" => "brand=33&model=null&type=null",
            ],
            "type-model" => [
                "type" => $type,
                "model" => $model,
                "brand" => null,
                "url" => "brand=null&model=44&type=22",
            ],
            "type-brand" => [
                "type" => $type,
                "model" => null,
                "brand" => $brand,
                "url" => "brand=33&model=null&type=22",
            ],
            "model-brand" => [
                "type" => null,
                "model" => $model,
                "brand" => $brand,
                "url" => "brand=33&model=44&type=null",
            ],
            "empty" => [
                "type" => null,
                "model" => null,
                "brand" => null,
                "url" => "brand=null&model=null&type=null",
            ],
        ];
    }
}
