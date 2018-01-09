<?php
/**
 * This file is part of FacturaScripts
 * Copyright (C) 2013-2017  Carlos Garcia Gomez  <carlos@facturascripts.com>
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Lesser General Public License as
 * published by the Free Software Foundation, either version 3 of the
 * License, or (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Lesser General Public License for more details.
 *
 * You should have received a copy of the GNU Lesser General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */

namespace FacturaScripts\Plugins\SBAdmin\Lib;

/**
 * Structure for each of the items in the FacturaScripts menu.
 *
 * @author Carlos García Gómez <carlos@facturascripts.com>
 * @author Artex Trading sa <jcuello@artextrading.com>
 */
class MenuItem
{
    /**
     * Indicates whether it is activated or not.
     *
     * @var bool
     */
    public $active;

    /**
     * Fontawesome font icon of the menu option.
     *
     * @var string
     */
    public $icon;

    /**
     * List of menu options for the item.
     *
     * @var MenuItem[]
     */
    public $menu;

    /**
     * Identifying name of the element.
     *
     * @var string
     */
    public $name;

    /**
     * Title of the menu option.
     *
     * @var string
     */
    public $title;

    /**
     * URL for the href of the menu option.
     *
     * @var string
     */
    public $url;

    /**
     * Build and fill the main values of the Item.
     *
     * @param string $name
     * @param string $title
     * @param string $url
     * @param string $icon
     */
    public function __construct($name, $title, $url, $icon = null)
    {
        $this->name = $name;
        $this->title = $title;
        $this->url = $url;
        $this->icon = $icon;
        $this->menu = [];
        $this->active = false;
    }

    /**
     * Returns the HTML for the icon of the item.
     *
     * @return string
     */
    protected function getHTMLIcon()
    {
        return empty($this->icon) ? '<i class="fa fa-fw" aria-hidden="true"></i> ' : '<i class="fa ' . $this->icon
            . ' fa-fw" aria-hidden="true"></i> ';
    }

    /**
     * Returns the indintifier of the menu.
     *
     * @param string $parent
     *
     * @return string
     */
    protected function getMenuId($parent)
    {
        return empty($parent) ? 'menu-' . $this->title : $parent . $this->name;
    }

    /**
     * Returns the html for the menu / submenu.
     *
     * @param string $parent
     *
     * @return string
     */
    public function getHTML($parent = '')
    {
        $active = $this->active ? ' active' : '';
        $liActive = $this->active ? ' show' : '';
        // True case require to be changed (data-toggle must close and not re-open it)
        //$aToggle = $this->active ? 'collapse' : 'collapse';
        $aToggle = 'collapse';
        $ulClassToggled = $this->active ? ' collapse show' : ' collapse';
        $folder = $this->active ? 'fa-folder-open' : 'fa-folder';
        // Class active not used here, can try it with text-uppercase
        $classToggled = $this->active ? ' active collapse' : ' collapsed';
        $expanded = $this->active ? 'true' : 'false';
        $menuId = $this->getMenuId($parent);

        $itemWithParent = '<li class="nav-item' . $liActive . '" data-container="body" data-toggle="tooltip" data-placement="left" data-trigger="hover" title="' . \ucfirst($this->title) . '">'
            . '<a class="nav-link nav-link-collapse' . $classToggled . '" href="#collapse-' . $menuId . '" id="' . $menuId . '" data-toggle="' . $aToggle . '" data-parent="#exampleAccordion" aria-haspopup="true" aria-expanded="' . $expanded . '">'
            . '<i class="fa ' . $folder . ' fa-fw"></i> <span class="nav-link-text">' . \ucfirst($this->title) . '</span></a>'
            . '<ul class="sidenav-second-level' . $ulClassToggled . '" id="collapse-' . $menuId . '">';

        $itemWithoutParent = '<li class="nav-item' . $liActive . '">'
            . '<a class="nav-link nav-link-collapse' . $classToggled . '" href="#collapse-' . $menuId . '" id="' . $menuId . '" data-toggle="' . $aToggle . '">'
            . '<i class="fa ' . $folder . ' fa-fw"></i> <span class="nav-link-text">' . \ucfirst($this->title) . '</span></a>'
            . '<ul class="sidenav-third-level' . $ulClassToggled . '" id="collapse-' . $menuId . '">';

        $html = empty($parent) ? $itemWithParent : $itemWithoutParent;

        foreach ($this->menu as $menuItem) {
            $classToggled = $menuItem->active ? 'class="collapse"' : '';
            $liActive = $menuItem->active ? ' class="active"' : '';
            $menu = '<li' . $liActive . '><a ' . $classToggled . ' href="' . $menuItem->url . '">'
                . '<span class="nav-link-text">' . $menuItem->getHTMLIcon() . ' ' . \ucfirst($menuItem->title)
                . '</span></a></li>';
            $html .= empty($menuItem->menu) ? $menu : $menuItem->getHTML($menuId);
        }

        $html .= '</ul>';
        return $html;
    }
}
