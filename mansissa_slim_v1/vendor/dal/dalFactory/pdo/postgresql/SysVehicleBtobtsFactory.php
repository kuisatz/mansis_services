<?php
/**
 *  Framework 
 *
 * @link       
 * @copyright Copyright (c) 2018
 * @license   
 */
namespace DAL\Factory\PDO\Postgresql;


/**
 * Class using Zend\ServiceManager\FactoryInterface
 * created to be used by DAL MAnager
 * @author Okan CIRAN
 */
class SysVehicleBtobtsFactory  implements \Zend\ServiceManager\FactoryInterface{
    
    public function createService(\Zend\ServiceManager\ServiceLocatorInterface $serviceLocator) {
        $sysVehicleBtobts = new \DAL\PDO\Oracle\SysVehicleBtobts() ;   
        $slimapp = $serviceLocator->get('slimapp') ;            
        $sysVehicleBtobts -> setSlimApp($slimapp); 
        return $sysVehicleBtobts; 
    } 
    
}