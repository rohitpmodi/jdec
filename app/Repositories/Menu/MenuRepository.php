<?php

namespace App\Repositories\Menu;

use App\Models\Menu;
use App\Repositories\RepositoryAbstract;

/**
 * Class MenuRepository.
 *
 * @author Rohit Modi <rohitpmodi@gmail.com>
 */
class MenuRepository extends RepositoryAbstract implements MenuInterface
{
    /**
     * @var \Menu
     */
    protected $menu;

    /**
     * @param Menu $menu
     */
    public function __construct(Menu $menu)
    {
        $this->menu = $menu;
    }

    /**
     * @return mixed
     */
    public function all()
    {
        return $this->menu->where('is_published', 1)->where('lang', $this->getLang())->orderBy('order', 'asc')->get();
    }

    /**
     * @param $menu
     * @param int  $parentId
     * @param bool $starter
     *
     * @return null|string
     */
	public function generateFrontMenu($menu, $parentId = 0, $starter = false)
	{
		$result = null;

		foreach ($menu as $item) {
			if ($item->parent_id == $parentId) {
                                $icon_class = ($item->icon_class ? $item->icon_class : '');
                                $icon_class_append = ($item->icon_class ? '<dd></dd>' : '');
				$childItem = $this->hasChildItems($item->id);
				$result .= "<li itemprop='name' class='".$icon_class." menu-item ".(($childItem) ? 'dropdown' : null).(($childItem && $item->parent_id != 0) ? ' dropdown-submenu' : null)."'>
                                <a itemprop='url' href='".url($item->url)."' ".(($childItem) ? 'class="dropdown-toggle" data-toggle="dropdown"' : null).">{$icon_class_append}{$item->title}".(($childItem && $item->parent_id == 0) ? '<b class="caret"></b>' : null).'</a>'.$this->generateFrontMenu($menu, $item->id).'
                            </li>';
			}
		}

		return $result ? "\n<ul itemscope itemtype='http://schema.org/SiteNavigationElement' class='".(($starter) ? ' nav navbar-nav navbar-right ' : null).((!$starter) ? ' dropdown-menu ' : null)."'>\n$result</ul>\n" : null;
	}

    /**
     * @param $items
     *
     * @return null|string
     */
    public function getFrontMenuHTML($items)
    {
        return $this->generateFrontMenu($items, 0, true);
    }

    /**
     * @param $id
     *
     * @return bool
     */
    public function hasChildItems($id)
    {
        $count = $this->menu->where('parent_id', $id)->where('is_published', 1)->where('lang', $this->getLang())->get()->count();
        if ($count === 0) {
            return false;
        }

        return true;
    }
}
