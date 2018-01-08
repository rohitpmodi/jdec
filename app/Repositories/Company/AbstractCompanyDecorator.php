<?php

namespace App\Repositories\Company;

/**
 * Class AbstractCompanyDecorator.
 *
 * @author Rohit Modi <rohitpmodi@gmail.com>
 */
abstract class AbstractCompanyDecorator implements CompanyInterface
{
    /**
     * @var CompanyInterface
     */
    protected $company;

    /**
     * @param CompanyInterface $company
     */
    public function __construct(CompanyInterface $company)
    {
        $this->company = $company;
    }

    /**
     * @param $id
     *
     * @return mixed
     */
    public function find($id)
    {
        return $this->company->find($id);
    }

    /**
     * @return mixed
     */
    public function all()
    {
        return $this->company->all();
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
        return $this->company->paginate($page = 1, $limit = 10, $all = false);
    }

   
}
