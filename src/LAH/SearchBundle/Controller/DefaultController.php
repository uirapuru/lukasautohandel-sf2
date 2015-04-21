<?php

namespace LAH\SearchBundle\Controller;

use LAH\SearchBundle\Model\SearchQuery;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;

class DefaultController extends Controller
{
    /**
     * @Template()
     */
    public function searchAction($form = null, $action = null)
    {
        if (!$form) {
            $form = $this->createForm('search', new SearchQuery(), [
                "method" => "GET",
                "action" => $action
            ]);
        }

        return ['form' => $form->createView()];
    }

    public function listAction(Request $request, $template, $action = null)
    {
        $searchQuery = new SearchQuery();
        $form = $this->createForm('search', $searchQuery, [
            "method" => "GET",
            "action" => $action
        ]);

        $queryBuilder = $this->getDoctrine()->getRepository('LAHMainBundle:Car')->createQueryBuilder('c');

        $cacheId = ['LAHSearchBundle:Default:listAction'];

        $this->get('lah.search.query_entity_merge')->merge($searchQuery);

        $form->handleRequest($request);

        if ($form->isValid()) {
            /*
             * @var SearchQuery
             */
            $searchQuery = $form->getData();

            $this->get('lah.search.query_modifier')->modify($searchQuery, $queryBuilder, $cacheId);
        } elseif ($form->isSubmitted() && !$form->isValid()) {
            $pagination =  $this->get('knp_paginator')->paginate([], 1, 10);

            return $this->render($template, [
                'cars'       => $pagination,
                'searchForm' => $form,
            ]);
        }

        /*
         * @var PersistentCollection $cars
         */

        $query = $queryBuilder->getQuery();
        $query->useResultCache(true, 3600, md5(implode('/', $cacheId)));
        $pagination =  $this->get('knp_paginator')->paginate($query, $request->get('page', 1), 10);

        return $this->render($template, [
            'cars'       => $pagination,
            'searchForm' => $form,
        ]);
    }
}
