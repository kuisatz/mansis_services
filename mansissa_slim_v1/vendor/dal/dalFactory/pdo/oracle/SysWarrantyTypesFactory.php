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
class SysWarrantyTypesFactory  implements \Zend\ServiceManager\FactoryInterface{
    
    public function createService(\Zend\ServiceManager\ServiceLocatorInterface $serviceLocator) {
        $sysWarrantyTypes = new \DAL\PDO\Oracle\SysWarrantyTypes() ;   
        $slimapp = $serviceLocator->get('slimapp') ;            
        $sysWarrantyTypes -> setSlimApp($slimapp); 
        return $sysWarrantyTypes; 
    } 
    
} 