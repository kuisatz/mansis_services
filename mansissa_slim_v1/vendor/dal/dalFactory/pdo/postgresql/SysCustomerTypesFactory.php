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
class SysCustomerTypesFactory  implements \Zend\ServiceManager\FactoryInterface{
    
    public function createService(\Zend\ServiceManager\ServiceLocatorInterface $serviceLocator) {
        $sysCustomerTypes= new \DAL\PDO\Postresql\SysCustomerTypes()   ;   
        $slimapp = $serviceLocator->get('slimapp') ;            
        $sysCustomerTypes -> setSlimApp($slimapp); 
        return $sysCustomerTypes; 
    } 
    
}