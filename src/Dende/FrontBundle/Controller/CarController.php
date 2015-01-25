<?php

namespace Dende\FrontBundle\Controller;

use Dende\FrontBundle\Entity\Car;
use Dende\FrontBundle\Entity\Image;
use Doctrine\Common\Collections\ArrayCollection;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Dende\FrontBundle\Form\Type\ContactType;

/**
 * Class CarController
 * @package Dende\FrontBundle\Controller
 *
 * @Route("/car")
 */
class CarController extends Controller
{

    /**
     * @Route("/add",name="add_car")
     * @Method({"GET","POST"})
     */
    public function addAction(Request $request)
    {
        $form = $this->createForm('dende_form_add_car');

        $statusCode = 200;

        if ($request->isMethod("POST")) {
            $form->handleRequest($request);

            if ($form->isValid()) {
                $car = $form->getData();
                $em = $this->getDoctrine()->getManager();
                $uploadableManager = $this->get("stof_doctrine_extensions.uploadable.manager");

                /**
                 * @var Image[] $images
                 */
                $images = $car->getImages();

                foreach($images as $image) {
                    /**
                     * @var Image $image
                     */
                    $image->setCar($car);
                    $uploadableManager->markEntityToUpload($image, $image->getFile());
                }

                $em->persist($car);
                $em->flush();
            } else {
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
     */
    public function editAction(Request $request, Car $car)
    {
        $statusCode = 200;
        $originalImages = clone($car->getImages());
        $form = $this->createForm('dende_form_add_car', $car);

        if ($request->isMethod("POST")) {
            $form->handleRequest($request);

            if ($form->isValid()) {
                $car = $form->getData();
                $em = $this->getDoctrine()->getManager();
                $uploadableManager = $this->get("stof_doctrine_extensions.uploadable.manager");

                foreach ($originalImages as $image) {
                    /**
                     * @var Image $image
                     */
                    if (!$car->getImages()->contains($image)) {
                        $image->setCar(null);
                        $em->remove($image);
                    }
                }

                /**
                 * @var Image[] $images
                 */
                $images = $car->getImages();

                foreach($images as $image) {
                    /**
                     * @var Image $image
                     */
                    $image->setCar($car);

                    if ($image->getFile() !== null) {
                        $uploadableManager->markEntityToUpload($image, $image->getFile());
                    }
                }

                $em->persist($car);
                $em->flush();
            } else {
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
}
