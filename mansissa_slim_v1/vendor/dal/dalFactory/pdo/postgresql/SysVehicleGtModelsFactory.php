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
class SysVehicleGtModelsFactory  implements \Zend\ServiceManager\FactoryInterface{
    
    public function createService(\Zend\ServiceManager\ServiceLocatorInterface $serviceLocator) {
        $sysVehicleGtModels = new \DAL\PDO\Oracle\SysVehicleGtModels() ;   
        $slimapp = $serviceLocator->get('slimapp') ;            
        $sysVehicleGtModels -> setSlimApp($slimapp); 
        return $sysVehicleGtModels; 
    } 
    
}