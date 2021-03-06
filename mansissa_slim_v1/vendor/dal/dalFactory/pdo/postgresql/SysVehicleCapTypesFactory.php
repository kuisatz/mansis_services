<?php
/**
 *  Framework 
 *
 * @link       
 * @copyright Copyright (c) 2017
 * @license   
 */
namespace DAL\Factory\PDO\Postgresql;


/**
 * Class using Zend\ServiceManager\FactoryInterface
 * created to be used by DAL MAnager
 * @author Okan CIRAN
 */
class SysVehicleCapTypesFactory  implements \Zend\ServiceManager\FactoryInterface{
    
    public function createService(\Zend\ServiceManager\ServiceLocatorInterface $serviceLocator) {
        $sysVehicleCapTypes = new \DAL\PDO\Postresql\SysVehicleCapTypes() ;   
        $slimapp = $serviceLocator->get('slimapp') ;            
        $sysVehicleCapTypes -> setSlimApp($slimapp); 
        return $sysVehicleCapTypes; 
    } 
    
}