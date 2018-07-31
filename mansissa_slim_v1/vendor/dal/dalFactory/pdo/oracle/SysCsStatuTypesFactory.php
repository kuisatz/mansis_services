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
class SysCsStatuTypesFactory  implements \Zend\ServiceManager\FactoryInterface{
    
    public function createService(\Zend\ServiceManager\ServiceLocatorInterface $serviceLocator) {
        $sysCsStatuTypes = new \DAL\PDO\Oracle\SysCsStatuTypes()   ;   
        $slimapp = $serviceLocator->get('slimapp') ;            
        $sysCsStatuTypes -> setSlimApp($slimapp); 
        return $sysCsStatuTypes; 
    } 
    
}