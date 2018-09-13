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
class SysSupplierFactory  implements \Zend\ServiceManager\FactoryInterface{
    
    public function createService(\Zend\ServiceManager\ServiceLocatorInterface $serviceLocator) {
        $sysSupplier = new \DAL\PDO\Postresql\SysSupplier() ;   
        $slimapp = $serviceLocator->get('slimapp') ;            
        $sysSupplier -> setSlimApp($slimapp); 
        return $sysSupplier; 
    } 
    
}