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
class SysSalesLimitsRolesFactory  implements \Zend\ServiceManager\FactoryInterface{
    
    public function createService(\Zend\ServiceManager\ServiceLocatorInterface $serviceLocator) {
        $sysSalesLimitsRoles = new \DAL\PDO\Postresql\SysSalesLimitsRoles() ;   
        $slimapp = $serviceLocator->get('slimapp') ;            
        $sysSalesLimitsRoles -> setSlimApp($slimapp); 
        return $sysSalesLimitsRoles; 
    } 
    
}