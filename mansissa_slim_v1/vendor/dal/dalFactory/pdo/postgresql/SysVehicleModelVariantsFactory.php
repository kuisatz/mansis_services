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
class SysVehicleModelVariantsFactory  implements \Zend\ServiceManager\FactoryInterface{
    
    public function createService(\Zend\ServiceManager\ServiceLocatorInterface $serviceLocator) {
        $sysVehicleModelVariants = new \DAL\PDO\Oracle\SysVehicleModelVariants() ;   
        $slimapp = $serviceLocator->get('slimapp') ;            
        $sysVehicleModelVariants -> setSlimApp($slimapp); 
        return $sysVehicleModelVariants; 
    } 
    
}