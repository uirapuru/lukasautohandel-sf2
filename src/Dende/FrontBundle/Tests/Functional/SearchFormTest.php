<?php
namespace Dende\FrontBundle\Tests\Functional;

use Dende\FrontBundle\Entity\Brand;
use Dende\FrontBundle\Entity\Model;
use Dende\FrontBundle\Entity\Type;
use Dende\FrontBundle\Tests\BaseFunctionalTest;
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

        $carTypes  = $em->getRepository('FrontBundle:Type')->findAll();
        $carModels = $em->getRepository('FrontBundle:Model')->findAll();
        $carBrands = $em->getRepository('FrontBundle:Brand')->findAll();

        $crawler = $this->client->request('GET', $this->container->get('router')->generate('list'));
        $this->assertEquals(200, $this->getStatusCode());

        $forms = $crawler->filter('form[name="dende_form_search"]');
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
    public function i_can_find_cars_by_submitting_search_form($params, $count)
    {
        $em = $this->container->get('doctrine.orm.entity_manager');

        $type  = $em->getRepository('FrontBundle:Type')->findOneByName($params['type']);
        $model = $em->getRepository('FrontBundle:Model')->findOneByName($params['model']);
        $brand = $em->getRepository('FrontBundle:Brand')->findOneByName($params['brand']);

        $crawler = $this->client->request('GET', $this->container->get('router')->generate('list'));
        $this->assertEquals(200, $this->getStatusCode());

        $forms = $crawler->filter('form[name="dende_form_search"]');
        $this->assertEquals(1, $forms->count());

        /*
         * @var Form
         */
        $form = $forms->first()->form();

        $form->setValues([
            'dende_form_search[type]'  => is_object($type) ? $type->getId() : $type,
            'dende_form_search[model]' => is_object($model) ? $model->getId() : $model,
            'dende_form_search[brand]' => is_object($brand) ? $brand->getId() : $brand,
        ]);

        $crawler      = $this->client->submit($form);
        $resultsCount = (int) $crawler->filter('span#list-result-count')->text();
        $carsCount    = (int) $crawler->filter('ul.search-results li')->count();

        $this->assertEquals($count, $resultsCount);
        $this->assertEquals($count, $carsCount);
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
                'count' => 3,
            ],
            'search-by-model-2' => [
                'params' => [
                    'model' => 'A3',
                    'brand' => null,
                    'type'  => null,
                ],
                'count' => 0,
            ],
            'search-by-brand-1' => [
                'params' => [
                    'model' => null,
                    'brand' => 'Audi',
                    'type'  => null,
                ],
                'count' => 8,
            ],
            'search-by-brand-2' => [
                'params' => [
                    'model' => null,
                    'brand' => 'BMW',
                    'type'  => null,
                ],
                'count' => 0,
            ],
            'search-by-type-1' => [
                'params' => [
                    'model' => null,
                    'brand' => null,
                    'type'  => 'Sedan',
                ],
                'count' => 11,
            ],
            'search-by-type-2' => [
                'params' => [
                    'model' => null,
                    'brand' => null,
                    'type'  => 'Hatchback',
                ],
                'count' => 0,
            ],
            'search-by-mixed-1' => [
                'params' => [
                    'type'  => 'Sedan',
                    'brand' => 'VolksWagen',
                    'model' => 'Golf',
                ],
                'count' => 3,
            ],
            'search-by-mixed-2' => [
                'params' => [
                    'type'  => 'Coupe',
                    'brand' => 'VolksWagen',
                    'model' => 'Golf',
                ],
                'count' => 0,
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

        $brand = $em->getRepository('FrontBundle:Brand')->findOneByName('VolksWagen');
        $model = $em->getRepository('FrontBundle:Model')->findOneByName('A3');

        $crawler = $this->client->request('GET', $this->container->get('router')->generate('list'));
        $this->assertEquals(200, $this->getStatusCode());

        $forms = $crawler->filter('form[name="dende_form_search"]');
        $this->assertEquals(1, $forms->count());

        /*
         * @var Form
         */
        $form = $forms->first()->form();

        $form->setValues([
            'dende_form_search[model]' => $model->getId(),
            'dende_form_search[brand]' => $brand->getId(),
        ]);

        $crawler      = $this->client->submit($form);
        $resultsCount = (int) $crawler->filter('span#list-result-count')->text();
        $carsCount    = (int) $crawler->filter('ul.search-results li')->count();

        $this->assertEquals(0, $resultsCount);
        $this->assertEquals(0, $carsCount);

        $this->assertContains('This value is not valid.', $crawler->text());
    }
}
