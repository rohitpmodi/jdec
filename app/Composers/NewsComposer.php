<?php

namespace App\Composers;

use App\Repositories\News\NewsInterface;
use News;

/**
 * Class MenuComposer.
 *
 * @author Rohit Modi <rohitpmodi@gmail.com>
 */
class NewsComposer
{
    /**
     * @var \App\Repositories\News\NewsInterface
     */
    protected $news;

    /**
     * NewsComposer constructor.
     *
     * @param NewsInterface $news
     */
    public function __construct(NewsInterface $news)
    {
        $this->news = $news;
    }

    /**
     * @param $view
     */
    public function compose($view)
    {
        $news = $this->news->getLastNews(5);
        $view->with('news', $news);
    }
}
