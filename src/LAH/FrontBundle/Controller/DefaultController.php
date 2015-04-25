<?php
namespace LAH\FrontBundle\Controller;

use LAH\MainBundle\Entity\Brand;
use LAH\MainBundle\Entity\Car;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="main")
     * @Template()
     *
     * @Method({"GET"})
     */
    public function indexAction()
    {
        return [];
    }

    /**
     * @Template()
     *
     * @Method({"GET"})
     */
    public function promotedAction()
    {
        /*
         * @var PersistentCollection $cars
         */
        $promoted = $this->getDoctrine()->getRepository('LAHMainBundle:Car')->findBy(['promoteFrontpage' => true]);

        return ['cars' => $promoted];
    }

    /**
     * @Template()
     *
     * @Method({"GET"})
     */
    public function carouselAction()
    {
        /*
         * @var PersistentCollection $cars
         */
        $promoted = $this->getDoctrine()->getRepository('LAHMainBundle:Car')->findBy(['promoteCarousel' => true]);

        return ['cars' => $promoted];
    }

    /**
     * @Route("/list", name="list")
     * @Template()
     *
     * @Method({"GET"})
     */
    public function listAction(Request $request)
    {
        return $this->forward("LAHSearchBundle:Default:list", [
            'request' => $request,
            'template' => 'LAHFrontBundle:Default:list.html.twig',
            'action' => $this->generateUrl('list')
        ]);
    }

    /**
     * @Route("/show/{id}/{slug}", name="show")
     * @ParamConverter("car", class="LAHMainBundle:Car")
     * @Template()
     *
     * @Method({"GET"})
     */
    public function showAction(Car $car)
    {
        return ['car' => $car];
    }

    /**
     * @Route("/contact/{id}", name="contact", defaults={ "id" = null })
     *
     * @Method({"GET","POST"})
     * @ParamConverter("car", class="LAHMainBundle:Car")
     * @Template()
     */
    public function contactAction(Request $request, $car = null)
    {
        $this->get('lah.front.form.type.contact')->setCar($car);
        $form = $this->createForm('lah_contact', null, [
            'action'=> $this->generateUrl("contact", ["id" => $car ? $car->getId() : null])
        ]);

        if ($request->getMethod() == 'POST') {
            $form->handleRequest($request);

            if ($form->isValid()) {
                $this->addFlash('success', 'contact.message.success');

                $mailer = $this->get('mailer.contact');
                $mailer->setParameters([
                    'message' => $form->get('message')->getData(),
                    'email'   => $form->get('email')->getData(),
                    'name'      => $form->get('name')->getData(),
                    'gsm'   => $form->get('phone')->getData(),
                    'car'     => $car
                ]);
                $mailer->setFrom($form->get('email')->getData());
                $mailer->sendMail();

                $this->redirectToRoute('contact', ["id" => $car ? $car->getId() : null]);
            }
        }

        return ['form' => $form->createView()];
    }

    /**
     * @Route(
     *      "/language/{locale}",
     *      name="switch_language",
     *      requirements={"locale" = "(pl|ru|de)"},
     *      defaults={"locale" = "pl"}
     * )
     *
     * @Method({"GET"})
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

    /**
     * @Route(
     *      "/api/models/brand/{id}",
     *      name="models_for_brand",
     *      options={"expose"=true}
     * )
     *
     * @Method({"GET"})
     * @ParamConverter("brand", class="LAHMainBundle:Brand")
     */
    public function getModelsForBrandAction(Brand $brand)
    {
        $models = $brand->getModels();

        return new Response($this->get('jms_serializer')->serialize($models, 'json'));
    }

    /**
     * @Route(
     *      "/api/models",
     *      name="api_models",
     *      options={"expose"=true}
     * )
     *
     * @Method({"GET"})
     */
    public function getModelsAction()
    {
        $models = $this->getDoctrine()->getRepository('LAHMainBundle:Model')->findAll();

        return new Response($this->get('jms_serializer')->serialize($models, 'json'));
    }
}
