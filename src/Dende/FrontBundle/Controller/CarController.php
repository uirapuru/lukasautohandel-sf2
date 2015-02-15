<?php

namespace Dende\FrontBundle\Controller;

use A2lix\TranslationFormBundle\Annotation\GedmoTranslation;
use Dende\FrontBundle\Entity\Car;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class CarController
 * @package Dende\FrontBundle\Controller
 *
 * @Route("/cars")
 */
class CarController extends Controller
{

    /**
     * @Route("/add",name="add_car")
     * @Method({"GET","POST"})
     */
    public function addAction(Request $request)
    {
        $form = $this->createForm('dende_form_car');

        $statusCode = 200;

        if ($request->isMethod("POST")) {
            $form->handleRequest($request);
            $serializer = $this->get("jms_serializer");

            if ($form->isValid()) {
                $car = $form->getData();

                $em = $this->getDoctrine()->getManager();

                $this->get("dende.front.form_handler.process_colors")->setForm($form)->addColor();
                $this->get("dende.front.form_handler.process_types")->setForm($form)->addType();
                $this->get("dende.front.form_handler.process_models")->setForm($form)->addModel();

                $processImages = $this->get("dende.front.form_handler.process_images");
                $processImages->setCar($car);
                $processImages->processUploaded();

                $em->persist($car);
                $em->flush();

                $request->getSession()->getFlashBag()->add(
                    'success',
                    'flash.car_add.success'
                );

                $this->get("logger")->addWarning(
                    "Car added",
                    [
                        "formData" => $serializer->serialize($form->getData(), "json"),
                    ]
                );

                return $this->redirect(
                    $this->generateUrl("edit_car", ["id" => $car->getId()])
                );
            } else {
                $request->getSession()->getFlashBag()->add(
                    'error',
                    'flash.car_edit.errors'
                );

                $this->get("logger")->addWarning(
                    "Car adding error",
                    [
                        "formData" => $serializer->serialize($form->getData(), "json"),
                        "formError" => $serializer->serialize($form->getErrors(), "json"),
                        "asString" => $form->getErrorsAsString(),
                    ]
                );
                $statusCode = 400;
            }
        }

        return new Response(
            $this->renderView('@Front/Car/add.html.twig', ["form" => $form->createView()]),
            $statusCode
        );
    }

    /**
     * @Route("/edit/{id}",name="edit_car")
     * @ParamConverter("car", class="FrontBundle:Car")
     * @Method({"GET","POST"})
     * @GedmoTranslation()
     */
    public function editAction(Request $request, Car $car)
    {
        $form = $this->createForm('dende_form_car', $car);
        $statusCode = 200;
        $serializer = $this->get("jms_serializer");

        $processImages = $this->get("dende.front.form_handler.process_images");
        $processImages->setOriginalImages($car->getImages());

        $processPrices = $this->get("dende.front.form_handler.process_prices");
        $processPrices->setOriginalPrices(clone($car->getPrices()));

        if ($request->isMethod("POST")) {
            $form->handleRequest($request);

            if ($form->isValid()) {
                $processImages->setCar($car);

                $em = $this->getDoctrine()->getManager();

                $this->get("dende.front.form_handler.process_colors")->setForm($form)->addColor();
                $this->get("dende.front.form_handler.process_types")->setForm($form)->addType();
                $this->get("dende.front.form_handler.process_models")->setForm($form)->addModel();

                $processImages->removeUnused();
                $processImages->processUploaded();

                $processPrices->setCar($car)->removeUnused();

                $em->persist($car);
                $em->flush();

                $request->getSession()->getFlashBag()->add(
                    'success',
                    'flash.car_edit.success'
                );

                $this->get("logger")->addWarning(
                    "Car edited",
                    [
                        "formData" => $serializer->serialize($form->getData(), "json"),
                    ]
                );

                return $this->redirect(
                    $this->generateUrl("edit_car", ["id" => $car->getId()])
                );
            } else {
                $request->getSession()->getFlashBag()->add(
                    'error',
                    'flash.car_edit.errors'
                );

                $this->get("logger")->addWarning(
                    "Car editing error",
                    [
                        "formData" => $serializer->serialize($form->getData(), "json"),
                        "formError" => $serializer->serialize($form->getErrors(true), "json"),
                    ]
                );

                $statusCode = 400;
            }
        }

        return new Response(
            $this->renderView(
                '@Front/Car/edit.html.twig',
                ["form" => $form->createView(), "car" => $car]
            ),
            $statusCode
        );
    }

    /**
     * Lists all Car entities.
     *
     * @Route("/", name="car")
     * @Method("GET")
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('FrontBundle:Car')->findAll();

        return array(
            'entities' => $entities,
        );
    }

    /**
     * Finds and displays a Car entity.
     *
     * @Route("/{id}", name="car_show")
     * @Method("GET")
     * @Template()
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('FrontBundle:Car')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Car entity.');
        }

        return array(
            'entity'      => $entity,
        );
    }

    /**
     * @Route("/{id}/delete",name="delete_car")
     * @ParamConverter("car", class="FrontBundle:Car")
     * @Method({"GET"})
     */
    public function deleteAction(Request $request, Car $car)
    {
        $em = $this->getDoctrine()->getEntityManager();
        $em->remove($car);
        $em->flush();

        return $this->redirect(
            $request->get("referer")
        );
    }
}
