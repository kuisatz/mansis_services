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
class SysProbabilitiesFactory  implements \Zend\ServiceManager\FactoryInterface{
    
    public function createService(\Zend\ServiceManager\ServiceLocatorInterface $serviceLocator) {
        $sysProbabilities = new \DAL\PDO\Postresql\SysProbabilities() ;   
        $slimapp = $serviceLocator->get('slimapp') ;            
        $sysProbabilities -> setSlimApp($slimapp); 
        return $sysProbabilities; 
    } 
    
}