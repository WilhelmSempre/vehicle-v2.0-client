<?php

namespace App\Menu;

use Knp\Menu\FactoryInterface;
use Knp\Menu\ItemInterface;

/**
 * Class Menu
 * @package App\Menu
 *
 * @author Rafał Głuszak <rafal.gluszak@gmail.com>
 */
class Menu
{

    /**
     * @var FactoryInterface
     */
    private $factory;

    /**
     * Menu constructor.
     * @param FactoryInterface $factory
     */
    public function __construct(FactoryInterface $factory)
    {
        $this->factory = $factory;
    }

    /**
     * @return ItemInterface
     */
    public function createSidebarMenu(): ItemInterface
    {

        /** @var ItemInterface $menu */
        $menu = $this->factory
            ->createItem('sidebar', [
                'childrenAttributes' => [
                    'class' => 'nav nav-pills nav-sidebar flex-column vehicle-navbar-list',
                    'data-widget' => 'treeview',
                    'role' => 'menu',
                ],
            ]);

        $starterPageMenuItem = $menu->addChild('Starter pages', [
            'attributes' => [
                'class' => 'nav-item has-treeview menu-open vehicle-navbar-item',
            ],
            'labelAttributes' => [
                'class' => 'vehicle-navbar-link-title',
            ],
            'linkAttributes' => [
                'class' => 'nav-link active vehicle-navbar-link',
            ],
            'childrenAttributes' => [
                'class' => 'nav nav-treeview',
            ],
            'route' => 'main',
            'extras' => [
                'icon' => 'fas fa-tachometer-alt',
            ],
        ]);

        $starterPageMenuItem->addChild('Test', [
            'attributes' => [
                'class' => 'nav-item has-treeview menu-open vehicle-navbar-item',
            ],
            'labelAttributes' => [
                'class' => 'vehicle-navbar-link-title',
            ],
            'linkAttributes' => [
                'class' => 'nav-link vehicle-navbar-link',
            ],
            'childrenAttributes' => [
                'class' => 'nav nav-treeview',
            ],
            'route' => 'main',
            'extras' => [
                'icon' => 'far fa-circle',
            ],
        ]);

        $starterPageMenuItem->addChild('Test disabled', [
            'attributes' => [
                'class' => 'nav-item has-treeview menu-open vehicle-navbar-item',
            ],
            'labelAttributes' => [
                'class' => 'vehicle-navbar-link-title',
            ],
            'linkAttributes' => [
                'class' => 'nav-link vehicle-navbar-link',
            ],
            'childrenAttributes' => [
                'class' => 'nav nav-treeview',
            ],
            'route' => 'main',
            'extras' => [
                'icon' => 'far fa-circle',
            ],
        ]);

        $menu->addChild('Simple link', [
            'attributes' => [
                'class' => 'nav-item has-treeview menu-open vehicle-navbar-item',
            ],
            'labelAttributes' => [
                'class' => 'vehicle-navbar-link-title',
            ],
            'linkAttributes' => [
                'class' => 'nav-link vehicle-navbar-link',
            ],
            'childrenAttributes' => [
                'class' => 'nav nav-treeview',
            ],
            'route' => 'main',
            'extras' => [
                'icon' => 'fas fa-th',
                'badge' => [
                    'content' => 'New',
                ],
            ],
        ]);

        return $menu;
    }
}