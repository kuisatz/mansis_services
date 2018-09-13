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
 * created date : 13.12.2015
 */
class SysAclModulesFactory  implements \Zend\ServiceManager\FactoryInterface{
    
    public function createService(\Zend\ServiceManager\ServiceLocatorInterface $serviceLocator) {      
        $sysAclModules  = new \DAL\PDO\Postresql\SysAclModules()   ;         
        $slimapp = $serviceLocator->get('slimapp') ;            
        $sysAclModules -> setSlimApp($slimapp);         
        return $sysAclModules;
      
    }
    
    
}