<?php
namespace Dende\FrontBundle\Menu;

use Knp\Menu\FactoryInterface;
use Symfony\Component\DependencyInjection\ContainerAware;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\SecurityContext;

class MenuBuilder extends ContainerAware
{
    /**
     * @var FactoryInterface
     */
    private $factory;

    /**
     * @var SecurityContext
     */
    private $securityContext;

    /**
     * @var array
     */
    private $languages = ['pl'];

    /**
     * @param FactoryInterface $factory
     */
    public function __construct(FactoryInterface $factory, SecurityContext $context, array $languages)
    {
        $this->factory         = $factory;
        $this->securityContext = $context;
        $this->languages       = $languages;
    }

    public function createLangMenu(Request $request)
    {
        $menu = $this->factory->createItem('root');

        $menu->setChildrenAttributes([
            'class' => 'nav navbar-nav pull-right',
            'id'    => 'langMenu',
        ]);

        foreach ($this->languages as $language) {
            $menu->addChild($language, ['route' => 'switch_language', 'routeParameters' => ['locale' => $language]])
            ->setLabel(strtoupper($language));
        }

        $locale = $request->getLocale();

        if (in_array($locale, $this->languages)) {
            $menu->getChild($locale)->setCurrent(true);
            $menu->getChild($locale)->setLinkAttribute('class', 'active');
        }

        return $menu;
    }

    public function createMainMenu(Request $request)
    {
        $menu = $this->factory->createItem('root');

        if (!$this->securityContext->isGranted('IS_AUTHENTICATED_REMEMBERED')) {
            return $menu;
        }

        $menu->setChildrenAttributes([
            'class' => 'nav navbar-nav pull-left',
            'id'    => 'mainMenu',
        ]);

        $menu->addChild('menu.main.dashboard', ['route' => 'dashboard_index']);
        $menu->addChild('menu.main.cars', ['route' => 'car']);
        $menu->addChild('menu.main.logout', ['route' => 'fos_user_security_logout']);

        if (preg_match("@\/cars@ui", $request->getRequestUri())) {
            $menu->getChild('menu.main.cars')->setCurrent(true);
        }

        return $menu;
    }

    public function createFrontMenu(Request $request)
    {
        $menu = $this->factory->createItem('root');

        $menu->setChildrenAttributes([
            'class' => 'sf-menu responsive-menu',
            'id'    => 'frontMenu',
        ]);

        $menu->addChild('menu.front.start', ['route' => 'main']);
        $menu->addChild('menu.front.search', ['route' => 'list']);
        $menu->addChild('menu.front.contact', ['route' => 'contact']);

        return $menu;
    }
}
