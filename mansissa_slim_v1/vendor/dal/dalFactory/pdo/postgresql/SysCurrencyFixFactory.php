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
class SysCurrencyFixFactory  implements \Zend\ServiceManager\FactoryInterface{
    
    public function createService(\Zend\ServiceManager\ServiceLocatorInterface $serviceLocator) {
        $sysCurrencyFix = new \DAL\PDO\Postresql\SysCurrencyFix()   ;   
        $slimapp = $serviceLocator->get('slimapp') ;            
        $sysCurrencyFix -> setSlimApp($slimapp); 
        return $sysCurrencyFix; 
    } 
    
}