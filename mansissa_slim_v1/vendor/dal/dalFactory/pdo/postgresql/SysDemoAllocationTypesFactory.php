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
class SysDemoAllocationTypesFactory  implements \Zend\ServiceManager\FactoryInterface{
    
    public function createService(\Zend\ServiceManager\ServiceLocatorInterface $serviceLocator) {
        $sysDemoAllocationTypes = new \DAL\PDO\postresql\SysDemoAllocationTypes() ;   
        $slimapp = $serviceLocator->get('slimapp') ;            
        $sysDemoAllocationTypes -> setSlimApp($slimapp); 
        return $sysDemoAllocationTypes; 
    } 
    
}