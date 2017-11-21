<?php
/**
 * Created by PhpStorm.
 * User: jasonswords
 * Date: 20/11/2017
 * Time: 10:23
 */

namespace Itb;


class League
{

    private $id;
    private $name;
    private $country;
    private $drone;
    private $position;

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param mixed $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @return mixed
     */
    public function getCountry()
    {
        return $this->country;
    }

    /**
     * @param mixed $country
     */
    public function setCountry($country)
    {
        $this->country = $country;
    }

    /**
     * @return mixed
     */
    public function getDrone()
    {
        return $this->drone;
    }

    /**
     * @param mixed $drone
     */
    public function setDrone($drone)
    {
        $this->drone = $drone;
    }

    /**
     * @return mixed
     */
    public function getPosition()
    {
        return $this->position;
    }

    /**
     * @param mixed $position
     */
    public function setPosition($position)
    {
        $this->position = $position;
    }

}