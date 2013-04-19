<?php
namespace DlcBase\Module;

interface ModuleNamespaceAwareInterface
{
    /**
     * Getter for $moduleNamespace
     *
     * @return string $moduleNamespace
     */
    public function getModuleNamespace();
    
    /**
     * Setter for $moduleNamespace
     *
     * @param  string $moduleNamespace
     * @return AbstractService
     */
    public function setModuleNamespace($moduleNamespace);
}