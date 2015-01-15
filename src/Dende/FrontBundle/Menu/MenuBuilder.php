<?php

namespace Dende\FrontBundle\Menu;

use Knp\Menu\FactoryInterface;
use Symfony\Component\DependencyInjection\ContainerAware;
use Symfony\Component\HttpFoundation\Request;

class MenuBuilder extends ContainerAware
{
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
