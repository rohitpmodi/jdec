<?php

namespace App\Composers;

use App\Repositories\Article\ArticleInterface;

/**
 * Class ArticleComposer.
 *
 * @author Rohit Modi <rohitpmodi@gmail.com>
 */
class HomePageComposer
{
    /**
     * @var \App\Repositories\Article\ArticleInterface
     */
    protected $article;

    /**
     * @param ArticleInterface $article
     */
    public function __construct(ArticleInterface $article)
    {
        $this->article = $article;
    }

    /**
     * @param $view
     */
    public function compose($view)
    {
        $articles = $this->article->getLastArticle(4);
        $view->with('articles', $articles);
    }
}
