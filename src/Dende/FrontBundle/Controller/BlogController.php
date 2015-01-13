<?php

namespace Dende\FrontBundle\Controller;

use Dende\FrontBundle\Entity\Article;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

/**
 * Class BlogController
 * @package Dende\FrontBundle\Controller
 * @Route("/blog")
 */
class BlogController extends Controller
{
    /**
     * @Route("/", name="blog")
     * @Template()
     */
    public function indexAction()
    {
        $articles = $this->getDoctrine()->getRepository("FrontBundle:Article")->findAll();

        return [
            "articles" => $articles
        ];
    }
    /**
     * @Route("/{slug}", name="show_article")
     * @ParamConverter("article", class="FrontBundle:Article")
     * @Template()
     */
    public function showArticleAction(Article $article)
    {
        return [
            "article" => $article
        ];
    }
}
