<?php
namespace DlcBase\Service;

use DlcBase\Module\ModuleNamespaceAwareInterface;
use DlcBase\Options\ModuleOptionsAwareInterface;
use Zend\Console\ColorInterface as Color;
use ZFTool\Service\AbstractModuleSetupService;

/**
 * Abstract setup service class
 */
abstract class AbstractSetupService extends AbstractModuleSetupService
    implements ModuleNamespaceAwareInterface,
               ModuleOptionsAwareInterface
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
}