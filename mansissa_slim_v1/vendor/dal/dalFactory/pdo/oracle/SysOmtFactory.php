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
class SysOmtFactory  implements \Zend\ServiceManager\FactoryInterface{
    
    public function createService(\Zend\ServiceManager\ServiceLocatorInterface $serviceLocator) {
        $sysOmt = new \DAL\PDO\Oracle\SysOmt() ;   
        $slimapp = $serviceLocator->get('slimapp') ;            
        $sysOmt -> setSlimApp($slimapp); 
        return $sysOmt; 
    } 
    
}