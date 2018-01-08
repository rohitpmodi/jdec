<?php

namespace App\Repositories\Order;

/**
 * Class AbstractOrderDecorator.
 *
 * @author Rohit Modi <rohitpmodi@gmail.com>
 */
abstract class AbstractOrderDecorator implements OrderInterface
{
    /**
     * @var OrderInterface
     */
    protected $order;

    /**
     * @param OrderInterface $order
     */
    public function __construct(OrderInterface $order)
    {
        $this->order = $order;
    }

    /**
     * @param $id
     *
     * @return mixed
     */
    public function find($id)
    {
        return $this->order->find($id);
    }

    /**
     * @return mixed
     */
    public function all()
    {
        return $this->order->all();
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
        return $this->order->paginate($page = 1, $limit = 10, $all = false);
    }

   
}
