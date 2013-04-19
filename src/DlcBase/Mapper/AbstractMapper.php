<?php
namespace DlcBase\Mapper;

use DlcBase\Module\ModuleNamespaceAwareInterface;
use DlcBase\Options\ModuleOptionsAwareInterface;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * Abstract mapper class
 */
class AbstractMapper 
    implements ModuleNamespaceAwareInterface, 
               ModuleOptionsAwareInterface, 
               ServiceLocatorAwareInterface
{
    /**
     * The module namespace
     *
     * @var string
     */
    protected $moduleNamespace;
    
    /**
     * The module options
     *
     * @var \DlcBase\Options\ModuleOptionsInterface
     */
    protected $options;
    
    /**
     * The service locator
     *
     * @var ServiceLocatorInterface
     */
    protected $serviceLocator;
    
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
     * @return \DlcBase\Options\ModuleOptionsInterface $options
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
     * @param  \DlcBase\Options\ModuleOptionsInterface $options
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