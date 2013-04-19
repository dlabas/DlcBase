<?php
namespace DlcBase\Controller;

use DlcBase\Module\ModuleNamespaceAwareInterface;
use DlcBase\Options\ModuleOptionsAwareInterface;
use DlcBase\Service\AbstractService;
use Zend\Mvc\Controller\AbstractActionController AS ZendAbstractActionController;

/**
 * Abstract action controller
 */
abstract class AbstractActionController extends ZendAbstractActionController 
    implements ModuleNamespaceAwareInterface, 
               ModuleOptionsAwareInterface
{
    /**
     * Controller class name without it's namespace
     * 
     * @var string
     */
    protected $classNameWithoutNamespace;
    
    /**
     * The module namespace (e.g. DlcBase)
     * 
     * @var string
     */
    protected $moduleNamespace;
    
    /**
     * Route identifier prefix
     * 
     * @var string
     */
    protected $routeIdentifierPrefix;
    
    /**
     * The module options
     * 
     * @var DlcBase\Options\ModuleOptions
     */
    protected $options;
    
    /**
     * Service class Instance
     * 
     * @var AbstractService
     */
    protected $service;
    
    /**
     * (non-PHPdoc)
     * @see \Zend\Mvc\Controller\AbstractController::__call()
     */
    public function __call($method, $params)
    {
        if (preg_match('/^get([A-Z]{1}[a-z]*)Form/', $method, $matches)) {
            return $this->getService()->$method();
        } elseif (preg_match('/^get([A-Z]{1}[a-z]*)ActionRoute$/', $method, $matches)) {
            //Return the action route identifier
            return $this->getRouteIdentifierPrefix() . '/' . strtolower($matches[1]);
        } elseif (preg_match('/^get([A-Z]{1}[a-z]*)ActionRedirectRoute$/', $method, $matches)) {
            //Return the route for redirection after the action was successful
            return $this->getRouteIdentifierPrefix();
        } else {
            return parent::__call($method, $params);
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
            $this->classNameWithoutNamespace = substr(end($class), 0, -10);
        }
        return $this->classNameWithoutNamespace;
    }
    
    /**
     * Getter for $moduleNamespace
     *
     * @return string $moduleNamespace
     */
    public function getModuleNamespace()
    {
        if (null === $this->moduleNamespace) {
            $class = get_class($this);
            $this->moduleNamespace = substr($class, 0, strpos($class, '\\'));
        }
        return $this->moduleNamespace;
    }
    
    /**
     * Setter for $moduleNamespace
     *
     * @param  string $moduleNamespace
     * @return AbstractActionController
     */
    public function setModuleNamespace($moduleNamespace)
    {
        $this->moduleNamespace = $moduleNamespace;
        return $this;
    }
    
    /**
     * Returns the prefix for the route identifier
     */
    public function getRouteIdentifierPrefix()
    {
        if ($this->routeIdentifierPrefix === null) {
            $this->routeIdentifierPrefix = strtolower($this->getModuleNamespace() . '/' . $this->getClassNameWithoutNamespace());
        }
        return $this->routeIdentifierPrefix;
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
     * Getter for $service
     *
     * @return \DlcBase\Service\AbstractService $service
     */
    public function getService()
    {
        if (null === $this->service) {
            $class = explode('\\', get_class($this));
            $class = substr(end($class), 0, -10);
            $serviceKey = strtolower($this->getModuleNamespace() . '_' . $class . '_service');
            $this->setService($this->getServiceLocator()->get($serviceKey));
        }
        return $this->service;
    }

	/**
     * Setter for $service
     *
     * @param  \DlcBase\Service\AbstractService $service
     * @return AbstractActionController
     */
    public function setService($service)
    {
        $this->service = $service;
        return $this;
    }

}