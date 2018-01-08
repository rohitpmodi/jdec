<?php

namespace App\Repositories\Product;

/**
 * Class AbstractProductDecorator.
 *
 * @author Rohit Modi <rohitpmodi@gmail.com>
 */
abstract class AbstractProductDecorator implements ProductInterface
{
    /**
     * @var ProductInterface
     */
    protected $product;

    /**
     * @param ProductInterface $product
     */
    public function __construct(ProductInterface $product)
    {
        $this->product = $product;
    }

    /**
     * @param $id
     *
     * @return mixed
     */
    public function find($id)
    {
        return $this->product->find($id);
    }

    /**
     * @return mixed
     */
    public function all()
    {
        return $this->product->all();
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
        return $this->product->paginate($page = 1, $limit = 10, $all = false);
    }

   
}
