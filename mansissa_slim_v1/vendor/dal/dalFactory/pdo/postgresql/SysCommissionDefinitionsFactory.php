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
class SysCommissionDefinitionsFactory  implements \Zend\ServiceManager\FactoryInterface{
    
    public function createService(\Zend\ServiceManager\ServiceLocatorInterface $serviceLocator) {
        $sysCommissionDefinitions = new \DAL\PDO\Oracle\SysCommissionDefinitions()   ;   
        $slimapp = $serviceLocator->get('slimapp') ;            
        $sysCommissionDefinitions -> setSlimApp($slimapp); 
        return $sysCommissionDefinitions; 
    } 
    
}