<?php
namespace DlcBase\Controller;

use DlcBase\Module\ModuleNamespaceAwareInterface;
use DlcBase\Options\ModuleOptionsAwareInterface;
use Zend\Mvc\Controller\AbstractActionController AS ZendAbstractActionController;

abstract class AbstractActionController extends ZendAbstractActionController implements ModuleNamespaceAwareInterface, ModuleOptionsAwareInterface
{
    /**
     * The module namespace (e.g. DlcBase)
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
}