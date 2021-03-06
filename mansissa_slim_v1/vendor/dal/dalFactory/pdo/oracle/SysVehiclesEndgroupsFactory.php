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
class SysVehiclesEndgroupsFactory  implements \Zend\ServiceManager\FactoryInterface{
    
    public function createService(\Zend\ServiceManager\ServiceLocatorInterface $serviceLocator) {
        $sysVehiclesEndgroups = new \DAL\PDO\Oracle\SysVehiclesEndgroups()   ;   
        $slimapp = $serviceLocator->get('slimapp') ;            
        $sysVehiclesEndgroups -> setSlimApp($slimapp); 
        return $sysVehiclesEndgroups; 
    } 
    
}