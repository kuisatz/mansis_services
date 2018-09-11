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
class SysCustomerSectorTypesFactory  implements \Zend\ServiceManager\FactoryInterface{
    
    public function createService(\Zend\ServiceManager\ServiceLocatorInterface $serviceLocator) {
        $sysCustomerSectorTypes = new \DAL\PDO\Oracle\SysCustomerSectorTypes() ;   
        $slimapp = $serviceLocator->get('slimapp') ;            
        $sysCustomerSectorTypes -> setSlimApp($slimapp); 
        return $sysCustomerSectorTypes; 
    } 
    
}