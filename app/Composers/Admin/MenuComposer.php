<?php

namespace App\Composers\Admin;

use App\Models\FormPost;

/**
 * Class MenuComposer.
 *
 * @author Phillip Madsen <contact@affordableprogrammer.com>
 */
class MenuComposer
{
    /**
     * @param $view
     */
    public function compose($view)
    {
        $view->with('formPostMessage', FormPost::orderBy('created_at', 'DESC')->where('lang', getLang())->where('is_read','0')->paginate(15));
    }
}
