<?php
namespace LAH\FrontBundle\Controller;

use LAH\FrontBundle\Entity\Brand;
use LAH\FrontBundle\Entity\Car;
use LAH\FrontBundle\Model\SearchQuery;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\RedirectResponse;
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
    public function searchAction($form = null)
    {
        if (!$form) {
            $form = $this->createForm('search', new SearchQuery(), [
                "method" => "GET"
            ]);
        }

        return ['form' => $form->createView()];
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
        $promoted = $this->getDoctrine()->getRepository('FrontBundle:Car')->findBy(['promoteFrontpage' => true]);

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
        $promoted = $this->getDoctrine()->getRepository('FrontBundle:Car')->findBy(['promoteCarousel' => true]);

        return ['cars' => $promoted];
    }

    /**
     * @Route("/list/{page}", name="list", defaults={ "page" = 1})
     * @Template()
     *
     * @Method({"GET"})
     */
    public function listAction(Request $request, $page = 1)
    {
        $searchQuery = new SearchQuery();
        $form = $this->createForm('search', $searchQuery, [
            "method" => "GET"
        ]);

        $qb = $this->getDoctrine()->getRepository('FrontBundle:Car')->createQueryBuilder('c');

        $cacheId = ['DefaultController:listAction'];

        $this->get('lah.front.search_query_entity_merge')->merge($searchQuery);

        $form->handleRequest($request);

        if ($form->isValid()) {
            /*
             * @var SearchQuery
             */
            $searchQuery = $form->getData();

            $this->get('lah.front.search_query_modifier')->modify($searchQuery, $qb, $cacheId);
        } else {
            $pagination =  $this->get('knp_paginator')->paginate([], 1, 10);
            return ['cars' => $pagination, 'searchForm' => $form];
        }

        /*
         * @var PersistentCollection $cars
         */

        $query = $qb->getQuery();

        $query->useResultCache(true, 3600, md5(implode('/', $cacheId)));

        $pagination =  $this->get('knp_paginator')->paginate($query, $page, 10);

        return [
            'cars'       => $pagination,
            'searchForm' => $form,
        ];
    }

    /**
     * @Route("/show/{id}/{slug}", name="show")
     * @ParamConverter("car", class="FrontBundle:Car")
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
     * @ParamConverter("car", class="FrontBundle:Car")
     * @Template()
     */
    public function contactAction(Request $request, $car = null)
    {
        $this->get('lah.front.form.type.contact')->setCar($car);
        $form = $this->createForm('lah_form_contact');

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
     * @ParamConverter("brand", class="FrontBundle:Brand")
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
        $models = $this->getDoctrine()->getRepository('FrontBundle:Model')->findAll();

        return new Response($this->get('jms_serializer')->serialize($models, 'json'));
    }
}
