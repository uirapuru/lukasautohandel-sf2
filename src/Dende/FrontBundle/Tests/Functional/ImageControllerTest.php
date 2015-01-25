<?php
namespace Dende\FrontBundle\Tests\Functional;

use Dende\FrontBundle\Dictionary\Country;
use Dende\FrontBundle\Dictionary\Engine;
use Dende\FrontBundle\Dictionary\Fuel;
use Dende\FrontBundle\Dictionary\Gearbox;
use Dende\FrontBundle\Entity\Car;
use Dende\FrontBundle\Entity\Image;
use Dende\FrontBundle\Tests\BaseFunctionalTest;

class ImageControllerTest extends BaseFunctionalTest
{
    /**
     * @test
     */
    public function image_add_form_renders_properly()
    {
        $this->markTestSkipped("image controller no more used");
        $em = $this->container->get("doctrine.orm.entity_manager");

        $crawler = $this->client->request('GET', '/image/add');

        $forms = $crawler->filter('form[name="dende_form_add_image"]');
        $this->assertEquals(1, $forms->count());

        $form = $forms->first();
        $this->assertCount(3, $form->filter('input, button, select'));

        $this->assertCount(0, $form->filter('select'));
        $this->assertCount(1, $form->filter('button'));
        $this->assertCount(1, $form->filter('input[type=file]'));
        $this->assertCount(1, $form->filter('input[type=hidden]'));
    }

    /**
     * @test
     */
    public function image_form_is_posted_and_entity_is_added()
    {
        $this->markTestSkipped("image controller no more used");
        $crawler = $this->client->request('GET', '/image/add');
        $forms = $crawler->filter('form[name="dende_form_add_image"]');

        $form = $forms->first()->form([
            "dende_form_add_image[car]" => 1,
        ]);

        $form["dende_form_add_image[file]"]->upload(realpath(__DIR__."/../../Resources/tests/test_image.jpg"));

        $this->client->submit($form);

        $this->assertEquals(200, $this->getStatusCode());

        $em = $this->container->get("doctrine.orm.entity_manager");

        $qb = $em->getRepository("FrontBundle:Image")->createQueryBuilder("i");
        $query = $qb->orderBy('i.id', "DESC")->setMaxResults(1);

        /**
         * @var Image $lastImage
         */
        $lastImage = $query->getQuery()->getOneOrNullResult();

        $this->assertNotNull($lastImage->getMimeType(), "Mime type is null");
        $this->assertNotNull($lastImage->getPath(), "Path is null");
        $this->assertNotNull($lastImage->getSize(), "Size is null");
        $this->assertNotNull($lastImage->getName(), "Name is null");
        $this->assertFileExists("web/uploads/".$lastImage->getName(), "File does not exists in web/uploads");

        unlink($lastImage->getPath());
    }

    /**
     * test
     * dataProvider getErrorGeneratingForms
     */
    public function car_form_is_posted_and_error_is_emitted($formData)
    {
        $this->markTestSkipped("image controller no more used");
        $crawler = $this->client->request('GET', '/car/add');
        $forms = $crawler->filter('form[name="dende_form_add_image"]');

        $form = $forms->first()->form($formData);

        $this->client->submit($form);

        $this->assertEquals(400, $this->getStatusCode());
    }

    public function getErrorGeneratingForms()
    {
        return [
            "empty title and description" => [
              "formData" => [
                  "dende_form_add_image[type]" => 1,
                  "dende_form_add_image[model]" => 1,
                  "dende_form_add_image[color]" => 1,
                  "dende_form_add_image[year]" => 1990,
                  "dende_form_add_image[distance]" => 40000,
                  "dende_form_add_image[fuel]" => Fuel::DIESEL,
                  "dende_form_add_image[engine]" => Engine::DIESEL,
                  "dende_form_add_image[gearbox]" => Gearbox::AUTOMATIC,
                  "dende_form_add_image[registrationCountry]" => Country::POLAND,
                  "dende_form_add_image[promoteCarousel]" => true,
                  "dende_form_add_image[promoteFrontpage]" => false,
                  "dende_form_add_image[title]" => null,
                  "dende_form_add_image[description]" => null,
                  "dende_form_add_image[adminNotes]" => 'Admin Notes',
                  "dende_form_add_image[hidden]" => false,
              ]
            ],
        ];
    }
}