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
 * created date : 15.07.2016
 */
class SysAclRrpFactory  implements \Zend\ServiceManager\FactoryInterface{
    
    public function createService(\Zend\ServiceManager\ServiceLocatorInterface $serviceLocator) {
        $sysAclRrp  = new \DAL\PDO\Oracle\SysAclRrp();  
        $slimapp = $serviceLocator->get('slimapp');            
        $sysAclRrp -> setSlimApp($slimapp);
        return $sysAclRrp;
      
    }
    
    
}