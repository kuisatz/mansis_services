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
class SysSisHierarchyFactory  implements \Zend\ServiceManager\FactoryInterface{
    
    public function createService(\Zend\ServiceManager\ServiceLocatorInterface $serviceLocator) {
        $sysSisHierarchy = new \DAL\PDO\Oracle\SysSisHierarchy() ;   
        $slimapp = $serviceLocator->get('slimapp') ;            
        $sysSisHierarchy -> setSlimApp($slimapp); 
        return $sysSisHierarchy; 
    } 
    
}