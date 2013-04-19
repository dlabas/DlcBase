<?php
namespace DlcBase\Options;

/**
 * The module options aware interface
 */
interface ModuleOptionsAwareInterface
{
    /**
     * Getter for $options
     *
     * @return \DlcBase\Options\ModuleOptionsInterface $options
     */
    public function getOptions();
    
    /**
     * Setter for $options
     *
     * @param  \DlcBase\Options\ModuleOptionsInterface $options
     * @return AbstractActionController
     */
    public function setOptions($options);
}