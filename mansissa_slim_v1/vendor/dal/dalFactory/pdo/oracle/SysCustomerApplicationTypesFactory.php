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
class SysCustomerApplicationTypesFactory  implements \Zend\ServiceManager\FactoryInterface{
    
    public function createService(\Zend\ServiceManager\ServiceLocatorInterface $serviceLocator) {
        $sysCustomerApplicationTypes = new \DAL\PDO\Oracle\SysCustomerApplicationTypes() ;   
        $slimapp = $serviceLocator->get('slimapp') ;            
        $sysCustomerApplicationTypes -> setSlimApp($slimapp); 
        return $sysCustomerApplicationTypes; 
    } 
    
}