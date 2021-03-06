<?php
namespace LAH\SearchBundle\Tests\Functional;

use LAH\MainBundle\Entity\Brand;
use LAH\MainBundle\Entity\Model;
use LAH\MainBundle\Entity\Type;
use LAH\MainBundle\Tests\BaseFunctionalTest;
use Symfony\Component\DomCrawler\Form;

class SearchFormTest extends BaseFunctionalTest
{
    /**
     * @test
     * @group read-only
     */
    public function search_form_renders_properly()
    {
        $em = $this->container->get('doctrine.orm.entity_manager');

        $carTypes  = $em->getRepository('LAHMainBundle:Type')->findAll();
        $carModels = $em->getRepository('LAHMainBundle:Model')->findAll();
        $carBrands = $em->getRepository('LAHMainBundle:Brand')->findAll();

        $crawler = $this->client->request('GET', $this->container->get('router')->generate('list'));
        $this->assertEquals(200, $this->getStatusCode());

        $forms = $crawler->filter('form[name="search"]');
        $this->assertEquals(1, $forms->count());

        $form = $forms->first();
        $this->assertCount(3, $form->filter('select'));

        $this->assertCount(count($carTypes) + 1, $crawler->filter('select#car_filters_variant option'));
        $this->assertCount(count($carModels) + 1, $crawler->filter('select#car_filters_model option'));
        $this->assertCount(count($carBrands) + 1, $crawler->filter('select#car_filters_brand option'));
    }

    /**
     * @test
     * @group read-only
     * @dataProvider searchDataProvider
     */
    public function i_can_find_cars_by_submitting_search_form($params, $resultsCountExpected, $countExpected, $pagesExpected = 1)
    {
        $em = $this->container->get('doctrine.orm.entity_manager');

        $type  = $em->getRepository('LAHMainBundle:Type')->findOneByName($params['type']);
        $model = $em->getRepository('LAHMainBundle:Model')->findOneByName($params['model']);
        $brand = $em->getRepository('LAHMainBundle:Brand')->findOneByName($params['brand']);

        $crawler = $this->client->request('GET', $this->container->get('router')->generate('list'));
        $this->assertEquals(200, $this->getStatusCode());

        $forms = $crawler->filter('form[name="search"]');
        $this->assertEquals(1, $forms->count());

        /*
         * @var Form
         */
        $form = $forms->first()->form();

        $form->setValues([
            'search[type]'  => is_object($type) ? $type->getId() : $type,
            'search[model]' => is_object($model) ? $model->getId() : $model,
            'search[brand]' => is_object($brand) ? $brand->getId() : $brand,
        ]);

        $crawler      = $this->client->submit($form);
        $resultsCount = (int) $crawler->filter('span#list-result-count')->text();
        $carsCount    = (int) $crawler->filter('ul.search-results li')->count();
        $pages    = (int) $crawler->filter('div.pagination:first-child span')->count();

        $this->assertEquals($resultsCountExpected, $resultsCount);

        if ($countExpected === 0) {
            $this->assertEquals(1, $carsCount);
            $this->assertContains("search.no_results", $crawler->filter('ul.search-results li')->text());
        } else {
            $this->assertEquals($countExpected, $carsCount);
        }


        $this->assertEquals($pagesExpected, $pages == 0 ? 1 : $pages - 2);
    }

    public function searchDataProvider()
    {
        return [
            'search-by-model-1' => [
                'params' => [
                    'model' => 'Golf',
                    'brand' => null,
                    'type'  => null,
                ],
                'countExpected' => 3,
                'resultsCountExpected' => 3,
            ],
            'search-by-model-2' => [
                'params' => [
                    'model' => 'A3',
                    'brand' => null,
                    'type'  => null,
                ],
                'countExpected' => 0,
                'resultsCountExpected' => 0,
            ],
            'search-by-brand-1' => [
                'params' => [
                    'model' => null,
                    'brand' => 'Audi',
                    'type'  => null,
                ],
                'countExpected' => 8,
                'resultsCountExpected' => 8,
            ],
            'search-by-brand-2' => [
                'params' => [
                    'model' => null,
                    'brand' => 'BMW',
                    'type'  => null,
                ],
                'countExpected' => 0,
                'resultsCountExpected' => 0,
            ],
            'search-by-type-1' => [
                'params' => [
                    'model' => null,
                    'brand' => null,
                    'type'  => 'Sedan',
                ],
                'countExpected' => 11,
                'resultsCountExpected' => 10,
                'pagesExpected' => 2,
            ],
            'search-by-type-2' => [
                'params' => [
                    'model' => null,
                    'brand' => null,
                    'type'  => 'Hatchback',
                ],
                'countExpected' => 0,
                'resultsCountExpected' => 0,
            ],
            'search-by-mixed-1' => [
                'params' => [
                    'type'  => 'Sedan',
                    'brand' => 'VolksWagen',
                    'model' => 'Golf',
                ],
                'countExpected' => 3,
                'resultsCountExpected' => 3,
            ],
            'search-by-mixed-2' => [
                'params' => [
                    'type'  => 'Coupe',
                    'brand' => 'VolksWagen',
                    'model' => 'Golf',
                ],
                'countExpected' => 0,
                'resultsCountExpected' => 0,
            ],
        ];
    }

    /**
     * @test
     * @group read-only
     */
    public function i_submit_bad_data_with_search_form()
    {
        $em = $this->container->get('doctrine.orm.entity_manager');

        $brand = $em->getRepository('LAHMainBundle:Brand')->findOneByName('VolksWagen');
        $model = $em->getRepository('LAHMainBundle:Model')->findOneByName('A3');

        $crawler = $this->client->request('GET', $this->container->get('router')->generate('list'));
        $this->assertEquals(200, $this->getStatusCode());

        $forms = $crawler->filter('form[name="search"]');
        $this->assertEquals(1, $forms->count());

        /*
         * @var Form
         */
        $form = $forms->first()->form();

        $form->setValues([
            'search[model]' => $model->getId(),
            'search[brand]' => $brand->getId(),
        ]);

        $crawler      = $this->client->submit($form);
        $resultsCount = (int) $crawler->filter('span#list-result-count')->text();
        $carsCount    = (int) $crawler->filter('ul.search-results li')->count();

        $this->assertEquals(0, $resultsCount);
        $this->assertEquals(1, $carsCount);

        $this->assertContains('This value is not valid.', $crawler->text());
        $this->assertContains("search.no_results", $crawler->filter('ul.search-results li')->text());
    }
}
