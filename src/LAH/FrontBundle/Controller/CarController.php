<?php
namespace LAH\FrontBundle\Controller;

use A2lix\TranslationFormBundle\Annotation\GedmoTranslation;
use LAH\FrontBundle\Entity\Car;
use LAH\FrontBundle\Model\SearchQuery;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class CarController.
 *
 * @Route("/admin/cars")
 */
class CarController extends Controller
{
    /**
     * @Route("/add",name="add_car")
     *
     * @Method({"GET","POST"})
     */
    public function addAction(Request $request)
    {
        $form = $this->createForm('lah_form_car');

        if ($request->isMethod('POST')) {
            $form->handleRequest($request);
            $serializer = $this->get('jms_serializer');

            if ($form->isValid()) {
                $car = $form->getData();
                $this->get('lah.front.form_handler.car')->setCar($car)->handle($form);

                $em = $this->getDoctrine()->getManager();
                $em->persist($car);
                $em->flush();

                $this->addFlash('success', 'flash.car_add.success');

                $this->get('logger')->addWarning('Car added', [
                    'formData' => $serializer->serialize($form->getData(), 'json'),
                ]);

                return $this->redirect(
                    $this->generateUrl('edit_car', ['id' => $car->getId()])
                );
            } else {
                $this->addFlash('error', 'flash.car_edit.errors');

                $this->get('logger')->addWarning('Car adding error', [
                    'formData'  => $serializer->serialize($form->getData(), 'json'),
                    'formError' => $serializer->serialize($form->getErrors(), 'json'),
                    'asString'  => $form->getErrorsAsString(),
                ]);
                $statusCode = 400;
            }
        }

        return new Response($this->renderView('@Front/Car/add.html.twig', [
                'form' => $form->createView()
            ]), isset($statusCode) ? $statusCode : 200);
    }

    /**
     * @Route("/edit/{id}",name="edit_car")
     * @ParamConverter("car", class="FrontBundle:Car")
     *
     * @Method({"GET","POST"})
     * @GedmoTranslation()
     */
    public function editAction(Request $request, Car $car)
    {
        $form       = $this->createForm('lah_form_car', $car);
        $serializer = $this->get('jms_serializer');

        $this->get('lah.front.form_handler.process_images')->setOriginalImages($car->getImages());
        $this->get('lah.front.form_handler.process_prices')->setOriginalPrices(clone($car->getPrices()));

        if ($request->isMethod('POST')) {
            $form->handleRequest($request);

            if ($form->isValid()) {
                $this->get('lah.front.form_handler.car')->setCar($car)->handle($form);

                $em = $this->getDoctrine()->getManager();
                $em->persist($car);
                $em->flush();

                $this->addFlash('success', 'flash.car_edit.success');

                $this->get('logger')->addWarning('Car edited', [
                        'formData' => $serializer->serialize($form->getData(), 'json'),
                ]);

                return $this->redirect($this->generateUrl('edit_car', ['id' => $car->getId()]));
            } else {
                $this->addFlash('error', 'flash.car_edit.errors');

                $this->get('logger')->addWarning('Car editing error', [
                        'formData'  => $serializer->serialize($form->getData(), 'json'),
                        'formError' => $serializer->serialize($form->getErrors(true), 'json'),
                ]);

                $statusCode = 400;
            }
        }

        return new Response($this->renderView('@Front/Car/edit.html.twig', [
                'form' => $form->createView(), 'car' => $car
            ]), isset($statusCode) ? $statusCode : 200);
    }

    /**
     * @Route("/list/{page}", name="car", defaults={ "page" = 1})
     * @Template()
     *
     * @Method({"GET", "POST"})
     */
    public function listAction(Request $request, $page = 1)
    {
        $searchQuery = new SearchQuery();

        $formType = $this->get('lah.front.form.type.search');
        $form        = $this->createForm($formType, $searchQuery, [
            "action" => $this->generateUrl("car"),
            "method" => "GET"
        ]);

        $qb          = $this->getDoctrine()->getRepository('FrontBundle:Car')->createQueryBuilder('c');

        $cacheId = ['DefaultController:listAction'];

        $this->get('lah.front.search_query_entity_merge')->merge($searchQuery);

        $form->handleRequest($request);

        if ($form->isValid()) {
            /*
             * @var SearchQuery
             */
            $searchQuery = $form->getData();

            $this->get('lah.front.search_query_modifier')->modify($searchQuery, $qb, $cacheId);
        }

        /*
         * @var PersistentCollection $cars
         */

        $query = $qb->getQuery();

        $query->useResultCache(true, 3600, md5(implode('/', $cacheId)));

        $paginator  = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
            $query,
            $request->query->get('page', $page),
            10
        );

        return [
            'entities'       => $pagination,
            'searchForm' => $form,
        ];
    }


    /**
     * Finds and displays a Car entity.
     *
     * @Route("/{id}", name="car_show")
     * @ParamConverter("car", class="FrontBundle:Car")
     *
     * @Method("GET")
     * @Template()
     */
    public function showAction(Car $car)
    {
        return [
            'entity'      => $car,
        ];
    }

    /**
     * @Route("/{id}/delete",name="delete_car")
     * @ParamConverter("car", class="FrontBundle:Car")
     *
     * @Method({"GET"})
     */
    public function deleteAction(Request $request, Car $car)
    {
        $this->get('lah.front.manager.car')->delete($car);

        return $this->redirect(
            $request->headers->get('referer')
        );
    }
}
