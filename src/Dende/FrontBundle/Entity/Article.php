<?php
namespace Dende\FrontBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Entity
 * @ORM\Table(name="articles")
 * @codeCoverageIgnore
 */

class Article
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @Gedmo\Slug(fields={"titleEn"}, updatable=true, separator="-", unique=true)
     * @ORM\Column(name="slug", type="string", length=255, nullable=false)
     * @var string $slug
     */
    protected $slug;

    /**
     * @ORM\Column(name="title_en", type="string", length=255, nullable=false)
     * @var string $titleEn
     */
    protected $titleEn;

    /**
     * @ORM\Column(name="title_pl", type="string", length=255, nullable=false)
     * @var string $titlePl
     */
    protected $titlePl;

    /**
     * @ORM\Column(name="template_en", type="text", nullable=false)
     * @var string $templateEn
     */
    protected $templateEn;

    /**
     * @ORM\Column(name="template_pl", type="text", nullable=false)
     * @var string $templatePl
     */
    protected $templatePl;

    public function getTitle($culture = "pl")
    {
        if ($culture === "pl") {
            return $this->getTitlePl();
        }

        return $this->getTitleEn();
    }

    public function getTemplate($culture = "pl")
    {
        if ($culture === "pl") {
            return $this->getTemplatePl();
        }

        return $this->getTemplateEn();
    }

    /**
     * @return string
     */
    public function getSlug()
    {
        return $this->slug;
    }

    /**
     * @param string $slug
     */
    public function setSlug($slug)
    {
        $this->slug = $slug;
    }

    /**
     * @return string
     */
    public function getTemplateEn()
    {
        return $this->templateEn;
    }

    /**
     * @param string $templateEn
     */
    public function setTemplateEn($templateEn)
    {
        $this->templateEn = $templateEn;
    }

    /**
     * @return string
     */
    public function getTemplatePl()
    {
        return $this->templatePl;
    }

    /**
     * @param string $templatePl
     */
    public function setTemplatePl($templatePl)
    {
        $this->templatePl = $templatePl;
    }

    /**
     * @return string
     */
    public function getTitleEn()
    {
        return $this->titleEn;
    }

    /**
     * @param string $titleEn
     */
    public function setTitleEn($titleEn)
    {
        $this->titleEn = $titleEn;
    }

    /**
     * @return string
     */
    public function getTitlePl()
    {
        return $this->titlePl;
    }

    /**
     * @param string $titlePl
     */
    public function setTitlePl($titlePl)
    {
        $this->titlePl = $titlePl;
    }
}
