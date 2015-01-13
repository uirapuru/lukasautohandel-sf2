<?php

namespace Dende\FrontBundle\DataFixtures\ORM;

use Dende\FrontBundle\DataFixtures\BaseFixture;
use Dende\FrontBundle\Entity\Article;

class ArticlesData extends BaseFixture
{
    public function getOrder()
    {
        return 1;
    }

    /**
     * @param $params
     * @return Article
     * @SuppressWarnings(PHPMD.UnusedLocalVariable)
     */
    public function insert($params)
    {
        extract($params);

        $article = new Article();
        $article->setTitleEn($titleEn);
        $article->setTitlePl($titlePl);
        $article->setTemplateEn($templateEn);
        $article->setTemplatePl($templatePl);

        return $article;
    }
}
