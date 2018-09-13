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
 * created date : 27.07.2016
 */
class SysServicesGroupsFactory  implements \Zend\ServiceManager\FactoryInterface{
    
    public function createService(\Zend\ServiceManager\ServiceLocatorInterface $serviceLocator) {
        $sysServicesGroups  = new \DAL\PDO\Postresql\SysServicesGroups()   ;         
        $slimapp = $serviceLocator->get('slimapp') ;            
        $sysServicesGroups -> setSlimApp($slimapp);
        return $sysServicesGroups;      
    }    
    
}