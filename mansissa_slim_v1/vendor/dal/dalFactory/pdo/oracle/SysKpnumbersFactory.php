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
class SysKpnumbersFactory  implements \Zend\ServiceManager\FactoryInterface{
    
    public function createService(\Zend\ServiceManager\ServiceLocatorInterface $serviceLocator) {
        $sysKpnumbers = new \DAL\PDO\Oracle\SysKpnumbers() ;   
        $slimapp = $serviceLocator->get('slimapp') ;            
        $sysKpnumbers -> setSlimApp($slimapp); 
        return $sysKpnumbers; 
    } 
    
}