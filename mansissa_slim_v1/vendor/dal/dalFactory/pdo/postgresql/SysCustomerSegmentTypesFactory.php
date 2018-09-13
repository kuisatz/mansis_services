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
class SysCustomerSegmentTypesFactory  implements \Zend\ServiceManager\FactoryInterface{
    
    public function createService(\Zend\ServiceManager\ServiceLocatorInterface $serviceLocator) {
        $sysCustomerSegmentTypes = new \DAL\PDO\Postresql\SysCustomerSegmentTypes() ;   
        $slimapp = $serviceLocator->get('slimapp') ;            
        $sysCustomerSegmentTypes -> setSlimApp($slimapp); 
        return $sysCustomerSegmentTypes; 
    } 
    
}