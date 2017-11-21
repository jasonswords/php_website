<?php

namespace Itb;

class Product
{

    private $id;
    private $name;
    private $price;
    private $image;
    private $description;

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
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
    public function getPrice()
    {
        return $this->price;
    }

    /**
     * @param mixed $price
     */
    public function setPrice($price)
    {
        $this->price = $price;
    }

    /**
     * @return mixed
     */
    public function getImage()
    {
        return $this->image;
    }

    /**
     * @param mixed $image
     */
    public function setImage($image)
    {
        $this->image = $image;
    }

    /**
     * @return mixed
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param mixed $description
     */
    public function setDescription($description)
    {
        $this->description = $description;
    }

    public function __toString()
    {
        $output = '';
        $output .=  '<hr>';
        $output .=  "<br>Id: " . $this->getId();
        $output .=  "<br>Name: " . $this->getName();
        $output .=  "<br>Price: " . $this->getPrice();
        $output .=  "<br>Image: " . $this->getImage();
        $output .=  "<br>Decription: " . $this->getDescription();

        return $output;
    }


}