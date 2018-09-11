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
class SysSisQuotasFactory  implements \Zend\ServiceManager\FactoryInterface{
    
    public function createService(\Zend\ServiceManager\ServiceLocatorInterface $serviceLocator) {
        $sysSisQuotas = new \DAL\PDO\Oracle\SysSisQuotas() ;   
        $slimapp = $serviceLocator->get('slimapp') ;            
        $sysSisQuotas -> setSlimApp($slimapp); 
        return $sysSisQuotas; 
    } 
    
}