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
 * created date : 26.07.2016
 */
class SysAclMenuTypesActionsFactory  implements \Zend\ServiceManager\FactoryInterface{
    
    public function createService(\Zend\ServiceManager\ServiceLocatorInterface $serviceLocator) {      
        $sysAclMenuTypesActions  = new \DAL\PDO\Oracle\SysAclMenuTypesActions()   ;         
        $slimapp = $serviceLocator->get('slimapp') ;            
        $sysAclMenuTypesActions -> setSlimApp($slimapp);         
        return $sysAclMenuTypesActions;
      
    }
    
    
}