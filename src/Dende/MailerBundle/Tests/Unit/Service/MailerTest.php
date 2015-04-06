<?php
namespace Dende\MailerBundle\Tests\Unit\Service;

use Dende\MailerBundle\Service\Mailer;

class MailerTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Mailer
     */
    protected $mailer;

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp()
    {
        $this->mailer = new Mailer($this->getMailerMock());
        $this->mailer->setTemplating($this->getTemplatingMock());
        $this->mailer->setTranslator($this->getTranslatorMock());
    }

    /**
     * Tears down the fixture, for example, closes a network connection.
     * This method is called after a test is executed.
     */
    protected function tearDown()
    {
    }

    /**
     * @covers Dende\MailerBundle\Service\Mailer::sendMail
     */
    public function testSendMail()
    {
        $this->mailer->sendMail();
    }

    /**
     * @covers Dende\MailerBundle\Service\Mailer::__call
     * @expectedException \BadMethodCallException`
     */
    public function testCall()
    {
        $this->mailer->sendMail();

        try {
            $this->mailer->setDupaCycki();
        } catch (\BadMethodCallException $e) {
            $this->assertTrue(true);
        }
    }

    private function getMailerMock()
    {
        $mock = $this->getMockBuilder('\Swift_Mailer')
            ->disableOriginalConstructor()
            ->getMock();

        $mock->expects($this->once())
            ->method('createMessage')
            ->will($this->returnValue($this->getMessageMock()));

        return $mock;
    }

    private function getMessageMock()
    {
        $mock = $this->getMockBuilder('\Swift_Message')
            ->disableOriginalConstructor()
            ->getMock();

        $mock->expects($this->once())
            ->method('getSubject')
            ->will($this->returnValue('message_subject'));

        $mock->expects($this->once())
            ->method('setContentType')
            ->will($this->returnValue(null));

        $mock->expects($this->once())
            ->method('setSubject')
            ->will($this->returnValue(null));

        return $mock;
    }

    private function getTemplatingMock()
    {
        $mock = $this->getMockBuilder('Symfony\Bridge\Twig\TwigEngine')
            ->disableOriginalConstructor()
            ->getMock();

        $mock->expects($this->once())
            ->method('render')
            ->will($this->returnValue('template_body'));

        return $mock;
    }

    private function getTranslatorMock()
    {
        $mock = $this->getMockBuilder("\Symfony\Bundle\FrameworkBundle\Translation\Translator")
            ->disableOriginalConstructor()
            ->getMock();

        $mock->expects($this->once())
            ->method('trans')
            ->will($this->returnValue('translated_text'));

        return $mock;
    }
}
