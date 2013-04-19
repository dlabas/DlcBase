<?php
namespace DlcBase\Entity;

/**
 * Abstract entity class
 */
abstract class AbstractEntity
{
    /**
     * Magic __get() method - calls the getter for $name
     *
     * @param string $name
     */
    public function __get($name)
    {
        $getterMethod = 'get' . ucfirst($name);
    
        if (!method_exists($this, $getterMethod)) {
            throw new \BadMethodCallException('Method ' . $getterMethod . ' does not exists', 500);
        }
    
        return $this->$getterMethod();
    }
    
    /**
     * Magic __set() method - calls the setter for $name with $value
     *
     * @param string $name
     * @param mixed  $value
     */
    public function __set($name, $value)
    {
        $setterMethod = 'set' . ucfirst($name);
    
        if (!method_exists($this, $setterMethod)) {
            throw new \BadMethodCallException('Method ' . $setterMethod . ' does not exists', 500);
        }
    
        $this->$setterMethod($value);
    }
    
    /**
     * Magic __isset() method - checks via getter if the property $name isset
     *
     * @param string $name
     */
    public function __isset($name)
    {
        $getterMethod = 'get' . ucfirst($name);
    
        if (!method_exists($this, $getterMethod)) {
            return false;
        }
    
        return (($this->$getterMethod() !== null) ? $this->$getterMethod() : false);
    }
}