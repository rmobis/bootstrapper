<?php

namespace Bootstrapper;

use Illuminate\Routing\UrlGenerator;

class Navigation extends RenderedObject
{

    const NAVIGATION_PILLS = 'nav-pills';
    const NAVIGATION_TABS = 'nav-tabs';
    private $attributes = [];
    private $type = 'nav-tabs';
    private $links = [];
    /**
     * @var UrlGenerator
     */
    private $url;
    private $autoroute = true;

    public function __construct(UrlGenerator $urlGenerator)
    {
        $this->url = $urlGenerator;
    }

    public function render()
    {
        $attributes = new Attributes($this->attributes, ['class' => "nav {$this->type}"]);
        $string = "<ul {$attributes}>";
        foreach ($this->links as $link) {
            if (isset($link['link'])) {
                $string .= $this->renderLink($link);
            } else {
                $string .= $this->renderDropdown($link);
            }
        }

        $string .= "</ul>";

        return $string;
    }

    public function withAttributes($attributes)
    {
        $this->attributes = $attributes;

        return $this;
    }

    public function pills($links = [])
    {
        $this->type = self::NAVIGATION_PILLS;

        return $this->links($links);
    }

    public function links($links)
    {
        $this->links = $links;

        return $this;
    }

    public function tabs($links = [])
    {
        $this->type = self::NAVIGATION_TABS;

        return $this->links($links);
    }

    /**
     * @param $link
     * @return string
     */
    private function renderLink($link)
    {
        $string = '';
        if ($this->itemShouldBeActive($link)) {
            $string .= '<li class=\'active\'>';
        } else {
            $string .= '<li>';
        }
        $string .= "<a href='{$link['link']}'>{$link['title']}</a></li>";

        return $string;
    }

    public function autoroute($autoroute)
    {
        $this->autoroute = $autoroute;

        return $this;
    }

    private function renderDropdown($link)
    {
        if($this->dropdownShouldBeActive($link)) {
            $string = '<li class=\'dropdown active\'>';
            // Prevent active state being added to any other links
            $this->autoroute(false);
        } else {
            $string = '<li class=\'dropdown\'>';
        }
        $string .= "<a class='dropdown-toggle' data-toggle='dropdown' href='#'>{$link[0]} <span class='caret'></span></a>";
        $string .= '<ul class=\'dropdown-menu\' role=\'menu\'>';
        foreach ($link[1] as $item) {
            $string .= $this->renderLink($item);
        }
        $string .= '</ul>';
        $string .= '</li>';

        return $string;
    }

    private function dropdownShouldBeActive($dropdown)
    {
        if($this->autoroute) {
            foreach ($dropdown[1] as $item) {
                if($this->itemShouldBeActive($item)) {
                    return true;
                }
            }
        }
        return false;
    }

    /**
     * @param $link
     * @return bool
     */
    private function itemShouldBeActive($link)
    {
        return $this->autoroute && $this->url->current() == $link['link'];
    }
}
