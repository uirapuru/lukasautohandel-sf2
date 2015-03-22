<?php

namespace Dende\FrontBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class CarController.
 *
 * @Route("/image")
 */
class ImageController extends Controller
{
    /**
     * @Route("/add",name="add_image")
     *
     * @Method({"GET","POST"})
     * @Template()
     */
    public function addAction(Request $request)
    {
        $form = $this->createForm('dende_form_add_image');

        $statusCode = 200;

        if ($request->isMethod("POST")) {
            $form->handleRequest($request);

            if ($form->isValid()) {
                $em = $this->getDoctrine()->getManager();
                $image = $form->getData();

                $uploadableManager = $this->get("stof_doctrine_extensions.uploadable.manager");
                $uploadableManager->markEntityToUpload($image, $image->getFile());

                $em->persist($image);
                $em->flush();
            } else {
                $statusCode = 400;
            }
        }

        return new Response(
            $this->renderView('@Front/Image/add.html.twig', ["form" => $form->createView()]),
            $statusCode
        );
    }
}
