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
class SysAccessoryOptionsFactory  implements \Zend\ServiceManager\FactoryInterface{
    
    public function createService(\Zend\ServiceManager\ServiceLocatorInterface $serviceLocator) {
        $sysAccessoryOptions = new \DAL\PDO\Oracle\SysAccessoryOptions()   ;   
        $slimapp = $serviceLocator->get('slimapp') ;            
        $sysAccessoryOptions -> setSlimApp($slimapp); 
        return $sysAccessoryOptions; 
    } 
    
}