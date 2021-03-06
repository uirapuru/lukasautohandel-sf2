<?php
namespace LAH\AdminBundle\Controller;

use A2lix\TranslationFormBundle\Annotation\GedmoTranslation;
use LAH\MainBundle\Entity\Car;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Form;
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
        $form = $this->createForm('lah_car');

        if ($request->isMethod('POST')) {
            $form->handleRequest($request);
            $serializer = $this->get('jms_serializer');

            if ($form->isValid()) {
                $car = $form->getData();
                $this->saveCar($car, $form);
                return $this->redirect($this->generateUrl('edit_car', ['id' => $car->getId()]));
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
        return new Response($this->renderView('@LAHAdmin/Car/add.html.twig', [
                'form' => $form->createView()
            ]), isset($statusCode) ? $statusCode : 200);
    }

    /**
     * @Route("/edit/{id}",name="edit_car")
     * @ParamConverter("car", class="LAHMainBundle:Car")
     *
     * @Method({"GET","POST"})
     * @GedmoTranslation()
     */
    public function editAction(Request $request, Car $car)
    {
        $form       = $this->createForm('lah_car', $car);
        $serializer = $this->get('jms_serializer');

        $this->get('lah.admin.handler.process_images')->setOriginalImages($car->getImages());
        $this->get('lah.admin.handler.process_prices')->setOriginalPrices(clone($car->getPrices()));

        if ($request->isMethod('POST')) {
            $form->handleRequest($request);

            if ($form->isValid()) {
                $this->saveCar($car, $form);
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

        return new Response($this->renderView('@LAHAdmin/Car/edit.html.twig', [
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
            'template' => 'LAHAdminBundle:Car:list.html.twig',
            'action' => $this->generateUrl('car'),
            'showHidden' => true
        ]);
    }


    /**
     * Finds and displays a Car entity.
     *
     * @Route("/{id}", name="car_show")
     * @ParamConverter("car", class="LAHMainBundle:Car")
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
     * @ParamConverter("car", class="LAHMainBundle:Car")
     *
     * @Method({"POST"})
     */
    public function deleteAction(Request $request, Car $car)
    {
        $this->get('lah.main.manager.car')->delete($car);
        $this->getDoctrine()->getManager()->getConfiguration()->getResultCacheImpl()->deleteAll();
        return $this->redirectToRoute("car");
    }

    private function saveCar(Car $car, Form $form)
    {
        $this->get('lah.admin.handler.car')->setCar($car)->handle($form);

        $em = $this->getDoctrine()->getManager();
        $em->persist($car);
        $em->flush();
        $em->getConfiguration()->getResultCacheImpl()->deleteAll();

        $this->addFlash('success', 'flash.car_edit.success');
        $this->get('logger')->addWarning('Car edited', ['formData' => $this->get("jms_serializer")->serialize($form->getData(), 'json')]);
    }
}
