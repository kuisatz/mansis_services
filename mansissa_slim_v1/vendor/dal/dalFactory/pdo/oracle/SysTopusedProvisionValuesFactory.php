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
class SysTopusedProvisionValuesFactory  implements \Zend\ServiceManager\FactoryInterface{
    
    public function createService(\Zend\ServiceManager\ServiceLocatorInterface $serviceLocator) {
        $sysTopusedProvisionValues = new \DAL\PDO\Oracle\SysTopusedProvisionValues() ;   
        $slimapp = $serviceLocator->get('slimapp') ;            
        $sysTopusedProvisionValues -> setSlimApp($slimapp); 
        return $sysTopusedProvisionValues; 
    } 
    
}