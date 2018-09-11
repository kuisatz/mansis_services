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
class SysVehicleAuditSheetDefFactory  implements \Zend\ServiceManager\FactoryInterface{
    
    public function createService(\Zend\ServiceManager\ServiceLocatorInterface $serviceLocator) {
        $serviceLocatorysVehicleAuditSheetDef = new \DAL\PDO\Oracle\SysVehicleAuditSheetDef() ;   
        $slimapp = $serviceLocator->get('slimapp') ;            
        $serviceLocatorysVehicleAuditSheetDef -> setSlimApp($slimapp); 
        return $serviceLocatorysVehicleAuditSheetDef; 
    } 
    
}