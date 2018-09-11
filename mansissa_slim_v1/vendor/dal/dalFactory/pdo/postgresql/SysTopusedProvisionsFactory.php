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
class SysTopusedProvisionsFactory  implements \Zend\ServiceManager\FactoryInterface{
    
    public function createService(\Zend\ServiceManager\ServiceLocatorInterface $serviceLocator) {
        $sysTopusedProvisions = new \DAL\PDO\Oracle\SysTopusedProvisions() ;   
        $slimapp = $serviceLocator->get('slimapp') ;            
        $sysTopusedProvisions -> setSlimApp($slimapp); 
        return $sysTopusedProvisions; 
    } 
    
}