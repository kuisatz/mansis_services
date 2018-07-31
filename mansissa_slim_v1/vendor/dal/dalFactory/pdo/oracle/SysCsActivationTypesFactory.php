<?php
/**
 *  Framework 
 *
 * @link       
 * @copyright Copyright (c) 2017
 * @license   
 */
namespace DAL\Factory\PDO\Oracle;


/**
 * Class using Zend\ServiceManager\FactoryInterface
 * created to be used by DAL MAnager
 * @author Okan CIRAN
 */
class SysCsActivationTypesFactory  implements \Zend\ServiceManager\FactoryInterface{
    
    public function createService(\Zend\ServiceManager\ServiceLocatorInterface $serviceLocator) {
        $sysCsActivationTypes = new \DAL\PDO\Oracle\SysCsActivationTypes()   ;   
        $slimapp = $serviceLocator->get('slimapp') ;            
        $sysCsActivationTypes -> setSlimApp($slimapp); 
        return $sysCsActivationTypes; 
    } 
    
}