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
class SysVehicleGroupTypesFactory  implements \Zend\ServiceManager\FactoryInterface{
    
    public function createService(\Zend\ServiceManager\ServiceLocatorInterface $serviceLocator) {
        $sysVehicleGroupTypes = new \DAL\PDO\Oracle\SysVehicleGroupTypes() ;   
        $slimapp = $serviceLocator->get('slimapp') ;            
        $sysVehicleGroupTypes -> setSlimApp($slimapp); 
        return $sysVehicleGroupTypes; 
    } 
    
}