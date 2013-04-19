<?php
namespace DlcBase\Form;

use DlcBase\Module\ModuleNamespaceAwareInterface;
use DlcBase\Options\ModuleOptionsAwareInterface;
use Zend\Form\Fieldset;
use Zend\InputFilter\InputFilterProviderInterface;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * Abstract fieldset which provides the input filter definition
 */
class AbstractInputFilterProvidingFieldset extends Fieldset 
    implements InputFilterProviderInterface,
               ModuleNamespaceAwareInterface, 
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
    
        $this->init();
    
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
    
    public function __construct()
    {
        parent::__construct('abstract fieldset');
    }
    
    /**
     * (non-PHPdoc)
     * @see \Zend\Form\Element::init()
     */
    public function init()
    {
        parent::init();
    }

    /**
     * @return array
     */
    public function getInputFilterSpecification()
    {
        return array();
    }
}