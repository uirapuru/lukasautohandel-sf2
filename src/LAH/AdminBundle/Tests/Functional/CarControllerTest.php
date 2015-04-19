<?php
namespace LAH\AdminBundle\Tests\Functional;

use LAH\MainBundle\Dictionary\Country;
use LAH\MainBundle\Dictionary\Engine;
use LAH\MainBundle\Dictionary\Fuel;
use LAH\MainBundle\Dictionary\Gearbox;
use LAH\MainBundle\Tests\BaseFunctionalTest;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class CarControllerTest extends BaseFunctionalTest
{
    /**
     * @var array
     */
    private $languages = [];

    /**
     * @var string
     */
    private $defaultLocale = 'pl';

    public function setUp()
    {
        parent::setUp();

        $this->languages     = $this->container->getParameter('supported_locales');
        $this->defaultLocale = $this->container->getParameter('locale');
    }

    /**
     * @test
     * @group read-only
     */
    public function car_add_form_renders_properly()
    {
        $em = $this->container->get('doctrine.orm.entity_manager');

        $carTypes  = $em->getRepository('LAHMainBundle:Type')->findAll();
        $carModels = $em->getRepository('LAHMainBundle:Model')->findAll();
        $carColors = $em->getRepository('LAHMainBundle:Color')->findAll();

        $crawler = $this->client->request('GET', $this->container->get('router')->generate('add_car'));
        $this->assertEquals(200, $this->getStatusCode());

        $forms = $crawler->filter('form[name="lah_car"]');
        $this->assertEquals(1, $forms->count());

        $form = $forms->first();
        $this->assertCount(26, $form->filter('input, textarea, button, select'));

        $this->assertCount(4, $form->filter('textarea'));
        $this->assertCount(7, $form->filter('select'));
        $this->assertCount(1, $form->filter('button'));
        $this->assertCount(14, $form->filter('input'));
        $this->assertCount(9, $form->filter('input[type=text]'));
        $this->assertCount(2, $form->filter('input[type=number]'));
        $this->assertCount(3, $form->filter('input[type=checkbox]'));

        $this->assertCount(count($carTypes) + 1, $crawler->filter('select#lah_car_type option'));
        $this->assertCount(count($carModels) + 1, $crawler->filter('select#lah_car_model option'));
        $this->assertCount(count($carColors) + 1, $crawler->filter('select#lah_car_color option'));
        $this->assertCount(count(Fuel::$choicesArray) + 1, $crawler->filter('select#lah_car_fuel option'));
        $this->assertCount(count(Engine::$choicesArray) + 1, $crawler->filter('select#lah_car_engine option'));
        $this->assertCount(count(Gearbox::$choicesArray) + 1, $crawler->filter('select#lah_car_gearbox option'));
        $this->assertCount(count(Country::$choicesArray) + 1, $crawler->filter('select#lah_car_registrationCountry option'));
    }

    /**
     * @test
     * @group read-only
     */
    public function car_edit_form_renders_properly()
    {
        $em = $this->container->get('doctrine.orm.entity_manager');

        $qb    = $em->getRepository('LAHMainBundle:Car')->createQueryBuilder('c');
        $query = $qb->orderBy('c.id', 'ASC')->setMaxResults(1);
        /*
         * @var Car
         */
        $carEntity = $query->getQuery()->getOneOrNullResult();

        $carTypes  = $em->getRepository('LAHMainBundle:Type')->findAll();
        $carModels = $em->getRepository('LAHMainBundle:Model')->findAll();
        $carColors = $em->getRepository('LAHMainBundle:Color')->findAll();

        $crawler = $this->client->request('GET', $this->container->get('router')->generate('edit_car', ['id' => $carEntity->getId()]));
        $this->assertEquals(200, $this->getStatusCode());

        $forms = $crawler->filter('form[name="lah_car"]');
        $this->assertEquals(1, $forms->count());

        $form = $forms->first();
        $this->assertCount(46, $form->filter('input, textarea, button, select'));

        $this->assertCount(4, $form->filter('textarea'));
        $this->assertCount(11, $form->filter('select'));
        $this->assertCount(9, $form->filter('button'));
        $this->assertCount(22, $form->filter('input'));
        $this->assertCount(9, $form->filter('input[type=text]'));
        $this->assertCount(6, $form->filter('input[type=number]'));
        $this->assertCount(3, $form->filter('input[type=checkbox]'));
        $this->assertCount($carEntity->getImages()->count(), $form->filter('input[type=file]'));

        $this->assertCount(count($carTypes) + 1, $crawler->filter('select#lah_car_type option'));
        $this->assertCount(count($carModels) + 1, $crawler->filter('select#lah_car_model option'));
        $this->assertCount(count($carColors) + 1, $crawler->filter('select#lah_car_color option'));
        $this->assertCount(count(Fuel::$choicesArray) + 1, $crawler->filter('select#lah_car_fuel option'));
        $this->assertCount(count(Engine::$choicesArray) + 1, $crawler->filter('select#lah_car_engine option'));
        $this->assertCount(count(Gearbox::$choicesArray) + 1, $crawler->filter('select#lah_car_gearbox option'));
        $this->assertCount(count(Country::$choicesArray) + 1, $crawler->filter('select#lah_car_registrationCountry option'));
    }

    /**
     * @test
     * @group read-write
     */
    public function car_add_form_is_posted_and_entity_is_added()
    {
        $crawler = $this->client->request('GET', $this->container->get('router')->generate('add_car'));
        $forms   = $crawler->filter('form[name="lah_car"]');

        $title = md5('some title'.microtime());

        $uploadedFile = new UploadedFile(
            realpath(__DIR__.'/../../../MainBundle/Resources/tests/test_image.jpg'),
            'someTestFile.jpg',
            'image/jpeg',
            123
        );

        $form = $forms->first()->form();

        $form->setValues([
            'lah_car[type]'                                                => 1,
            'lah_car[model]'                                               => 1,
            'lah_car[color]'                                               => 1,
            'lah_car[year]'                                                => 1990,
            'lah_car[distance]'                                            => 40000,
            'lah_car[fuel]'                                                => Fuel::DIESEL,
            'lah_car[engine]'                                              => Engine::DIESEL,
            'lah_car[gearbox]'                                             => Gearbox::AUTOMATIC,
            'lah_car[registrationCountry]'                                 => Country::POLAND,
            'lah_car[promoteCarousel]'                                     => true,
            'lah_car[promoteFrontpage]'                                    => false,
            'lah_car[translations]['.$this->defaultLocale.'][title]'       => $title,
            'lah_car[translations]['.$this->defaultLocale.'][description]' => 'Some description',
            'lah_car[adminNotes]'                                          => 'Admin Notes',
            'lah_car[hidden]'                                              => false,
        ]);

        $values                             = $form->getPhpValues();
        $values['lah_car']['prices'] = [
            ['amount' => 20, 'currency' => 1],
        ];

        $files = [
            'lah_car' => [
                'images' => [
                    ['file' => clone($uploadedFile)],
                    ['file' => clone($uploadedFile)],
                    ['file' => clone($uploadedFile)],
                ],
            ],
        ];

        $crawler = $this->client->request($form->getMethod(), $form->getUri(), $values, $files);

        $this->assertEquals(200, $this->getStatusCode());

        $alert = $crawler->filter('div.alert.alert-success');
        $this->assertEquals('flash.car_add.success', trim($alert->text()));

        $em = $this->container->get('doctrine.orm.entity_manager');
        /*
         * @var Car
         */
        $entity = $em->getRepository('LAHMainBundle:Car')->findOneByTitle($title);

        $this->assertEquals($this->container->get('router')->generate('edit_car', ['id' => $entity->getId()]), $this->client->getRequest()->getRequestUri());
        $this->assertEquals('GET', $this->client->getRequest()->getMethod());

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
     * @group read-write
     */
    public function car_edit_form_is_posted_and_entity_is_changed()
    {
        $em = $this->container->get('doctrine.orm.entity_manager');

        $qb    = $em->getRepository('LAHMainBundle:Car')->createQueryBuilder('c');
        $query = $qb->orderBy('c.id', 'ASC')->setMaxResults(1);
        /*
         * @var Car
         */
        $carEntity = $query->getQuery()->getOneOrNullResult();

        $url = $this->container->get('router')->generate('edit_car', ['id' => $carEntity->getId()]);

        $crawler = $this->client->request('GET', $url);
        $forms   = $crawler->filter('form[name="lah_car"]');

        $title = md5('some title'.microtime());

        $form                                                                    = $forms->first()->form();
        $values                                                                  = $form->getPhpValues();
        $values['lah_car']['translations'][$this->defaultLocale]['title'] = $title;
        $values['lah_car']['prices']                                      = [
            ['amount' => 30, 'currency' => 2],
            ['amount' => 40, 'currency' => 2],
        ];
        $newModelName                                   = md5('some title'.microtime());
        $values['lah_car']['add_model']['name']  = $newModelName;
        $values['lah_car']['add_model']['brand'] = $newModelName;

        $files                             = [];
        $files['lah_car']['images'] = $this->getUploadedImages(2);

        $crawler = $this->client->request('POST', $url, $values, $files);

        $this->assertEquals(200, $this->getStatusCode());

        $alert = $crawler->filter('div.alert.alert-success');
        $this->assertEquals('flash.car_edit.success', trim($alert->text()));

        $this->assertEquals($this->container->get('router')->generate('edit_car', ['id' => $carEntity->getId()]), $this->client->getRequest()->getRequestUri());
        $this->assertEquals('GET', $this->client->getRequest()->getMethod());

        $em->refresh($carEntity);
        $titleTranslationEntity = $carEntity->getTranslationEntityForLanguage('title', $this->defaultLocale)->first();
        $em->refresh($titleTranslationEntity);

        $this->assertNotNull($carEntity, "Car entity can't be found");
        $this->assertEquals($title, $titleTranslationEntity->getContent(), "Entity haven't been updated in db");
        $this->assertCount(2, $carEntity->getImages());
        $this->assertCount(2, $carEntity->getPrices());
        $this->assertEquals($carEntity->getModel()->getName(), $newModelName, "Entity Model haven't been created in db");
        $this->assertEquals($carEntity->getModel()->getBrand()->getName(), $newModelName, "Entity Brand haven't been created in db");
    }

    /**
     * @test
     * @dataProvider getErrorGeneratingForms
     * @group read-only
     */
    public function car_add_form_is_posted_and_error_is_emitted($formData, $collections, $error)
    {
        $crawler = $this->client->request('GET', $this->container->get('router')->generate('add_car'));
        $forms   = $crawler->filter('form[name="lah_car"]');

        $form = $forms->first()->form($formData);

        $values                             = $form->getPhpValues();
        $values['lah_car']['prices'] = $collections['prices'];

        $crawler = $this->client->request('POST', $form->getUri(), $values, ['lah_car' => ['images' => $collections['images']]]);

        $this->assertEquals(400, $this->getStatusCode());

        $alert = $crawler->filter('div.alert.alert-danger');
        $this->assertEquals('flash.car_edit.errors', trim($alert->text()));
        $this->assertContains($error, $crawler->text(), sprintf("Error '%s' not found in response", $error));
    }

    /**
     * @test
     * @dataProvider getErrorGeneratingForms
     * @group read-only
     */
    public function car_edit_form_is_posted_and_error_is_emitted($formData, $collections, $error)
    {
        $crawler = $this->client->request('GET', $this->container->get('router')->generate('edit_car', ['id' => 1]));
        $forms   = $crawler->filter('form[name="lah_car"]');

        $form = $forms->first()->form($formData);

        $values                             = $form->getPhpValues();
        $values['lah_car']['prices'] = $collections['prices'];

        $crawler = $this->client->request('POST', $form->getUri(), $values, ['lah_car' => ['images' => $collections['images']]]);

        $this->assertEquals(400, $this->getStatusCode());

        $alert = $crawler->filter('div.alert.alert-danger');
        $this->assertEquals('flash.car_edit.errors', trim($alert->text()));
        $this->assertContains($error, $crawler->text(), sprintf("Error '%s' not found in response", $error));
    }

    /**
     * @test
     * @group read-write
     */
    public function new_type_is_being_created_while_editing_new_car()
    {
        $em = $this->container->get('doctrine.orm.entity_manager');

        $qb    = $em->getRepository('LAHMainBundle:Car')->createQueryBuilder('c');
        $query = $qb->orderBy('c.id', 'ASC')->setMaxResults(1);
        /*
         * @var Car
         */
        $carEntity = $query->getQuery()->getOneOrNullResult();

        $url = $this->container->get('router')->generate('edit_car', ['id' => $carEntity->getId()]);

        $crawler = $this->client->request('GET', $url);
        $forms   = $crawler->filter('form[name="lah_car"]');

        $newTypeName = md5('some title'.microtime());

        $form                                         = $forms->first()->form();
        $values                                       = $form->getPhpValues();
        $values['lah_car']['add_type']['name'] = $newTypeName;
        $values['lah_car']['prices']           = $this->getPrices(1);

        $files                             = [];
        $files['lah_car']['images'] = $this->getUploadedImages(count($carEntity->getImages()));

        $this->client->request('POST', $url, $values, $files);

        $this->assertEquals(200, $this->getStatusCode());

        $em->refresh($carEntity);

        $this->assertNotNull($carEntity, "Car entity can't be found");
        $this->assertEquals($carEntity->getType()->getName(), $newTypeName, "Entity haven't been created in db");
    }

    /**
     * @test
     * @group read-write
     */
    public function new_color_is_being_created_while_adding_new_car()
    {
        $crawler = $this->client->request('GET', $this->container->get('router')->generate('add_car'));
        $forms   = $crawler->filter('form[name="lah_car"]');

        $title = md5('some title'.microtime());

        $form = $forms->first()->form();

        $newColorName = md5('some title'.microtime());

        $form->setValues([
            'lah_car[type]'                                                    => 1,
            'lah_car[model]'                                                   => 1,
            'lah_car[color]'                                                   => 1,
            'lah_car[year]'                                                    => 1990,
            'lah_car[distance]'                                                => 40000,
            'lah_car[fuel]'                                                    => Fuel::DIESEL,
            'lah_car[engine]'                                                  => Engine::DIESEL,
            'lah_car[gearbox]'                                                 => Gearbox::AUTOMATIC,
            'lah_car[registrationCountry]'                                     => Country::POLAND,
            'lah_car[promoteCarousel]'                                         => true,
            'lah_car[promoteFrontpage]'                                        => false,
            'lah_car[translations]['.$this->defaultLocale.'][title]'           => $title,
            'lah_car[translations]['.$this->defaultLocale.'][description]'     => 'Some description',
            'lah_car[hidden]'                                                  => false,
            'lah_car[adminNotes]'                                              => 'notes',
            'lah_car[add_color][translations]['.$this->defaultLocale.'][name]' => $newColorName,
        ]);

        $files                             = [];
        $files['lah_car']['images'] = $this->getUploadedImages(1);

        $values                             = $form->getPhpValues();
        $values['lah_car']['prices'] = $this->getPrices(1);

        $this->client->request($form->getMethod(), $form->getUri(), $values, $files);

        $this->assertEquals(200, $this->getStatusCode());

        $em = $this->container->get('doctrine.orm.entity_manager');
        /*
         * @var Car
         */
        $carEntity = $em->getRepository('LAHMainBundle:Car')->findOneByTitle($title);

        $this->assertNotNull($carEntity, 'Car entity not found');

        $this->assertEquals($carEntity->getColor()->getName(), $newColorName, "Entity Model haven't been created in db");
    }

    /**
     * @test
     * @group read-write
     */
    public function new_color_is_being_created_while_editing_new_car()
    {
        $em = $this->container->get('doctrine.orm.entity_manager');

        $qb    = $em->getRepository('LAHMainBundle:Car')->createQueryBuilder('c');
        $query = $qb->orderBy('c.id', 'ASC')->setMaxResults(1);
        /*
         * @var Car
         */
        $carEntity = $query->getQuery()->getOneOrNullResult();

        $url = $this->container->get('router')->generate('edit_car', ['id' => $carEntity->getId()]);

        $crawler = $this->client->request('GET', $url);
        $forms   = $crawler->filter('form[name="lah_car"]');

        $newColorName = md5('some title'.microtime());

        $form                                                                                = $forms->first()->form();
        $values                                                                              = $form->getPhpValues();
        $values['lah_car']['add_color']['translations'][$this->defaultLocale]['name'] = $newColorName;
        $values['lah_car']['prices']                                                  = $this->getPrices(1);

        $files                             = [];
        $files['lah_car']['images'] = $this->getUploadedImages(count($carEntity->getImages()));

        $this->client->request('POST', $url, $values, $files);

        $this->assertEquals(200, $this->getStatusCode());

        $em->refresh($carEntity);

        $this->assertNotNull($carEntity, "Car entity can't be found");
        $this->assertEquals($carEntity->getColor()->getName(), $newColorName, "Entity Model haven't been created in db");
    }

    /**
     * @test
     * @group read-write
     *
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function new_model_is_being_created_while_editing_new_car()
    {
        $em = $this->container->get('doctrine.orm.entity_manager');

        $qb    = $em->getRepository('LAHMainBundle:Car')->createQueryBuilder('c');
        $query = $qb->orderBy('c.id', 'ASC')->setMaxResults(1);
        /*
         * @var Car
         */
        $carEntity = $query->getQuery()->getOneOrNullResult();

        $url = $this->container->get('router')->generate('edit_car', ['id' => $carEntity->getId()]);

        $crawler = $this->client->request('GET', $url);
        $forms   = $crawler->filter('form[name="lah_car"]');

        $newModelName = md5('some title'.microtime());

        $form                                           = $forms->first()->form();
        $values                                         = $form->getPhpValues();
        $values['lah_car']['add_model']['name']  = $newModelName;
        $values['lah_car']['add_model']['brand'] = $newModelName;
        $values['lah_car']['prices']             = $this->getPrices(1);

        $files                             = [];
        $files['lah_car']['images'] = $this->getUploadedImages(count($carEntity->getImages()));

        $this->client->request('POST', $url, $values, $files);

        $this->assertEquals(200, $this->getStatusCode());

        $em->refresh($carEntity);

        $this->assertNotNull($carEntity, "Car entity can't be found");
        $this->assertEquals($carEntity->getModel()->getName(), $newModelName, "Entity Model haven't been created in db");
        $this->assertEquals($carEntity->getModel()->getBrand()->getName(), $newModelName, "Entity Brand haven't been created in db");
    }

    /**
     * @test
     * @group read-write
     *
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function new_model_is_being_created_while_adding_new_car()
    {
        $crawler = $this->client->request('GET', $this->container->get('router')->generate('add_car'));
        $forms   = $crawler->filter('form[name="lah_car"]');

        $title = md5('some title'.microtime());

        $form = $forms->first()->form();

        $newModelName = md5('some title'.microtime());

        $form->setValues([
            'lah_car[type]'                                                => 1,
            'lah_car[model]'                                               => null,
            'lah_car[color]'                                               => 1,
            'lah_car[year]'                                                => 1990,
            'lah_car[distance]'                                            => 40000,
            'lah_car[fuel]'                                                => Fuel::DIESEL,
            'lah_car[engine]'                                              => Engine::DIESEL,
            'lah_car[gearbox]'                                             => Gearbox::AUTOMATIC,
            'lah_car[registrationCountry]'                                 => Country::POLAND,
            'lah_car[promoteCarousel]'                                     => true,
            'lah_car[promoteFrontpage]'                                    => false,
            'lah_car[translations]['.$this->defaultLocale.'][title]'       => $title,
            'lah_car[translations]['.$this->defaultLocale.'][description]' => 'Some description',
            'lah_car[hidden]'                                              => false,
            'lah_car[adminNotes]'                                          => 'notes',
            'lah_car[add_model][name]'                                     => $newModelName,
            'lah_car[add_model][brand]'                                    => $newModelName,
        ]);

        $files                             = [];
        $files['lah_car']['images'] = $this->getUploadedImages(1);

        $values                             = $form->getPhpValues();
        $values['lah_car']['prices'] = $this->getPrices(1);

        $this->client->request($form->getMethod(), $form->getUri(), $values, $files);

        $this->assertEquals(200, $this->getStatusCode());

        $em = $this->container->get('doctrine.orm.entity_manager');
        /*
         * @var Car $entity
         */
        $carEntity = $em->getRepository('LAHMainBundle:Car')->findOneByTitle($title);

        $this->assertEquals($carEntity->getModel()->getName(), $newModelName, "Entity Model haven't been created in db");
        $this->assertEquals($carEntity->getModel()->getBrand()->getName(), $newModelName, "Entity Brand haven't been created in db");
    }

    /**
     * @test
     * @group read-write
     */
    public function new_model_type_color_brand_are_not_created_but_reused_when_adding_car()
    {
        $em = $this->container->get('doctrine.orm.entity_manager');

        $colorQuery = $em->getRepository('LAHMainBundle:Color')->createQueryBuilder('c')->orderBy('c.id', 'ASC')->setMaxResults(1);
        $modelQuery = $em->getRepository('LAHMainBundle:Model')->createQueryBuilder('c')->orderBy('c.id', 'ASC')->setMaxResults(1);
        $brandQuery = $em->getRepository('LAHMainBundle:Brand')->createQueryBuilder('c')->orderBy('c.id', 'ASC')->setMaxResults(1);
        $typeQuery  = $em->getRepository('LAHMainBundle:Type')->createQueryBuilder('c')->orderBy('c.id', 'ASC')->setMaxResults(1);

        /*
         * @var Color
         */
        $color = $colorQuery->getQuery()->getOneOrNullResult();

        /*
         * @var Model
         */
        $model = $modelQuery->getQuery()->getOneOrNullResult();

        /*
         * @var Brand
         */
        $brand = $brandQuery->getQuery()->getOneOrNullResult();

        /*
         * @var Type
         */
        $type = $typeQuery->getQuery()->getOneOrNullResult();

        $crawler = $this->client->request('GET', $this->container->get('router')->generate('add_car'));
        $forms   = $crawler->filter('form[name="lah_car"]');

        $title = md5('some title'.microtime());

        $form = $forms->first()->form();

        $form->setValues([
            'lah_car[type]'                                                    => 1,
            'lah_car[model]'                                                   => 1,
            'lah_car[color]'                                                   => 1,
            'lah_car[year]'                                                    => 1990,
            'lah_car[distance]'                                                => 40000,
            'lah_car[fuel]'                                                    => Fuel::DIESEL,
            'lah_car[engine]'                                                  => Engine::DIESEL,
            'lah_car[gearbox]'                                                 => Gearbox::AUTOMATIC,
            'lah_car[registrationCountry]'                                     => Country::POLAND,
            'lah_car[promoteCarousel]'                                         => true,
            'lah_car[promoteFrontpage]'                                        => false,
            'lah_car[translations]['.$this->defaultLocale.'][title]'           => $title,
            'lah_car[translations]['.$this->defaultLocale.'][description]'     => 'Some description',
            'lah_car[hidden]'                                                  => false,
            'lah_car[adminNotes]'                                              => 'notes',
            'lah_car[add_model][name]'                                         => $model->getName(),
            'lah_car[add_model][brand]'                                        => $brand->getName(),
            'lah_car[add_color][translations]['.$this->defaultLocale.'][name]' => $color->getName(),
            'lah_car[add_type][name]'                                          => $type->getName(),
        ]);

        $files                             = [];
        $files['lah_car']['images'] = $this->getUploadedImages(1);

        $values                             = $form->getPhpValues();
        $values['lah_car']['prices'] = $this->getPrices(1);

        $this->client->request($form->getMethod(), $form->getUri(), $values, $files);

        $this->assertEquals(200, $this->getStatusCode());

        $em = $this->container->get('doctrine.orm.entity_manager');
        /*
         * @var Car
         */
        $carEntity = $em->getRepository('LAHMainBundle:Car')->findOneByTitle($title);

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
     * @group read-write
     */
    public function new_model_type_color_brand_are_not_created_but_reused_when_editing_car()
    {
        $em = $this->container->get('doctrine.orm.entity_manager');

        $colorQuery = $em->getRepository('LAHMainBundle:Color')->createQueryBuilder('c')->orderBy('c.id', 'ASC')->setMaxResults(1);
        $modelQuery = $em->getRepository('LAHMainBundle:Model')->createQueryBuilder('c')->orderBy('c.id', 'ASC')->setMaxResults(1);
        $brandQuery = $em->getRepository('LAHMainBundle:Brand')->createQueryBuilder('c')->orderBy('c.id', 'ASC')->setMaxResults(1);
        $typeQuery  = $em->getRepository('LAHMainBundle:Type')->createQueryBuilder('c')->orderBy('c.id', 'ASC')->setMaxResults(1);

        /*
         * @var Color
         */
        $color = $colorQuery->getQuery()->getOneOrNullResult();

        /*
         * @var Model
         */
        $model = $modelQuery->getQuery()->getOneOrNullResult();

        /*
         * @var Brand
         */
        $brand = $brandQuery->getQuery()->getOneOrNullResult();

        /*
         * @var Type
         */
        $type = $typeQuery->getQuery()->getOneOrNullResult();

        $qb    = $em->getRepository('LAHMainBundle:Car')->createQueryBuilder('c');
        $query = $qb->orderBy('c.id', 'ASC')->setMaxResults(1);
        /*
         * @var Car
         */
        $carEntity = $query->getQuery()->getOneOrNullResult();

        $url = $this->container->get('router')->generate('edit_car', ['id' => $carEntity->getId()]);

        $crawler = $this->client->request('GET', $url);
        $forms   = $crawler->filter('form[name="lah_car"]');

        $title = md5('some title'.microtime());

        $form = $forms->first()->form();

        $values = $form->getPhpValues();

        $values['lah_car']['translations'][$this->defaultLocale]['title']             = $title;
        $values['lah_car']['add_model']['name']                                       = $model->getName();
        $values['lah_car']['add_model']['brand']                                      = $brand->getName();
        $values['lah_car']['add_color']['translations'][$this->defaultLocale]['name'] = $color->getName();
        $values['lah_car']['add_type']['name']                                        = $type->getName();
        $values['lah_car']['prices']                                                  = $this->getPrices(1);

        $files                             = [];
        $files['lah_car']['images'] = $this->getUploadedImages(count($carEntity->getImages()));

        $this->client->request($form->getMethod(), $form->getUri(), $values, $files);

        $this->assertEquals(200, $this->getStatusCode());

        $em = $this->container->get('doctrine.orm.entity_manager');

        /*
         * @var Car $carEntity
         */
        $em->refresh($carEntity);

        $titleTranslationEntity = $carEntity->getTranslationEntityForLanguage('title', $this->defaultLocale)->first();
        $em->refresh($titleTranslationEntity);
        $em->refresh($color);
        $em->refresh($type);
        $em->refresh($brand);
        $em->refresh($model);

        $this->assertNotNull($carEntity);

        $this->assertEquals($title, $titleTranslationEntity->getContent());
        $this->assertEquals($color->getId(), $carEntity->getColor()->getId());
        $this->assertEquals($type->getId(), $carEntity->getType()->getId());
        $this->assertEquals($brand->getId(), $carEntity->getModel()->getBrand()->getId());
        $this->assertEquals($model->getId(), $carEntity->getModel()->getId());
    }

    public function getErrorGeneratingForms()
    {
        $correctData = [
            'lah_car[type]'                                                => 1,
            'lah_car[model]'                                               => 1,
            'lah_car[color]'                                               => 1,
            'lah_car[year]'                                                => 1990,
            'lah_car[distance]'                                            => 40000,
            'lah_car[fuel]'                                                => Fuel::DIESEL,
            'lah_car[engine]'                                              => Engine::DIESEL,
            'lah_car[gearbox]'                                             => Gearbox::AUTOMATIC,
            'lah_car[registrationCountry]'                                 => Country::POLAND,
            'lah_car[promoteCarousel]'                                     => true,
            'lah_car[promoteFrontpage]'                                    => false,
            'lah_car[translations]['.$this->defaultLocale.'][title]'       => 'Some title',
            'lah_car[translations]['.$this->defaultLocale.'][description]' => 'Some description',
            'lah_car[adminNotes]'                                          => 'Admin Notes',
            'lah_car[hidden]'                                              => false,
        ];

        $collections = ['images' => $this->getUploadedImages(2), 'prices' => $this->getPrices(2)];

        return [
            'empty type' => [
                'formData' => array_merge(
                    $correctData,
                    [
                        'lah_car[type]' => null,
                    ]
                ),
                'collections' => $collections,
                'error'       => 'validator.you_have_to_choose_car_type',
            ],
            'empty model' => [
                'formData' => array_merge(
                    $correctData,
                    [
                        'lah_car[model]' => null,
                    ]
                ),
                'collections' => $collections,
                'error'       => 'validator.you_have_to_choose_car_model',
            ],
            'empty color' => [
                'formData' => array_merge(
                    $correctData,
                    [
                        'lah_car[color]' => null,
                    ]
                ),
                'collections' => $collections,
                'error'       => 'validator.you_have_to_choose_car_color',
            ],
            'empty brand' => [
                'formData' => array_merge(
                    $correctData,
                    [
                        'lah_car[add_model][name]'  => 'someName',
                        'lah_car[add_model][brand]' => null,
                    ]
                ),
                'collections' => $collections,
                'error'       => 'validator.you_have_to_add_car_brand',
            ],
            'empty model, filled brand' => [
                'formData' => array_merge(
                    $correctData,
                    [
                        'lah_car[add_model][name]'  => null,
                        'lah_car[add_model][brand]' => 'brand',
                    ]
                ),
                'collections' => $collections,
                'error'       => 'validator.you_have_to_add_car_model_name',
            ],
            'empty prices' => [
                'formData'    => $correctData,
                'collections' => array_merge($collections, ['prices' => []]),
                'error'       => 'car.validation.prices.min',
            ],
            'empty images' => [
                'formData'    => $correctData,
                'collections' => array_merge($collections, ['images' => []]),
                'error'       => 'car.validation.images.min',
            ],
            'too much prices' => [
                'formData'    => $correctData,
                'collections' => array_merge($collections, ['prices' => $this->getPrices(6)]),
                'error'       => 'car.validation.prices.max',
            ],
            'too much images' => [
                'formData'    => $correctData,
                'collections' => array_merge($collections, ['images' => $this->getUploadedImages(21)]),
                'error'       => 'car.validation.images.max',
            ],
        ];
    }

    private function getUploadedImages($int)
    {
        $uploadedFile = $this->getUploadedFile();

        $array = [];

        for ($a = 0; $a < $int; $a++) {
            $array[] = ['file' => clone($uploadedFile)];
        }

        return $array;
    }

    private function getPrices($int)
    {
        $array = [];

        for ($a = 0; $a < $int; $a++) {
            $array[] = ['amount' => 30, 'currency' => 2];
        }

        return $array;
    }

    private function getUploadedFile()
    {
        return new UploadedFile(
            realpath(__DIR__.'/../../../MainBundle/Resources/tests/test_image.jpg'),
            'someTestFile.jpg',
            'image/jpeg',
            123
        );
    }
}
