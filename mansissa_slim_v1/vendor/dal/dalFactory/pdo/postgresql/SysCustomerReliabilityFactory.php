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
class SysCustomerReliabilityFactory  implements \Zend\ServiceManager\FactoryInterface{
    
    public function createService(\Zend\ServiceManager\ServiceLocatorInterface $serviceLocator) {
        $sysCustomerReliability = new \DAL\PDO\postresql\SysCustomerReliability() ;   
        $slimapp = $serviceLocator->get('slimapp') ;            
        $sysCustomerReliability -> setSlimApp($slimapp); 
        return $sysCustomerReliability; 
    } 
    
}