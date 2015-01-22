<?php

namespace Dende\FrontBundle\Controller;

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
     * @Template()
     */
    public function addAction(Request $request)
    {
        $form = $this->createForm('dende_form_add_car');

        if ($request->getMethod() == 'POST') {
            $form->handleRequest($request);

            if ($form->isValid()) {
                die("OK");
            }
        }

        return array(
            "form" => $form->createView(),
        );
    }
}
