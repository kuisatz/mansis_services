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
class SysTopusedIntakesFactory  implements \Zend\ServiceManager\FactoryInterface{
    
    public function createService(\Zend\ServiceManager\ServiceLocatorInterface $serviceLocator) {
        $sysTopusedIntakes = new \DAL\PDO\Oracle\SysTopusedIntakes() ;   
        $slimapp = $serviceLocator->get('slimapp') ;            
        $sysTopusedIntakes -> setSlimApp($slimapp); 
        return $sysTopusedIntakes; 
    } 
    
}