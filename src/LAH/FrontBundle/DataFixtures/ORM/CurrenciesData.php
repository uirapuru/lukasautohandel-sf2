<?php
namespace LAH\FrontBundle\DataFixtures\ORM;

use Dende\CommonBundle\DataFixtures\BaseFixtures;
use Dende\CommonBundle\DataFixtures\FixtureInterface;
use LAH\FrontBundle\Entity\Currency;

class CurrenciesData extends BaseFixture
{
    public function getOrder()
    {
        return 0;
    }

    public function insert($params)
    {
        $currency = new Currency();
        $currency->setSymbol($params['symbol']);
        $currency->setType($params['type']);
        $currency->setCode($params['code']);

        return $currency;
    }
}
