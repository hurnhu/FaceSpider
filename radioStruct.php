<?php

/**
 * Created by PhpStorm.
 * User: mike
 * Date: 2/28/2017
 * Time: 4:40 PM
 */
class radioStruct
{
    private $name;
    private $value;


    public function zero(){
        $this->value = 0;
    }
    public function one(){
        $this->value = 1;
    }

    /**
     * radioStruct constructor.
     * @param $name
     * @param $value
     */
    public function __construct($name, $value)
    {
        $this->name = $name;
        $this->value = $value;
    }

    /**
     * @return mixed
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * @param mixed $value
     */
    public function setValue($value)
    {
        $this->value = $value;
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
}