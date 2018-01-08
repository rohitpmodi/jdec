<?php

namespace App\Repositories\Email;

/**
 * Class AbstractEmailDecorator.
 *
 * @author Rohit Modi <rohitpmodi@gmail.com>
 */
abstract class AbstractEmailDecorator implements EmailInterface
{
    /**
     * @var EmailInterface
     */
    protected $email;

    /**
     * @param EmailInterface $email
     */
    public function __construct(EmailInterface $email)
    {
        $this->email = $email;
    }

    /**
     * @param $id
     *
     * @return mixed
     */
    public function find($id)
    {
        return $this->email->find($id);
    }

    /**
     * @return mixed
     */
    public function all()
    {
        return $this->email->all();
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
        return $this->email->paginate($page = 1, $limit = 10, $all = false);
    }

   
}
