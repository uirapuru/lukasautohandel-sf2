<?php
namespace Dende\FrontBundle\Tests\Functional;

use Dende\FrontBundle\Dictionary\Country;
use Dende\FrontBundle\Dictionary\Engine;
use Dende\FrontBundle\Dictionary\Fuel;
use Dende\FrontBundle\Dictionary\Gearbox;
use Dende\FrontBundle\Entity\Car;
use Dende\FrontBundle\Tests\BaseFunctionalTest;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class CarControllerTest extends BaseFunctionalTest
{
    /**
     * @test
     */
    public function car_add_form_renders_properly()
    {
        $em = $this->container->get("doctrine.orm.entity_manager");

        $carTypes = $em->getRepository("FrontBundle:Type")->findAll();
        $carModels = $em->getRepository("FrontBundle:Model")->findAll();
        $carColors = $em->getRepository("FrontBundle:Color")->findAll();

        $crawler = $this->client->request('GET', '/car/add');

        $forms = $crawler->filter('form[name="dende_form_add_car"]');
        $this->assertEquals(1, $forms->count());

        $form = $forms->first();
        $this->assertCount(16, $form->filter('input, textarea, button, select'));

        $this->assertCount(2, $form->filter('textarea'));
        $this->assertCount(7, $form->filter('select'));
        $this->assertCount(1, $form->filter('button'));
        $this->assertCount(6, $form->filter('input'));
        $this->assertCount(1, $form->filter('input[type=text]'));
        $this->assertCount(2, $form->filter('input[type=number]'));
        $this->assertCount(3, $form->filter('input[type=checkbox]'));

        $this->assertCount(count($carTypes) + 1, $crawler->filter('select#dende_form_add_car_type option'));
        $this->assertCount(count($carModels) + 1, $crawler->filter('select#dende_form_add_car_model option'));
        $this->assertCount(count($carColors) + 1, $crawler->filter('select#dende_form_add_car_color option'));
        $this->assertCount(count(Fuel::$choicesArray) + 1, $crawler->filter('select#dende_form_add_car_fuel option'));
        $this->assertCount(count(Engine::$choicesArray) + 1, $crawler->filter('select#dende_form_add_car_engine option'));
        $this->assertCount(count(Gearbox::$choicesArray) + 1, $crawler->filter('select#dende_form_add_car_gearbox option'));
        $this->assertCount(count(Country::$choicesArray) + 1, $crawler->filter('select#dende_form_add_car_registrationCountry option'));
    }

    /**
     * @test
     */
    public function car_form_is_posted_and_entity_is_added()
    {
        $crawler = $this->client->request('GET', '/car/add');
        $forms = $crawler->filter('form[name="dende_form_add_car"]');

        $title = md5("some title" . microtime());

        $uploadedFile = new UploadedFile(
            realpath(__DIR__."/../../Resources/tests/test_image.jpg"),
            'someTestFile.jpg',
            'image/jpeg',
            123
        );

        $form = $forms->first()->form();

        $form->setValues([
            "dende_form_add_car[type]" => 1,
            "dende_form_add_car[model]" => 1,
            "dende_form_add_car[color]" => 1,
            "dende_form_add_car[year]" => 1990,
            "dende_form_add_car[distance]" => 40000,
            "dende_form_add_car[fuel]" => Fuel::DIESEL,
            "dende_form_add_car[engine]" => Engine::DIESEL,
            "dende_form_add_car[gearbox]" => Gearbox::AUTOMATIC,
            "dende_form_add_car[registrationCountry]" => Country::POLAND,
            "dende_form_add_car[promoteCarousel]" => true,
            "dende_form_add_car[promoteFrontpage]" => false,
            "dende_form_add_car[title]" => $title,
            "dende_form_add_car[description]" => 'Some description',
            "dende_form_add_car[adminNotes]" => 'Admin Notes',
            "dende_form_add_car[hidden]" => false,
        ]);

        $files = [
            "dende_form_add_car" => [
                "images" => [
                    ["file" => clone($uploadedFile)],
                    ["file" => clone($uploadedFile)],
                    ["file" => clone($uploadedFile)],
                ]
            ]
        ];

        $this->client->request($form->getMethod(), $form->getUri(), $form->getPhpValues(), $files);

        $this->assertEquals(200, $this->getStatusCode());

        $em = $this->container->get("doctrine.orm.entity_manager");
        /**
         * @var Car $entity
         */
        $entity = $em->getRepository("FrontBundle:Car")->findOneByTitle($title);

        $this->assertEquals($entity->getType()->getId(), 1);
        $this->assertEquals($entity->getModel()->getId(), 1);
        $this->assertEquals($entity->getColor()->getId(), 1);
        $this->assertEquals($entity->getYear(), 1990);
        $this->assertEquals($entity->getDistance(), 40000);
        $this->assertEquals($entity->getFuel(), Fuel::DIESEL);
        $this->assertEquals($entity->getEngine(), Engine::DIESEL);
        $this->assertEquals($entity->getGearbox(), Gearbox::AUTOMATIC);
        $this->assertEquals($entity->getRegistrationCountry(), Country::POLAND);
        $this->assertEquals($entity->isPromoteCarousel(), true);
        $this->assertEquals($entity->isPromoteFrontpage(), false);
        $this->assertEquals($entity->getTitle(), $title);
        $this->assertEquals($entity->getDescription(), 'Some description');
        $this->assertEquals($entity->getAdminNotes(), 'Admin Notes');
        $this->assertEquals($entity->isHidden(), false);
        $this->assertCount(3, $entity->getImages());

        foreach($entity->getImages() as $image) {
            $this->assertFileExists($image->getPath());
            unlink($image->getPath());
        }
    }

    /**
     * @test
     * @dataProvider getErrorGeneratingForms
     */
    public function car_form_is_posted_and_error_is_emitted($formData)
    {
        $crawler = $this->client->request('GET', '/car/add');
        $forms = $crawler->filter('form[name="dende_form_add_car"]');

        $form = $forms->first()->form($formData);

        $this->client->submit($form);

        $this->assertEquals(400, $this->getStatusCode());
    }

    public function getErrorGeneratingForms()
    {
        return [
            "empty title and description" => [
              "formData" => [
                  "dende_form_add_car[type]" => 1,
                  "dende_form_add_car[model]" => 1,
                  "dende_form_add_car[color]" => 1,
                  "dende_form_add_car[year]" => 1990,
                  "dende_form_add_car[distance]" => 40000,
                  "dende_form_add_car[fuel]" => Fuel::DIESEL,
                  "dende_form_add_car[engine]" => Engine::DIESEL,
                  "dende_form_add_car[gearbox]" => Gearbox::AUTOMATIC,
                  "dende_form_add_car[registrationCountry]" => Country::POLAND,
                  "dende_form_add_car[promoteCarousel]" => true,
                  "dende_form_add_car[promoteFrontpage]" => false,
                  "dende_form_add_car[title]" => null,
                  "dende_form_add_car[description]" => null,
                  "dende_form_add_car[adminNotes]" => 'Admin Notes',
                  "dende_form_add_car[hidden]" => false,
              ]
            ],
        ];
    }
}