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
class SysBbContractTypesFactory  implements \Zend\ServiceManager\FactoryInterface{
    
    public function createService(\Zend\ServiceManager\ServiceLocatorInterface $serviceLocator) {
        $sysBbContractTypes = new \DAL\PDO\Oracle\SysBbContractTypes()   ;   
        $slimapp = $serviceLocator->get('slimapp') ;            
        $sysBbContractTypes -> setSlimApp($slimapp); 
        return $sysBbContractTypes; 
    } 
    
}