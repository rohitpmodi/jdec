<?php

namespace App\Repositories\Book;

/**
 * Class AbstractBookDecorator.
 *
 * @author Rohit Modi <rohitpmodi@gmail.com>
 */
abstract class AbstractBookDecorator implements BookInterface
{
    /**
     * @var BookInterface
     */
    protected $book;

    /**
     * @param BookInterface $book
     */
    public function __construct(BookInterface $book)
    {
        $this->book = $book;
    }

    /**
     * @param $id
     *
     * @return mixed
     */
    public function find($id)
    {
        return $this->book->find($id);
    }

    /**
     * @return mixed
     */
    public function all()
    {
        return $this->book->all();
    }

    /**
     * @param int  $page
     * @param int  $limit
     * @param bool $all
     *
     * @return mixed
     */
    public function paginate($page = 1, $limit = 10, $all = false)
    {
        return $this->book->paginate($page = 1, $limit = 10, $all = false);
    }

   
}
