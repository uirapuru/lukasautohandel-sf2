<?php

namespace Dende\FrontBundle\Menu;

use Knp\Menu\FactoryInterface;
use Symfony\Component\DependencyInjection\ContainerAware;
use Symfony\Component\HttpFoundation\Request;

class MenuBuilder extends ContainerAware
{
    /**
     * @var array $socialLinks
     */
    private $socialLinks;

    /**
     * @var FactoryInterface
     */
    private $factory;

    /**
     * @param FactoryInterface $factory
     */
    public function __construct(FactoryInterface $factory)
    {
        $this->factory = $factory;
    }

    /**
     * @param array $links
     */
    public function setSocialLinks(array $links)
    {
        $this->socialLinks = $links;
    }

    public function createSocialMenu()
    {
        $menu = $this->factory->createItem('root');

        $menu->setChildrenAttributes(array(
            'class' => 'nav nav-pills pull-right',
            'id' => 'socialMenu',
        ));

        $menu->addChild('mail', array('uri' => $this->socialLinks["contact_mail"]))
            ->setLinkAttribute("class", "fa fa-envelope")
            ->setLabel('');
        $menu->addChild('fb', array('uri' => $this->socialLinks["facebook"]))
            ->setLinkAttribute("class", "fa fa-facebook")
            ->setLabel('');
        $menu->addChild('li', array('uri' => $this->socialLinks["linkedin"]))
            ->setLinkAttribute("class", "fa fa-linkedin")
            ->setLabel('');
        $menu->addChild('gl', array('uri' => $this->socialLinks["goldenline"]))
            ->setLinkAttribute("class", "fa fa-compress")
            ->setLabel('');
        $menu->addChild('github', array('uri' => $this->socialLinks["github"]))
            ->setLinkAttribute("class", "fa fa-github")
            ->setLabel('');
        $menu->addChild('yt', array('uri' => $this->socialLinks["youtube"]))
            ->setLinkAttribute("class", "fa fa-youtube")
            ->setLabel('');

        return $menu;
    }

    public function createLangMenu(Request $request)
    {
        $menu = $this->factory->createItem('root');

        $menu->setChildrenAttributes(array(
            'class' => 'nav nav-pills pull-right',
            'id' => 'langMenu',
        ));

        $menu->addChild('pl', array('route' => 'switch_language', 'routeParameters' => array('locale' => 'pl')))
            ->setLinkAttribute("class", "flag-icon flag-icon-pl")
            ->setLabel('');

        $menu->addChild('en', array('route' => 'switch_language', 'routeParameters' => array('locale' => 'en')))
            ->setLinkAttribute("class", "flag-icon flag-icon-gb")
            ->setLabel('');

//        $menu->addChild('de', array('route' => 'switch_language', 'routeParameters' => array('locale' => 'de')))
//            ->setLinkAttribute("class", "flag-icon flag-icon-de")
//            ->setLabel('');
//
//        $menu->addChild('pt', array('route' => 'switch_language', 'routeParameters' => array('locale' => 'pt')))
//            ->setLinkAttribute("class", "flag-icon flag-icon-br")
//            ->setLabel('');

        $locale = $request->getLocale();

        if (in_array($locale, array("pl", "en", "de", "pt"))) {
            $menu->getChild($locale)->setCurrent(true);
        }

        return $menu;
    }

    public function createMainMenu()
    {
        $menu = $this->factory->createItem('root');

        $menu->setChildrenAttributes(array(
            'class' => 'nav nav-pills pull-left',
            'id' => 'mainMenu',
        ));

        $menu->addChild('menu.main.blog', array('route' => 'blog'));
        $menu->addChild('menu.main.cv', array('route' => 'cv'));
        $menu->addChild('menu.main.projects', array('route' => 'projects'));
        $menu->addChild('menu.main.contact', array('route' => 'contact'));

        return $menu;
    }
}
