<?php
namespace Dende\FrontBundle\Tests\Functional;

use Dende\FrontBundle\Dictionary\Country;
use Dende\FrontBundle\Dictionary\Engine;
use Dende\FrontBundle\Dictionary\Fuel;
use Dende\FrontBundle\Dictionary\Gearbox;
use Dende\FrontBundle\Entity\Brand;
use Dende\FrontBundle\Entity\Car;
use Dende\FrontBundle\Entity\Model;
use Dende\FrontBundle\Entity\Type;
use Dende\FrontBundle\Entity\Color;
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

        $forms = $crawler->filter('form[name="dende_form_car"]');
        $this->assertEquals(1, $forms->count());

        $form = $forms->first();
        $this->assertCount(21, $form->filter('input, textarea, button, select'));

        $this->assertCount(2, $form->filter('textarea'));
        $this->assertCount(7, $form->filter('select'));
        $this->assertCount(1, $form->filter('button'));
        $this->assertCount(11, $form->filter('input'));
        $this->assertCount(6, $form->filter('input[type=text]'));
        $this->assertCount(2, $form->filter('input[type=number]'));
        $this->assertCount(3, $form->filter('input[type=checkbox]'));

        $this->assertCount(count($carTypes) + 1, $crawler->filter('select#dende_form_car_type option'));
        $this->assertCount(count($carModels) + 1, $crawler->filter('select#dende_form_car_model option'));
        $this->assertCount(count($carColors) + 1, $crawler->filter('select#dende_form_car_color option'));
        $this->assertCount(count(Fuel::$choicesArray) + 1, $crawler->filter('select#dende_form_car_fuel option'));
        $this->assertCount(count(Engine::$choicesArray) + 1, $crawler->filter('select#dende_form_car_engine option'));
        $this->assertCount(count(Gearbox::$choicesArray) + 1, $crawler->filter('select#dende_form_car_gearbox option'));
        $this->assertCount(count(Country::$choicesArray) + 1, $crawler->filter('select#dende_form_car_registrationCountry option'));
    }

    /**
     * @test
     */
    public function car_edit_form_renders_properly()
    {
        $em = $this->container->get("doctrine.orm.entity_manager");

        $qb = $em->getRepository("FrontBundle:Car")->createQueryBuilder("c");
        $query = $qb->orderBy('c.id', "ASC")->setMaxResults(1);
        /**
         * @var Car $carEntity
         */
        $carEntity = $query->getQuery()->getOneOrNullResult();

        $carTypes = $em->getRepository("FrontBundle:Type")->findAll();
        $carModels = $em->getRepository("FrontBundle:Model")->findAll();
        $carColors = $em->getRepository("FrontBundle:Color")->findAll();

        $crawler = $this->client->request('GET', '/car/edit/'.$carEntity->getId());

        $forms = $crawler->filter('form[name="dende_form_car"]');
        $this->assertEquals(1, $forms->count());

        $form = $forms->first();
        $this->assertCount(33, $form->filter('input, textarea, button, select'));

        $this->assertCount(2, $form->filter('textarea'));
        $this->assertCount(11, $form->filter('select'));
        $this->assertCount(1, $form->filter('button'));
        $this->assertCount(19, $form->filter('input'));
        $this->assertCount(6, $form->filter('input[type=text]'));
        $this->assertCount(6, $form->filter('input[type=number]'));
        $this->assertCount(3, $form->filter('input[type=checkbox]'));
        $this->assertCount($carEntity->getImages()->count(), $form->filter('input[type=file]'));

        $this->assertCount(count($carTypes) + 1, $crawler->filter('select#dende_form_car_type option'));
        $this->assertCount(count($carModels) + 1, $crawler->filter('select#dende_form_car_model option'));
        $this->assertCount(count($carColors) + 1, $crawler->filter('select#dende_form_car_color option'));
        $this->assertCount(count(Fuel::$choicesArray) + 1, $crawler->filter('select#dende_form_car_fuel option'));
        $this->assertCount(count(Engine::$choicesArray) + 1, $crawler->filter('select#dende_form_car_engine option'));
        $this->assertCount(count(Gearbox::$choicesArray) + 1, $crawler->filter('select#dende_form_car_gearbox option'));
        $this->assertCount(count(Country::$choicesArray) + 1, $crawler->filter('select#dende_form_car_registrationCountry option'));
    }

    /**
     * @test
     */
    public function car_add_form_is_posted_and_entity_is_added()
    {
        $crawler = $this->client->request('GET', '/car/add');
        $forms = $crawler->filter('form[name="dende_form_car"]');

        $title = md5("some title".microtime());

        $uploadedFile = new UploadedFile(
            realpath(__DIR__."/../../Resources/tests/test_image.jpg"),
            'someTestFile.jpg',
            'image/jpeg',
            123
        );

        $form = $forms->first()->form();

        $form->setValues([
            "dende_form_car[type]" => 1,
            "dende_form_car[model]" => 1,
            "dende_form_car[color]" => 1,
            "dende_form_car[year]" => 1990,
            "dende_form_car[distance]" => 40000,
            "dende_form_car[fuel]" => Fuel::DIESEL,
            "dende_form_car[engine]" => Engine::DIESEL,
            "dende_form_car[gearbox]" => Gearbox::AUTOMATIC,
            "dende_form_car[registrationCountry]" => Country::POLAND,
            "dende_form_car[promoteCarousel]" => true,
            "dende_form_car[promoteFrontpage]" => false,
            "dende_form_car[title]" => $title,
            "dende_form_car[description]" => 'Some description',
            "dende_form_car[adminNotes]" => 'Admin Notes',
            "dende_form_car[hidden]" => false,
        ]);

        $values = $form->getPhpValues();
        $values["dende_form_car"]["prices"] = [
            ["amount" => 20, "currency" => 1],
        ];

        $files = [
            "dende_form_car" => [
                "images" => [
                    ["file" => clone($uploadedFile)],
                    ["file" => clone($uploadedFile)],
                    ["file" => clone($uploadedFile)],
                ],
            ],
        ];

        $crawler = $this->client->request($form->getMethod(), $form->getUri(), $values, $files);

        $this->assertEquals(200, $this->getStatusCode());

        $alert = $crawler->filter('div.alert.alert-success');
        $this->assertEquals('flash.car_add.success', trim($alert->text()));

        $em = $this->container->get("doctrine.orm.entity_manager");
        /**
         * @var Car $entity
         */
        $entity = $em->getRepository("FrontBundle:Car")->findOneByTitle($title);

        $this->assertEquals("/car/edit/".$entity->getId(), $this->client->getRequest()->getRequestUri());
        $this->assertEquals("GET", $this->client->getRequest()->getMethod());

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
        $this->assertEquals($entity->getPrices()[0]->getAmount(), 20);
        $this->assertCount(3, $entity->getImages());
        $this->assertCount(1, $entity->getPrices());

        foreach ($entity->getImages() as $image) {
            $this->assertFileExists($image->getPath());
            unlink($image->getPath());
        }
    }

    /**
     * @test
     */
    public function car_edit_form_is_posted_and_entity_is_changed()
    {
        $em = $this->container->get("doctrine.orm.entity_manager");

        $qb = $em->getRepository("FrontBundle:Car")->createQueryBuilder("c");
        $query = $qb->orderBy('c.id', "ASC")->setMaxResults(1);
        /**
         * @var Car $carEntity
         */
        $carEntity = $query->getQuery()->getOneOrNullResult();

        $url = '/car/edit/'.$carEntity->getId();

        $crawler = $this->client->request('GET', $url);
        $forms = $crawler->filter('form[name="dende_form_car"]');

        $title = md5("some title".microtime());

        $uploadedFile = new UploadedFile(
            realpath(__DIR__."/../../Resources/tests/test_image.jpg"),
            'someTestFile.jpg',
            'image/jpeg',
            123
        );

        $form = $forms->first()->form();
        $values = $form->getPhpValues();
        $values["dende_form_car"]["title"] = $title;
        $values["dende_form_car"]["prices"] = [
            ["amount" => 30, "currency" => 2],
            ["amount" => 40, "currency" => 2],
        ];
        $newModelName = md5("some title".microtime());
        $values["dende_form_car"]["add_model"]["name"] = $newModelName;
        $values["dende_form_car"]["add_model"]["brand"] = $newModelName;

        $files = [
            "dende_form_car" => [
                "images" => [
                    ["file" => clone($uploadedFile)],
                    ["file" => clone($uploadedFile)],
                ],
            ],
        ];

        $crawler = $this->client->request("POST", $url, $values, $files);

        $this->assertEquals(200, $this->getStatusCode());

        $alert = $crawler->filter('div.alert.alert-success');
        $this->assertEquals('flash.car_edit.success', trim($alert->text()));

        $this->assertEquals("/car/edit/".$carEntity->getId(), $this->client->getRequest()->getRequestUri());
        $this->assertEquals("GET", $this->client->getRequest()->getMethod());

        $em->refresh($carEntity);

        $this->assertNotNull($carEntity, "Car entity can't be found");
        $this->assertEquals($carEntity->getTitle(), $title, "Entity haven't been updated in db");
        $this->assertCount(2, $carEntity->getImages());
        $this->assertCount(2, $carEntity->getPrices());
        $this->assertEquals($carEntity->getModel()->getName(), $newModelName, "Entity Model haven't been created in db");
        $this->assertEquals($carEntity->getModel()->getBrand()->getName(), $newModelName, "Entity Brand haven't been created in db");
    }

    /**
     * @test
     * @dataProvider getErrorGeneratingForms
     */
    public function car_add_form_is_posted_and_error_is_emitted($formData, $error)
    {
        $crawler = $this->client->request('GET', '/car/add');
        $forms = $crawler->filter('form[name="dende_form_car"]');

        $form = $forms->first()->form($formData);

        $crawler = $this->client->submit($form);

        $this->assertEquals(400, $this->getStatusCode());

        $alert = $crawler->filter('div.alert.alert-danger');
        $this->assertEquals('flash.car_edit.errors', trim($alert->text()));

        $this->assertContains($error, $crawler->text());
    }

    /**
     * @test
     * @dataProvider getErrorGeneratingForms
     */
    public function car_edit_form_is_posted_and_error_is_emitted($formData, $error)
    {
        $crawler = $this->client->request('GET', '/car/edit/1');
        $forms = $crawler->filter('form[name="dende_form_car"]');

        $form = $forms->first()->form($formData);

        $crawler = $this->client->submit($form);

        $this->assertEquals(400, $this->getStatusCode());

        $alert = $crawler->filter('div.alert.alert-danger');
        $this->assertEquals('flash.car_edit.errors', trim($alert->text()));

        $this->assertContains($error, $crawler->text());
    }

    public function getErrorGeneratingForms()
    {
        $correctData = [
            "dende_form_car[type]" => 1,
            "dende_form_car[model]" => 1,
            "dende_form_car[color]" => 1,
            "dende_form_car[year]" => 1990,
            "dende_form_car[distance]" => 40000,
            "dende_form_car[fuel]" => Fuel::DIESEL,
            "dende_form_car[engine]" => Engine::DIESEL,
            "dende_form_car[gearbox]" => Gearbox::AUTOMATIC,
            "dende_form_car[registrationCountry]" => Country::POLAND,
            "dende_form_car[promoteCarousel]" => true,
            "dende_form_car[promoteFrontpage]" => false,
            "dende_form_car[title]" => "Some title",
            "dende_form_car[description]" => 'Some description',
            "dende_form_car[adminNotes]" => 'Admin Notes',
            "dende_form_car[hidden]" => false,
        ];

        return [
            "empty title" => [
                "formData" => array_merge(
                    $correctData,
                    [
                        "dende_form_car[title]" => null,
                    ]
                ),
                "error" => 'validator.title_cannot_be_empty',
            ],
            "empty description" => [
                "formData" => array_merge(
                    $correctData,
                    [
                        "dende_form_car[description]" => null,
                    ]
                ),
                "error" => 'validator.description_cannot_be_empty',
            ],
            "empty type" => [
                "formData" => array_merge(
                    $correctData,
                    [
                        "dende_form_car[type]" => null,
                    ]
                ),
                "error" => 'validator.you_have_to_choose_car_type',
            ],
            "empty model" => [
                "formData" => array_merge(
                    $correctData,
                    [
                        "dende_form_car[model]" => null,
                    ]
                ),
                "error" => 'validator.you_have_to_choose_car_model',
            ],
            "empty color" => [
                "formData" => array_merge(
                    $correctData,
                    [
                        "dende_form_car[color]" => null,
                    ]
                ),
                "error" => 'validator.you_have_to_choose_car_color',
            ],
            "empty brand" => [
                "formData" => array_merge(
                    $correctData,
                    [
                        "dende_form_car[add_model][name]" => "someName",
                        "dende_form_car[add_model][brand]" => null,
                    ]
                ),
                "error" => 'validator.you_have_to_add_car_brand',
            ],
            "empty model, filled brand" => [
                "formData" => array_merge(
                    $correctData,
                    [
                        "dende_form_car[add_model][name]" => null,
                        "dende_form_car[add_model][brand]" => "brand",
                    ]
                ),
                "error" => 'validator.you_have_to_add_car_model_name',
            ],
        ];
    }

    /**
     * @test
     */
    public function new_type_is_being_created_while_editing_new_car()
    {
        $em = $this->container->get("doctrine.orm.entity_manager");

        $qb = $em->getRepository("FrontBundle:Car")->createQueryBuilder("c");
        $query = $qb->orderBy('c.id', "ASC")->setMaxResults(1);
        /**
         * @var Car $carEntity
         */
        $carEntity = $query->getQuery()->getOneOrNullResult();

        $url = '/car/edit/'.$carEntity->getId();

        $crawler = $this->client->request('GET', $url);
        $forms = $crawler->filter('form[name="dende_form_car"]');

        $newTypeName = md5("some title".microtime());

        $form = $forms->first()->form();
        $values = $form->getPhpValues();
        $values["dende_form_car"]["add_type"]["name"] = $newTypeName;

        $this->client->request("POST", $url, $values, $form->getFiles());

        $this->assertEquals(200, $this->getStatusCode());

        $em->refresh($carEntity);

        $this->assertNotNull($carEntity, "Car entity can't be found");
        $this->assertEquals($carEntity->getType()->getName(), $newTypeName, "Entity haven't been created in db");
    }

    /**
     * @test
     */
    public function new_color_is_being_created_while_adding_new_car()
    {
        $crawler = $this->client->request('GET', '/car/add');
        $forms = $crawler->filter('form[name="dende_form_car"]');

        $title = md5("some title".microtime());

        $form = $forms->first()->form();

        $newColorName = md5("some title".microtime());

        $form->setValues([
            "dende_form_car[type]" => 1,
            "dende_form_car[model]" => 1,
            "dende_form_car[color]" => 1,
            "dende_form_car[year]" => 1990,
            "dende_form_car[distance]" => 40000,
            "dende_form_car[fuel]" => Fuel::DIESEL,
            "dende_form_car[engine]" => Engine::DIESEL,
            "dende_form_car[gearbox]" => Gearbox::AUTOMATIC,
            "dende_form_car[registrationCountry]" => Country::POLAND,
            "dende_form_car[promoteCarousel]" => true,
            "dende_form_car[promoteFrontpage]" => false,
            "dende_form_car[title]" => $title,
            "dende_form_car[description]" => 'Some description',
            "dende_form_car[hidden]" => false,
            "dende_form_car[adminNotes]" => "notes",
            "dende_form_car[add_color][name]" => $newColorName,
            "dende_form_car[add_color][hex]" => "#ff00ff",
        ]);

        $this->client->request($form->getMethod(), $form->getUri(), $form->getPhpValues(), []);

        $this->assertEquals(200, $this->getStatusCode());

        $em = $this->container->get("doctrine.orm.entity_manager");
        /**
         * @var Car $carEntity
         */
        $carEntity = $em->getRepository("FrontBundle:Car")->findOneByTitle($title);

        $this->assertNotNull($carEntity, "Car entity not found");

        $this->assertEquals($carEntity->getColor()->getName(), $newColorName, "Entity Model haven't been created in db");
        $this->assertEquals($carEntity->getColor()->getHex(), "#ff00ff", "Entity Brand haven't been created in db");
    }

    /**
     * @test
     */
    public function new_color_is_being_created_while_editing_new_car()
    {
        $em = $this->container->get("doctrine.orm.entity_manager");

        $qb = $em->getRepository("FrontBundle:Car")->createQueryBuilder("c");
        $query = $qb->orderBy('c.id', "ASC")->setMaxResults(1);
        /**
         * @var Car $carEntity
         */
        $carEntity = $query->getQuery()->getOneOrNullResult();

        $url = '/car/edit/'.$carEntity->getId();

        $crawler = $this->client->request('GET', $url);
        $forms = $crawler->filter('form[name="dende_form_car"]');

        $newColorName = md5("some title".microtime());

        $form = $forms->first()->form();
        $values = $form->getPhpValues();
        $values["dende_form_car"]["add_color"]["name"] = $newColorName;
        $values["dende_form_car"]["add_color"]["hex"] = "#ff00ff";

        $this->client->request("POST", $url, $values, $form->getFiles());

        $this->assertEquals(200, $this->getStatusCode());

        $em->refresh($carEntity);

        $this->assertNotNull($carEntity, "Car entity can't be found");
        $this->assertEquals($carEntity->getColor()->getName(), $newColorName, "Entity Model haven't been created in db");
        $this->assertEquals($carEntity->getColor()->getHex(), "#ff00ff", "Entity Brand haven't been created in db");
    }

    /**
     * @test
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function new_model_is_being_created_while_editing_new_car()
    {
        $em = $this->container->get("doctrine.orm.entity_manager");

        $qb = $em->getRepository("FrontBundle:Car")->createQueryBuilder("c");
        $query = $qb->orderBy('c.id', "ASC")->setMaxResults(1);
        /**
         * @var Car $carEntity
         */
        $carEntity = $query->getQuery()->getOneOrNullResult();

        $url = '/car/edit/'.$carEntity->getId();

        $crawler = $this->client->request('GET', $url);
        $forms = $crawler->filter('form[name="dende_form_car"]');

        $newModelName = md5("some title".microtime());

        $form = $forms->first()->form();
        $values = $form->getPhpValues();
        $values["dende_form_car"]["add_model"]["name"] = $newModelName;
        $values["dende_form_car"]["add_model"]["brand"] = $newModelName;

        $this->client->request("POST", $url, $values, $form->getFiles());

        $this->assertEquals(200, $this->getStatusCode());

        $em->refresh($carEntity);

        $this->assertNotNull($carEntity, "Car entity can't be found");
        $this->assertEquals($carEntity->getModel()->getName(), $newModelName, "Entity Model haven't been created in db");
        $this->assertEquals($carEntity->getModel()->getBrand()->getName(), $newModelName, "Entity Brand haven't been created in db");
    }

    /**
     * @test
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function new_model_is_being_created_while_adding_new_car()
    {
        $crawler = $this->client->request('GET', '/car/add');
        $forms = $crawler->filter('form[name="dende_form_car"]');

        $title = md5("some title".microtime());

        $form = $forms->first()->form();

        $newModelName = md5("some title".microtime());

        $form->setValues([
            "dende_form_car[type]" => 1,
            "dende_form_car[model]" => null,
            "dende_form_car[color]" => 1,
            "dende_form_car[year]" => 1990,
            "dende_form_car[distance]" => 40000,
            "dende_form_car[fuel]" => Fuel::DIESEL,
            "dende_form_car[engine]" => Engine::DIESEL,
            "dende_form_car[gearbox]" => Gearbox::AUTOMATIC,
            "dende_form_car[registrationCountry]" => Country::POLAND,
            "dende_form_car[promoteCarousel]" => true,
            "dende_form_car[promoteFrontpage]" => false,
            "dende_form_car[title]" => $title,
            "dende_form_car[description]" => 'Some description',
            "dende_form_car[hidden]" => false,
            "dende_form_car[adminNotes]" => "notes",
            "dende_form_car[add_model][name]" => $newModelName,
            "dende_form_car[add_model][brand]" => $newModelName,
        ]);

        $this->client->request($form->getMethod(), $form->getUri(), $form->getPhpValues(), []);

        $this->assertEquals(200, $this->getStatusCode());

        $em = $this->container->get("doctrine.orm.entity_manager");
        /**
         * @var Car $entity
         */
        $carEntity = $em->getRepository("FrontBundle:Car")->findOneByTitle($title);

        $this->assertEquals($carEntity->getModel()->getName(), $newModelName, "Entity Model haven't been created in db");
        $this->assertEquals($carEntity->getModel()->getBrand()->getName(), $newModelName, "Entity Brand haven't been created in db");
    }

    /**
     * @test
     */
    public function new_model_type_color_brand_are_not_created_but_reused_when_adding_car()
    {
        $em = $this->container->get("doctrine.orm.entity_manager");

        $colorQuery = $em->getRepository("FrontBundle:Color")->createQueryBuilder("c")->orderBy('c.id', "ASC")->setMaxResults(1);
        $modelQuery = $em->getRepository("FrontBundle:Model")->createQueryBuilder("c")->orderBy('c.id', "ASC")->setMaxResults(1);
        $brandQuery = $em->getRepository("FrontBundle:Brand")->createQueryBuilder("c")->orderBy('c.id', "ASC")->setMaxResults(1);
        $typeQuery = $em->getRepository("FrontBundle:Type")->createQueryBuilder("c")->orderBy('c.id', "ASC")->setMaxResults(1);

        /**
         * @var Color $color
         */
        $color = $colorQuery->getQuery()->getOneOrNullResult();

        /**
         * @var Model $model
         */
        $model = $modelQuery->getQuery()->getOneOrNullResult();

        /**
         * @var Brand $brand
         */
        $brand = $brandQuery->getQuery()->getOneOrNullResult();

        /**
         * @var Type $type
         */
        $type = $typeQuery->getQuery()->getOneOrNullResult();

        $crawler = $this->client->request('GET', '/car/add');
        $forms = $crawler->filter('form[name="dende_form_car"]');

        $title = md5("some title".microtime());

        $form = $forms->first()->form();

        $form->setValues([
            "dende_form_car[type]" => 1,
            "dende_form_car[model]" => 1,
            "dende_form_car[color]" => 1,
            "dende_form_car[year]" => 1990,
            "dende_form_car[distance]" => 40000,
            "dende_form_car[fuel]" => Fuel::DIESEL,
            "dende_form_car[engine]" => Engine::DIESEL,
            "dende_form_car[gearbox]" => Gearbox::AUTOMATIC,
            "dende_form_car[registrationCountry]" => Country::POLAND,
            "dende_form_car[promoteCarousel]" => true,
            "dende_form_car[promoteFrontpage]" => false,
            "dende_form_car[title]" => $title,
            "dende_form_car[description]" => 'Some description',
            "dende_form_car[hidden]" => false,
            "dende_form_car[adminNotes]" => "notes",
            "dende_form_car[add_model][name]" => $model->getName(),
            "dende_form_car[add_model][brand]" => $brand->getName(),
            "dende_form_car[add_color][name]" => $color->getName(),
            "dende_form_car[add_color][hex]" => $color->getHex(),
            "dende_form_car[add_type][name]" => $type->getName(),
        ]);

        $this->client->request($form->getMethod(), $form->getUri(), $form->getPhpValues(), []);

        $this->assertEquals(200, $this->getStatusCode());

        $em = $this->container->get("doctrine.orm.entity_manager");
        /**
         * @var Car $carEntity
         */
        $carEntity = $em->getRepository("FrontBundle:Car")->findOneByTitle($title);

        $em->refresh($color);
        $em->refresh($type);
        $em->refresh($brand);
        $em->refresh($model);

        $this->assertNotNull($carEntity);

        $this->assertEquals($color->getId(), $carEntity->getColor()->getId());
        $this->assertEquals($type->getId(), $carEntity->getType()->getId());
        $this->assertEquals($brand->getId(), $carEntity->getModel()->getBrand()->getId());
        $this->assertEquals($model->getId(), $carEntity->getModel()->getId());
    }

    /**
     * @test
     */
    public function new_model_type_color_brand_are_not_created_but_reused_when_editing_car()
    {
        $em = $this->container->get("doctrine.orm.entity_manager");

        $colorQuery = $em->getRepository("FrontBundle:Color")->createQueryBuilder("c")->orderBy('c.id', "ASC")->setMaxResults(1);
        $modelQuery = $em->getRepository("FrontBundle:Model")->createQueryBuilder("c")->orderBy('c.id', "ASC")->setMaxResults(1);
        $brandQuery = $em->getRepository("FrontBundle:Brand")->createQueryBuilder("c")->orderBy('c.id', "ASC")->setMaxResults(1);
        $typeQuery = $em->getRepository("FrontBundle:Type")->createQueryBuilder("c")->orderBy('c.id', "ASC")->setMaxResults(1);

        /**
         * @var Color $color
         */
        $color = $colorQuery->getQuery()->getOneOrNullResult();

        /**
         * @var Model $model
         */
        $model = $modelQuery->getQuery()->getOneOrNullResult();

        /**
         * @var Brand $brand
         */
        $brand = $brandQuery->getQuery()->getOneOrNullResult();

        /**
         * @var Type $type
         */
        $type = $typeQuery->getQuery()->getOneOrNullResult();

        $qb = $em->getRepository("FrontBundle:Car")->createQueryBuilder("c");
        $query = $qb->orderBy('c.id', "ASC")->setMaxResults(1);
        /**
         * @var Car $carEntity
         */
        $carEntity = $query->getQuery()->getOneOrNullResult();

        $url = '/car/edit/'.$carEntity->getId();

        $crawler = $this->client->request('GET', $url);
        $forms = $crawler->filter('form[name="dende_form_car"]');

        $title = md5("some title".microtime());

        $form = $forms->first()->form();

        $values = $form->getPhpValues();

        $values["dende_form_car"]["title"] = $title;
        $values["dende_form_car"]["add_model"]["name"] = $model->getName();
        $values["dende_form_car"]["add_model"]["brand"] = $brand->getName();
        $values["dende_form_car"]["add_color"]["name"] = $color->getName();
        $values["dende_form_car"]["add_color"]["hex"] = $color->getHex();
        $values["dende_form_car"]["add_type"]["name"] = $type->getName();

        $this->client->request($form->getMethod(), $form->getUri(), $values, []);

        $this->assertEquals(200, $this->getStatusCode());

        $em = $this->container->get("doctrine.orm.entity_manager");
        /**
         * @var Car $carEntity
         */
        $em->refresh($carEntity);
        $em->refresh($color);
        $em->refresh($type);
        $em->refresh($brand);
        $em->refresh($model);

        $this->assertNotNull($carEntity);

        $this->assertEquals($title, $carEntity->getTitle());
        $this->assertEquals($color->getId(), $carEntity->getColor()->getId());
        $this->assertEquals($type->getId(), $carEntity->getType()->getId());
        $this->assertEquals($brand->getId(), $carEntity->getModel()->getBrand()->getId());
        $this->assertEquals($model->getId(), $carEntity->getModel()->getId());
    }
}
