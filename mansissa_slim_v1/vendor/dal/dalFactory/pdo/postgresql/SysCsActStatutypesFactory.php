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
class SysCsActStatutypesFactory  implements \Zend\ServiceManager\FactoryInterface{
    
    public function createService(\Zend\ServiceManager\ServiceLocatorInterface $serviceLocator) {
        $sysCsActStatutypes = new \DAL\PDO\postresql\SysCsActStatutypes()   ;   
        $slimapp = $serviceLocator->get('slimapp') ;            
        $sysCsActStatutypes -> setSlimApp($slimapp); 
        return $sysCsActStatutypes; 
    } 
    
}