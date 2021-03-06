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
class SysDemoQuotasFactory  implements \Zend\ServiceManager\FactoryInterface{
    
    public function createService(\Zend\ServiceManager\ServiceLocatorInterface $serviceLocator) {
        $sysDemoQuotas = new \DAL\PDO\Oracle\SysDemoQuotas() ;   
        $slimapp = $serviceLocator->get('slimapp') ;            
        $sysDemoQuotas -> setSlimApp($slimapp); 
        return $sysDemoQuotas; 
    } 
    
}