<?php

namespace App\Repositories\Standard;

/**
 * Class AbstractStandardDecorator.
 *
 * @author Rohit Modi <rohitpmodi@gmail.com>
 */
abstract class AbstractStandardDecorator implements StandardInterface
{
    /**
     * @var StandardInterface
     */
    protected $standard;

    /**
     * @param StandardInterface $standard
     */
    public function __construct(StandardInterface $standard)
    {
        $this->standard = $standard;
    }

    /**
     * @param $id
     *
     * @return mixed
     */
    public function find($id)
    {
        return $this->standard->find($id);
    }

    /**
     * @return mixed
     */
    public function all()
    {
        return $this->standard->all();
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
        return $this->standard->paginate($page = 1, $limit = 10, $all = false);
    }

   
}
