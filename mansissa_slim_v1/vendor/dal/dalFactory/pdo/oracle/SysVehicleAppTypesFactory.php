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
class SysVehicleAppTypesFactory  implements \Zend\ServiceManager\FactoryInterface{
    
    public function createService(\Zend\ServiceManager\ServiceLocatorInterface $serviceLocator) {
        $sysVehicleAppTypes = new \DAL\PDO\Oracle\SysVehicleAppTypes() ;   
        $slimapp = $serviceLocator->get('slimapp') ;            
        $sysVehicleAppTypes -> setSlimApp($slimapp); 
        return $sysVehicleAppTypes; 
    } 
    
}