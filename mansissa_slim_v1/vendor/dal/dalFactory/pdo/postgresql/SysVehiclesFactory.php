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
class SysVehiclesFactory  implements \Zend\ServiceManager\FactoryInterface{
    
    public function createService(\Zend\ServiceManager\ServiceLocatorInterface $serviceLocator) {
        $sysVehicles = new \DAL\PDO\Postresql\SysVehicles() ;   
        $slimapp = $serviceLocator->get('slimapp') ;            
        $sysVehicles -> setSlimApp($slimapp); 
        return $sysVehicles; 
    } 
    
}