<?php

namespace App\Composers;

use App\Models\Category;
use App\Repositories\Category\CategoryInterface;

/**
 * Class ProductSidebarComposer.
 *
 * @author Rohit Modi <rohitpmodi@gmail.com>
 */
class ProductSidebarComposer
{
    protected $categories;

    public function __construct(CategoryInterface $category)
    {
        $this->category = $category;
    }

    public function compose($view)
    {
    }
}
