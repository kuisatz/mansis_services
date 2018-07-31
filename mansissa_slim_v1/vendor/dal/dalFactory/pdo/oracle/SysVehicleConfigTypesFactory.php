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
class SysVehicleConfigTypesFactory  implements \Zend\ServiceManager\FactoryInterface{
    
    public function createService(\Zend\ServiceManager\ServiceLocatorInterface $serviceLocator) {
        $sysVehicleConfigTypes = new \DAL\PDO\Oracle\SysVehicleConfigTypes() ;   
        $slimapp = $serviceLocator->get('slimapp') ;            
        $sysVehicleConfigTypes -> setSlimApp($slimapp); 
        return $sysVehicleConfigTypes; 
    } 
    
}