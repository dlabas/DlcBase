<?php
namespace DlcBase\Service;

use DlcBase\Mapper\AbstractMapper;
use DlcBase\Module\ModuleNamespaceAwareInterface;
use DlcBase\Options\ModuleOptionsAwareInterface;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * Abstract service class
 */
class AbstractService 
    implements ModuleNamespaceAwareInterface, 
               ModuleOptionsAwareInterface, 
               ServiceLocatorAwareInterface
{
    /**
     * Service class name without itt's namespace
     * 
     * @var string
     */
    protected $classNameWithoutNamespace;
    
    /**
     * The mapper class for this service
     * 
     * @var AbstractMapper
     */
    protected $mapper;
    
    /**
     * The module namespace
     * 
     * @var string
     */
    protected $moduleNamespace;
    
    /**
     * The module options
     * 
     * @var DlcBase\Options\ModuleOptions
     */
    protected $options;
    
    /**
     * The service locator
     * 
     * @var ServiceLocatorInterface
     */
    protected $serviceLocator;
    
    /**
     * Magic __call method
     * 
     * @param string $method
     * @param mixed $params
     * @throws \BadFunctionCallException
     * @return object
     */
    public function __call($method, $params)
    {
        if (preg_match('/^get([A-Z]{1}[a-z]*)Form/', $method, $matches)) {
            $serviceKey = strtolower($this->getModuleNamespace() . '_' . $matches[1] . $this->getClassNameWithoutNamespace() . '_form');
            return $this->getServiceLocator()->get($serviceKey);
        } else {
            throw new \BadFunctionCallException('Unkown method "' . $method . '" called');
        }
    }
    
    /**
     * Getter for the class name without it's namespace
     *
     * @return string
     */
    public function getClassNameWithoutNamespace()
    {
        if ($this->classNameWithoutNamespace === null) {
            $class = explode('\\', get_class($this));
            $this->classNameWithoutNamespace = end($class);
        }
        return $this->classNameWithoutNamespace;
    }
    
    /**
     * Getter for $mapper
     *
     * @return \DlcBase\Mapper\AbstractMapper $mapper
     */
    public function getMapper()
    {
        if (null === $this->mapper) {
            $class = explode('\\', get_class($this));
            $serviceKey = strtolower($this->getModuleNamespace() . '_' . end($class)) . '_mapper';
            $this->setMapper($this->getServiceLocator()->get($serviceKey));
        }
        return $this->mapper;
    }

	/**
     * Setter for $mapper
     *
     * @param  \DlcBase\Mapper\AbstractMapper $mapper
     * @return AbstractService
     */
    public function setMapper($mapper)
    {
        $this->mapper = $mapper;
        return $this;
    }

	/**
     * Getter for $moduleNamespace
     *
     * @return string $moduleNamespace
     */
    public function getModuleNamespace()
    {
        return $this->moduleNamespace;
    }
    
    /**
     * Setter for $moduleNamespace
     *
     * @param  string $moduleNamespace
     * @return AbstractService
     */
    public function setModuleNamespace($moduleNamespace)
    {
        $this->moduleNamespace = $moduleNamespace;
        return $this;
    }
    
    /**
     * Getter for $options
     *
     * @return \DlcBase\Options\ModuleOptions $options
     */
    public function getOptions()
    {
        if (null === $this->options) {
            $serviceKey = strtolower($this->getModuleNamespace()) . '_module_options';
            $this->setOptions($this->getServiceLocator()->get($serviceKey));
        }
        return $this->options;
    }
    
    /**
     * Setter for $options
     *
     * @param  \DlcBase\Options\ModuleOptions $options
     * @return AbstractActionController
     */
    public function setOptions($options)
    {
        $this->options = $options;
        return $this;
    }
    
    /**
     * Set service locator
     *
     * @param ServiceLocatorInterface $serviceLocator
     * @return AbstractService
     */
    public function setServiceLocator(ServiceLocatorInterface $serviceLocator)
    {
        $this->serviceLocator = $serviceLocator;
        return $this;
    }
    
    /**
     * Get service locator
     *
     * @return ServiceLocatorInterface
     */
    public function getServiceLocator()
    {
        return $this->serviceLocator;
    }
}