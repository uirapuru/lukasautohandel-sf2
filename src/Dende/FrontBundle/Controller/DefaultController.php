<?php

namespace Dende\FrontBundle\Controller;

use Dende\FrontBundle\Entity\Car;
use Doctrine\ORM\PersistentCollection;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\Request;
use Dende\FrontBundle\Form\Type\ContactType;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="main")
     * @Template()
     */
    public function indexAction()
    {
        return [];
    }

    /**
     * @Route("/", name="search")
     * @Template()
     */
    public function searchAction()
    {
        $form = $this->createForm("dende_form_search");

        return ["form" => $form->createView()];
    }

    /**
     * @Template()
     */
    public function promotedAction()
    {
        /**
         * @var PersistentCollection $cars
         */
        $promoted = $this->getDoctrine()->getRepository("FrontBundle:Car")->findBy(["promoteFrontpage" => true]);
        return ["cars" => $promoted];
    }

    /**
     * @Template()
     */
    public function carouselAction()
    {
        /**
         * @var PersistentCollection $cars
         */
        $promoted = $this->getDoctrine()->getRepository("FrontBundle:Car")->findBy(["promoteCarousel" => true]);
        return ["cars" => $promoted];
    }

    /**
     * @Route("/list", name="list")
     * @Template()
     */
    public function listAction()
    {
        /**
         * @var PersistentCollection $cars
         */
        $cars = $this->getDoctrine()->getRepository("FrontBundle:Car")->findAll();
        return ["cars" => $cars];
    }

    /**
     * @Route("/show/{id}/{slug}", name="show", defaults={"slug" = null})
     * @ParamConverter("car", class="FrontBundle:Car")
     * @Template()
     */
    public function showAction(Car $car)
    {
        return ["car" => $car];
    }

    /**
     * @Route("/contact/{id}",name="contact", defaults={"id" = null})
     * @Method({"GET","POST"})
     * @ParamConverter("car", class="FrontBundle:Car")
     * @Template()
     */
    public function contactAction(Request $request, $car = null)
    {
        $this->get("dende.front.form.type.contact")->setCar($car);
        $form = $this->createForm('dende_form_contact');

        if ($request->getMethod() == 'POST') {
            $form->handleRequest($request);

            if ($form->isValid()) {
                $this->get('session')->getFlashBag()->add(
                    'notice',
                    $this->get("translator")->trans("contact.message.success")
                );

                $mailer = $this->get("mailer.contact");
                $mailer->setParameters([
                    "message" => $form->get("message")->getData(),
                    "email" => $form->get("email")->getData(),
                ]);
                $mailer->setFrom(
                    $form->get("email")->getData()
                );
                $mailer->sendMail();
            }
        }

        return [
            "form" => $form->createView(),
        ];
    }

    /**
     * @Route(
     *      "/language/{locale}",
     *      name="switch_language",
     *      requirements={"locale" = "(pl|en|de)"},
     *      defaults={"locale" = "pl"}
     * )
     * @Template()
     */
    public function switchLanguageAction(Request $request, $locale)
    {
        $request->setLocale($locale);
        $this->get('session')->set('_locale', $locale);

        return $this->redirect(
            $request->headers->get('referer')
        );
    }
}
