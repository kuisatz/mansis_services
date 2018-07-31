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
class SysSalesProvisionsFactory  implements \Zend\ServiceManager\FactoryInterface{
    
    public function createService(\Zend\ServiceManager\ServiceLocatorInterface $serviceLocator) {
        $sysSalesProvisions = new \DAL\PDO\Oracle\SysSalesProvisions() ;   
        $slimapp = $serviceLocator->get('slimapp') ;            
        $sysSalesProvisions -> setSlimApp($slimapp); 
        return $sysSalesProvisions; 
    } 
    
}