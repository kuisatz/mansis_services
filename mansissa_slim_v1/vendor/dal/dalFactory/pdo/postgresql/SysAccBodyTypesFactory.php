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
class SysAccBodyTypesFactory  implements \Zend\ServiceManager\FactoryInterface{
    
    public function createService(\Zend\ServiceManager\ServiceLocatorInterface $serviceLocator) {
        $sysAccBodyTypes = new \DAL\PDO\postresql\SysAccBodyTypes()   ;   
        $slimapp = $serviceLocator->get('slimapp') ;            
        $sysAccBodyTypes -> setSlimApp($slimapp); 
        return $sysAccBodyTypes; 
    } 
    
}