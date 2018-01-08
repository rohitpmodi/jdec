<?php

namespace App\Repositories\Product;

use App\Services\Cache\CacheInterface;

/**
 * Class CacheDecorator.
 *
 * @author Rohit Modi <rohitpmodi@gmail.com>
 */
class CacheDecorator extends AbstractProductDecorator
{
    /**
     * @var \App\Services\Cache\CacheInterface
     */
    protected $cache;

    /**
     * Cache key.
     *
     * @var string
     */
    protected $cacheKey = 'product';

    /**
     * @param ProductInterface $product
     * @param CacheInterface    $cache
     */
    public function __construct(ProductInterface $product, CacheInterface $cache)
    {
        parent::__construct($product);
        $this->cache = $cache;
    }

    /**
     * @param $id
     *
     * @return mixed
     */
    public function find($id)
    {
        $key = md5(getLang().$this->cacheKey.'.id.'.$id);

        if ($this->cache->has($key)) {
            return $this->cache->get($key);
        }

        $product = $this->product->find($id);

        $this->cache->put($key, $product);

        return $product;
    }

    /**
     * @return mixed
     */
    public function all()
    {
        $key = md5(getLang().$this->cacheKey.'.all.categories');

        if ($this->cache->has($key)) {
            return $this->cache->get($key);
        }

        $categories = $this->product->all();

        $this->cache->put($key, $categories);

        return $categories;
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
        $allkey = ($all) ? '.all' : '';
        $key = md5(getLang().$this->cacheKey.'.page.'.$page.'.'.$limit.$allkey);

        if ($this->cache->has($key)) {
            return $this->cache->get($key);
        }

        $paginated = $this->product->paginate($page, $limit, $all);
        $this->cache->put($key, $paginated);

        return $paginated;
    }

   }
