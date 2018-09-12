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
class SysSalesLimitsDeffFactory  implements \Zend\ServiceManager\FactoryInterface{
    
    public function createService(\Zend\ServiceManager\ServiceLocatorInterface $serviceLocator) {
        $sysSalesLimitsDeff = new \DAL\PDO\postresql\SysSalesLimitsDeff() ;   
        $slimapp = $serviceLocator->get('slimapp') ;            
        $sysSalesLimitsDeff -> setSlimApp($slimapp); 
        return $sysSalesLimitsDeff; 
    } 
    
}