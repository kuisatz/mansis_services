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
class SysMileagesFactory  implements \Zend\ServiceManager\FactoryInterface{
    
    public function createService(\Zend\ServiceManager\ServiceLocatorInterface $serviceLocator) {
        $sysMileages = new \DAL\PDO\Oracle\SysMileages() ;   
        $slimapp = $serviceLocator->get('slimapp') ;            
        $sysMileages -> setSlimApp($slimapp); 
        return $sysMileages; 
    } 
    
}