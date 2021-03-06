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
class SysVehicleGroupsFactory  implements \Zend\ServiceManager\FactoryInterface{
    
    public function createService(\Zend\ServiceManager\ServiceLocatorInterface $serviceLocator) {
        $sysVehicleGroups = new \DAL\PDO\Postresql\SysVehicleGroups() ;   
        $slimapp = $serviceLocator->get('slimapp') ;            
        $sysVehicleGroups -> setSlimApp($slimapp); 
        return $sysVehicleGroups; 
    } 
    
}