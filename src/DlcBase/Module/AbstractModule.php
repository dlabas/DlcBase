<?php
namespace DlcBase\Module;

use Zend\ModuleManager\Feature\AutoloaderProviderInterface;
use Zend\ModuleManager\Feature\ConfigProviderInterface;
use Zend\ModuleManager\Feature\ServiceProviderInterface;

/**
 * Abstract module class
 */
abstract class AbstractModule implements
    AutoloaderProviderInterface,
    ConfigProviderInterface,
    ModuleNamespaceAwareInterface,
    ServiceProviderInterface
{
    /**
     * Dirname of module class file
     * 
     * @var string
     */
    protected $dir;
    
    /**
     * The module namespace
     *
     * @var string
     */
    protected $moduleNamespace;
    
    /**
     * Module namespace
     * 
     * @var string
     */
    protected $namespace;
    
    /**
     * Retuns the module directory
     * 
     * @return string
     */
    public function getDir()
    {
        if ($this->dir === null) {
            $rc = new \ReflectionClass($this);
            $this->dir = dirname($rc->getFileName());
        }
        return $this->dir;
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
     * @return AbstractService
     */
    public function setModuleNamespace($moduleNamespace)
    {
        $this->moduleNamespace = $moduleNamespace;
        return $this;
    }
    
    /**
     * Returns the class namespace
     * 
     * @deprecated use getModuleNamespace() instead
     * 
     * @return string
     */
    public function getNamespace()
    {
        return $this->getModuleNamespace();
    }
    
    /**
     * (non-PHPdoc)
     * @see \Zend\ModuleManager\Feature\ConfigProviderInterface::getConfig()
     */
    public function getConfig()
    {
        $dir = $this->getDir();
    
        if (file_exists($dir . '/../../config/module.config.php')) {
            return include $dir . '/../../config/module.config.php';
        } elseif (file_exists($dir . '/config/module.config.php')) {
            return include $dir . '/config/module.config.php';
        } else {
            throw new \Exception('No module config found');
        }
    }
    
    /**
     * (non-PHPdoc)
     * @see \Zend\ModuleManager\Feature\AutoloaderProviderInterface::getAutoloaderConfig()
     */
    public function getAutoloaderConfig()
    {
        $dir = $this->getDir();
        $namespace = $this->getModuleNamespace();
    
        if (file_exists($dir . '/../../src')) {
            $dir = $dir . '/../../src';
        } elseif (file_exists($dir . '/../src')) {
            $dir = $dir . '/../src';
        } elseif (file_exists($dir . '/src')) {
            $dir = $dir . '/src';
        } else {
            throw new \Exception('No src directory found');
        }
    
        return array(
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
                    $namespace => $dir . '/' . $namespace,
                ),
            ),
        );
    }
    
    /**
     * (non-PHPdoc)
     * @see \Zend\ModuleManager\Feature\ServiceProviderInterface::getServiceConfig()
     */
    public function getServiceConfig()
    {
        return array();
    }
}