<?php

namespace App\Repositories\Company;

use App\Services\Cache\CacheInterface;

/**
 * Class CacheDecorator.
 *
 * @author Rohit Modi <rohitpmodi@gmail.com>
 */
class CacheDecorator extends AbstractCompanyDecorator
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
    protected $cacheKey = 'company';

    /**
     * @param CompanyInterface $company
     * @param CacheInterface    $cache
     */
    public function __construct(CompanyInterface $company, CacheInterface $cache)
    {
        parent::__construct($company);
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

        $company = $this->company->find($id);

        $this->cache->put($key, $company);

        return $company;
    }

    /**
     * @return mixed
     */
    public function all()
    {
        $key = md5(getLang().$this->cacheKey.'.all.company');

        if ($this->cache->has($key)) {
            return $this->cache->get($key);
        }

        $company = $this->company->all();

        $this->cache->put($key, $company);

        return $company;
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

        $paginated = $this->company->paginate($page, $limit, $all);
        $this->cache->put($key, $paginated);

        return $paginated;
    }

   }
