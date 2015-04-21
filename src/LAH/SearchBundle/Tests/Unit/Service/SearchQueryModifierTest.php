<?php

namespace LAH\SearchBundle\Tests\Unit\Service;

use LAH\SearchBundle\Service\SearchQueryModifier;
use Mockery as m;

class SearchQueryModifierTest extends \PHPUnit_Framework_TestCase
{
    public function testModify()
    {
        $typeMock = m::mock("LAH\MainBundle\Entity\Type");
        $typeMock->shouldReceive("getId")->once()->andReturn("type_id");
        $brandMock = m::mock("LAH\MainBundle\Entity\Brand");
        $brandMock->shouldReceive("getId")->once()->andReturn("brand_id");
        $modelMock = m::mock("LAH\MainBundle\Entity\Model");
        $modelMock->shouldReceive("getId")->once()->andReturn("model_id");

        $searchQueryMock = m::mock("LAH\SearchBundle\Model\SearchQuery");
        $searchQueryMock->shouldReceive("getType")->once()->andReturn($typeMock);
        $searchQueryMock->shouldReceive("getBrand")->once()->andReturn($brandMock);
        $searchQueryMock->shouldReceive("getModel")->once()->andReturn($modelMock);

        $modifier = new SearchQueryModifier();

        $queryBuilder = m::mock("Doctrine\ORM\QueryBuilder");
        $queryBuilder->shouldReceive("setParameter")->with('type', $typeMock)->once();
        $queryBuilder->shouldReceive("setParameter")->with('brand', $brandMock)->once();
        $queryBuilder->shouldReceive("setParameter")->with('carModel', $modelMock)->once();
        $queryBuilder->shouldReceive("andWhere")->times(3);
        $queryBuilder->shouldReceive("innerJoin")->once();

        $cacheId = [];
        $modifier->modify($searchQueryMock, $queryBuilder, $cacheId);

        $this->assertEquals(["type_id", "brand_id", "model_id"], $cacheId);
    }

    public function tearDown()
    {
        m::close();
    }
}
