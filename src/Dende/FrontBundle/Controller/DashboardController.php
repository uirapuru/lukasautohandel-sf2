<?php

namespace Dende\FrontBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class CarController.
 *
 * @Route("/admin/")
 */
class DashboardController extends Controller
{
    /**
     * @Route("/",name="dashboard_index")
     *
     * @Method({"GET"})
     * @Template()
     */
    public function indexAction(Request $request)
    {
        return [];
    }
}
