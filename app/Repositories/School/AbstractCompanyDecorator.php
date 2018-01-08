<?php

namespace App\Repositories\School;

/**
 * Class AbstractSchoolDecorator.
 *
 * @author Rohit Modi <rohitpmodi@gmail.com>
 */
abstract class AbstractSchoolDecorator implements SchoolInterface
{
    /**
     * @var SchoolInterface
     */
    protected $school;

    /**
     * @param SchoolInterface $school
     */
    public function __construct(SchoolInterface $school)
    {
        $this->school = $school;
    }

    /**
     * @param $id
     *
     * @return mixed
     */
    public function find($id)
    {
        return $this->school->find($id);
    }

    /**
     * @return mixed
     */
    public function all()
    {
        return $this->school->all();
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
        return $this->school->paginate($page = 1, $limit = 10, $all = false);
    }

   
}
