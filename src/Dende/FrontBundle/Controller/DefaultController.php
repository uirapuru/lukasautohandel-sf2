<?php

namespace Dende\FrontBundle\Controller;

use Dende\FrontBundle\Entity\Brand;
use Dende\FrontBundle\Entity\Car;
use Doctrine\ORM\PersistentCollection;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Dende\FrontBundle\Model\SearchQuery;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;

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
     * @Template()
     */
    public function searchAction($form = null)
    {
        if (!$form) {
            $form = $this->createForm("dende_form_search", new SearchQuery);
        }

        return ["form" => $form->createView()];
    }

//    /**
//     * @Route("/set-search", name="set-search")
//     */
//    public function setSearchQueryAction(Request $request)
//    {
//        $query = $this->getSearchQuery();
//
//        $form = $this->createForm("dende_form_search", $query);
//
//        if ($request->isMethod("POST")) {
//            $form->handleRequest($request);
//
//            if ($form->isValid()) {
//                $this->get('session')->set(
//                    '_search_query',
//                    $this->get("jms_serializer")->serialize($form->getData(), "json")
//                );
//            }
//        }
//
//        return $this->redirectToRoute("list");
//    }

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
    public function listAction(Request $request)
    {
        $searchQuery = new SearchQuery();
        $form = $this->createForm("dende_form_search", $searchQuery);
        $qb = $this->getDoctrine()->getRepository("FrontBundle:Car")->createQueryBuilder("c");

        if ($request->isMethod("POST")) {
            $this->get("dende.front.search_query_entity_merge")->merge($searchQuery);
            $form->handleRequest($request);

            if ($form->isValid()) {
                /**
                 * @var SearchQuery $searchQuery
                 */
                $searchQuery = $form->getData();

                if ($searchQuery->getType()) {
                    $qb->andWhere("c.type = :type");
                    $qb->setParameter("type", $searchQuery->getType());
                }

                if ($searchQuery->getBrand()) {
                    $qb->innerJoin("c.model", "m");

                    $qb->andWhere("m.brand = :brand");
                    $qb->setParameter("brand", $searchQuery->getBrand());
                }

                if ($searchQuery->getModel()) {
                    $qb->andWhere("c.model = :carModel");
                    $qb->setParameter("carModel", $searchQuery->getModel());
                }
            }
        }

        /**
         * @var PersistentCollection $cars
         */

        return [
            "cars" => $qb->getQuery()->execute(),
            "searchForm" => $form
        ];
    }

    /**
     * @Route("/show/{id}/{slug}", name="show")
     * @ParamConverter("car", class="FrontBundle:Car")
     * @Template()
     */
    public function showAction(Car $car)
    {
        return ["car" => $car];
    }

    /**
     * @Route("/contact",name="contact")
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

    /**
     * @Route(
     *      "/api/models/brand/{id}",
     *      name="models_for_brand",
     *      options={"expose"=true}
     * )
     * @ParamConverter("brand", class="FrontBundle:Brand")
     */
    public function getModelsForBrand(Brand $brand)
    {
        $models = $brand->getModels();

        return new Response($this->get("jms_serializer")->serialize($models, "json"));
    }

    /**
     * @Route(
     *      "/api/models",
     *      name="api_models",
     *      options={"expose"=true}
     * )
     */
    public function getModels()
    {
        $models = $this->getDoctrine()->getRepository("FrontBundle:Model")->findAll();

        return new Response($this->get("jms_serializer")->serialize($models, "json"));
    }

//    /**
//     * @return SearchQuery
//     */
//    private function getSearchQuery()
//    {
////        if ($this->get('session')->has('_search_query')) {
////            $query = $this->get("jms_serializer")->deserialize(
////                $this->get('session')->get('_search_query'),
////                "Dende\FrontBundle\Model\SearchQuery",
////                "json"
////            );
////        } else {
//            $query = new SearchQuery();
////        }
//
//        $this->get("dende.front.search_query_entity_merge")->merge($query);
//
//        return $query;
//    }
}
