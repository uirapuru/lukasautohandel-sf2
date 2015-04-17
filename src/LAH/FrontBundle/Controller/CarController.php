<?php
namespace LAH\FrontBundle\Controller;

use A2lix\TranslationFormBundle\Annotation\GedmoTranslation;
use LAH\FrontBundle\Entity\Car;
use LAH\SearchBundle\Model\SearchQuery;
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
     * @Route("/list", name="car")
     * @Template()
     *
     * @Method({"GET", "POST"})
     */
    public function listAction(Request $request)
    {
        return $this->forward("LAHSearchBundle:Default:list", [
            'request' => $request,
            'template' => 'FrontBundle:Car:list.html.twig',
            'action' => $this->generateUrl('car')
        ]);
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
