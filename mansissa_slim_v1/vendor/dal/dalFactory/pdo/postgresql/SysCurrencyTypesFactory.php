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
class SysCurrencyTypesFactory  implements \Zend\ServiceManager\FactoryInterface{
    
    public function createService(\Zend\ServiceManager\ServiceLocatorInterface $serviceLocator) {
        $sysCurrencyTypes = new \DAL\PDO\Oracle\SysCurrencyTypes() ;   
        $slimapp = $serviceLocator->get('slimapp') ;            
        $sysCurrencyTypes -> setSlimApp($slimapp); 
        return $sysCurrencyTypes; 
    } 
    
}