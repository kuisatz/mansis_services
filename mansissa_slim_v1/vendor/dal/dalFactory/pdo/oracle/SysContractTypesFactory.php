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
class SysContractTypesFactory  implements \Zend\ServiceManager\FactoryInterface{
    
    public function createService(\Zend\ServiceManager\ServiceLocatorInterface $serviceLocator) {
        $sysContractTypes = new \DAL\PDO\Oracle\SysContractTypes()   ;   
        $slimapp = $serviceLocator->get('slimapp') ;            
        $sysContractTypes -> setSlimApp($slimapp); 
        return $sysContractTypes; 
    } 
    
}