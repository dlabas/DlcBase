<?php
namespace DlcBase;

use Zend\ModuleManager\Feature\AutoloaderProviderInterface;
use Zend\ModuleManager\Feature\ConfigProviderInterface;
use Zend\ModuleManager\Feature\ServiceProviderInterface;

/**
 * The module class
 */
class Module implements
    AutoloaderProviderInterface,
    ConfigProviderInterface,
    ServiceProviderInterface
{
    /**
     * (non-PHPdoc)
     * @see \Zend\ModuleManager\Feature\ConfigProviderInterface::getConfig()
     */
    public function getConfig()
    {
        return include __DIR__ . '/../../config/module.config.php';
    }
    
    /**
     * (non-PHPdoc)
     * @see \Zend\ModuleManager\Feature\AutoloaderProviderInterface::getAutoloaderConfig()
     */
    public function getAutoloaderConfig()
    {
        return array(
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
                    __NAMESPACE__ => __DIR__ . '/../../src/' . __NAMESPACE__,
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
        return array(
            'initializers' => array(
                function ($instance, $sm) {
                    if ($instance instanceof \DlcBase\Module\ModuleNamespaceAwareInterface) {
                        $class           = get_class($instance);
                        $moduleNamespace = substr($class, 0, strpos($class, '\\'));
                        $instance->setModuleNamespace($moduleNamespace);
                    }
                }
            ),
        );
    }
}