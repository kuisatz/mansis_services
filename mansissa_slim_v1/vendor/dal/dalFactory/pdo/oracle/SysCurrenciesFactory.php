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
class SysCurrenciesFactory  implements \Zend\ServiceManager\FactoryInterface{
    
    public function createService(\Zend\ServiceManager\ServiceLocatorInterface $serviceLocator) {
        $sysCurrencies = new \DAL\PDO\Oracle\SysCurrencies()   ;   
        $slimapp = $serviceLocator->get('slimapp') ;            
        $sysCurrencies -> setSlimApp($slimapp); 
        return $sysCurrencies; 
    } 
    
}