<?php
namespace Dende\FrontBundle\Tests\Functional;

use Dende\FrontBundle\Entity\Brand;
use Dende\FrontBundle\Entity\Model;
use Dende\FrontBundle\Entity\Type;
use Dende\FrontBundle\Tests\BaseFunctionalTest;
use Symfony\Bundle\SwiftmailerBundle\DataCollector\MessageDataCollector;
use Symfony\Component\DomCrawler\Form;

class ContactFormTest extends BaseFunctionalTest
{
    /**
     * @var MessageDataCollector
     */
    private $mailerCollector;

    public function setUp()
    {
        $this->resetKernel();
        $this->prepareClient();
    }

    /**
     * @test
     * @group read-only
     */
    public function contact_form_renders_properly()
    {
        $crawler = $this->client->request('GET', $this->container->get('router')->generate('contact'));
        $this->assertEquals(200, $this->getStatusCode());

        $forms = $crawler->filter('form[name="dende_form_contact"]');
        $this->assertEquals(1, $forms->count());

        $form = $forms->first();
        $this->assertCount(4, $form->filter('input'));
        $this->assertCount(1, $form->filter('textarea'));

        $crawler = $this->client->request('GET', $this->container->get('router')->generate('contact', ["id" => 1]));
        $this->assertEquals(200, $this->getStatusCode());

        $forms = $crawler->filter('form[name="dende_form_contact"]');
        $this->assertEquals(1, $forms->count());

        $form = $forms->first();
        $this->assertCount(5, $form->filter('input'));
        $this->assertCount(1, $form->filter('textarea'));
    }

    /**
     * @test
     * @group read-only
     */
    public function i_submit_message_with_contact_form()
    {
        $crawler = $this->client->request('GET', $this->container->get('router')->generate('contact'));
        $this->assertEquals(200, $this->getStatusCode());

        $forms = $crawler->filter('form[name="dende_form_contact"]');
        $this->assertEquals(1, $forms->count());

        /*
         * @var Form
         */
        $form = $forms->first()->form();

        $form->setValues([
            'dende_form_contact[name]' => "john doe",
            'dende_form_contact[email]' => "john@doe.com",
            'dende_form_contact[phone]' => "600 000 000",
            'dende_form_contact[message]' => "lorem ipsum dolot simet",
        ]);

        $this->client->enableProfiler();

        $crawler = $this->client->submit($form);

        $this->mailerCollector = $this->client->getProfile()->getCollector("swiftmailer");

        $this->assertContains('contact.message.success', $crawler->text());
        $this->assertEquals(1, $this->mailerCollector->getMessageCount());

        /**
         * @var \Swift_Message
         */
        $message = $this->mailerCollector->getMessages()[0];

        $sendTo = array_keys($message->getHeaders()->get("X-Swift-To")->getNameAddresses());

        $this->assertInstanceOf('Swift_Message', $message);
        $this->assertEquals('john@doe.com', key($message->getFrom()));
        $this->assertEquals($this->container->getParameter("mailer_sendto"), $sendTo);
        $this->assertContains("lorem ipsum dolot simet", $message->getBody());
    }
    /**
     * @test
     * @group read-only
     */
    public function i_get_errors_when_i_submit_message_with_contact_form()
    {
        $crawler = $this->client->request('GET', $this->container->get('router')->generate('contact'));
        $this->assertEquals(200, $this->getStatusCode());

        $forms = $crawler->filter('form[name="dende_form_contact"]');
        $this->assertEquals(1, $forms->count());

        /*
         * @var Form
         */
        $form = $forms->first()->form();

        $form->setValues([
            'dende_form_contact[name]' => "",
            'dende_form_contact[email]' => "",
            'dende_form_contact[phone]' => "",
            'dende_form_contact[message]' => "",
        ]);

        $this->client->enableProfiler();

        $crawler = $this->client->submit($form);

        $this->mailerCollector = $this->client->getProfile()->getCollector("swiftmailer");

        $this->assertContains('contact.empty_message', $crawler->text());
        $this->assertContains('contact.empty_email', $crawler->text());
        $this->assertEquals(0, $this->mailerCollector->getMessageCount());
    }
}