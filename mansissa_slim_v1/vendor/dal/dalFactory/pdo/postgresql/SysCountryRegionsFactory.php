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
class SysCountryRegionsFactory  implements \Zend\ServiceManager\FactoryInterface{
    
    public function createService(\Zend\ServiceManager\ServiceLocatorInterface $serviceLocator) {
        $sysCountryRegions = new \DAL\PDO\Postresql\SysCountryRegions()   ;   
        $slimapp = $serviceLocator->get('slimapp') ;            
        $sysCountryRegions -> setSlimApp($slimapp); 
        return $sysCountryRegions; 
    } 
    
}