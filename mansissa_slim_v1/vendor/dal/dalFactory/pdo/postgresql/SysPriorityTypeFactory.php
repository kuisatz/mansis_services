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
class SysPriorityTypeFactory  implements \Zend\ServiceManager\FactoryInterface{
    
    public function createService(\Zend\ServiceManager\ServiceLocatorInterface $serviceLocator) {
        $sysPriorityType = new \DAL\PDO\postresql\SysPriorityType() ;   
        $slimapp = $serviceLocator->get('slimapp') ;            
        $sysPriorityType -> setSlimApp($slimapp); 
        return $sysPriorityType; 
    } 
    
}