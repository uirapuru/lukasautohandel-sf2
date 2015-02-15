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
            'class' => 'nav navbar-nav pull-right',
            'id' => 'langMenu',
        ));

        $menu->addChild('pl', array('route' => 'switch_language', 'routeParameters' => array('locale' => 'pl')))
            ->setLinkAttribute("class", "flag-icon flag-icon-pl")
            ->setLabel('');

        $menu->addChild('en', array('route' => 'switch_language', 'routeParameters' => array('locale' => 'en')))
            ->setLinkAttribute("class", "flag-icon flag-icon-gb")
            ->setLabel('');

        $menu->addChild('de', array('route' => 'switch_language', 'routeParameters' => array('locale' => 'de')))
            ->setLinkAttribute("class", "flag-icon flag-icon-de")
            ->setLabel('');

        $locale = $request->getLocale();

        if (in_array($locale, array("pl", "en", "de", "pt"))) {
            $menu->getChild($locale)->setCurrent(true);
        }

        return $menu;
    }

    public function createMainMenu(Request $request)
    {
        $menu = $this->factory->createItem('root');

        $menu->setChildrenAttributes(array(
            'class' => 'nav navbar-nav pull-left',
            'id' => 'mainMenu',
        ));

        $menu->addChild('menu.main.dashboard', array('route' => 'dashboard_index'));
        $menu->addChild('menu.main.cars', array('route' => 'car'));

        if (preg_match("@\/cars@ui", $request->getRequestUri())) {
            $menu->getChild('menu.main.cars')->setCurrent(true);
        }

        return $menu;
    }
}
