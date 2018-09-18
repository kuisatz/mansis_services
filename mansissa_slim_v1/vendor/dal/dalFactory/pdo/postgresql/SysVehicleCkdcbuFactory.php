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
class SysVehicleCkdcbuFactory  implements \Zend\ServiceManager\FactoryInterface{
    
    public function createService(\Zend\ServiceManager\ServiceLocatorInterface $serviceLocator) {
        $sysVehicleCkdcbu = new \DAL\PDO\Postresql\SysVehicleCkdcbu() ;   
        $slimapp = $serviceLocator->get('slimapp') ;            
        $sysVehicleCkdcbu -> setSlimApp($slimapp); 
        return $sysVehicleCkdcbu; 
    } 
    
}