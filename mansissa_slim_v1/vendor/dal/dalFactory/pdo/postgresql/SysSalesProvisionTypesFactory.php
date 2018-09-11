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
class SysSalesProvisionTypesFactory  implements \Zend\ServiceManager\FactoryInterface{
    
    public function createService(\Zend\ServiceManager\ServiceLocatorInterface $serviceLocator) {
        $sysSalesProvisionTypes = new \DAL\PDO\Oracle\SysSalesProvisionTypes() ;   
        $slimapp = $serviceLocator->get('slimapp') ;            
        $sysSalesProvisionTypes -> setSlimApp($slimapp); 
        return $sysSalesProvisionTypes; 
    } 
    
}