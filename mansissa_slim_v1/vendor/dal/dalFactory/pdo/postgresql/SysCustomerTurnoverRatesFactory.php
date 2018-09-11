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
class SysCustomerTurnoverRatesFactory  implements \Zend\ServiceManager\FactoryInterface{
    
    public function createService(\Zend\ServiceManager\ServiceLocatorInterface $serviceLocator) {
        $sysCustomerTurnoverRates = new \DAL\PDO\Oracle\SysCustomerTurnoverRates() ;   
        $slimapp = $serviceLocator->get('slimapp') ;            
        $sysCustomerTurnoverRates -> setSlimApp($slimapp); 
        return $sysCustomerTurnoverRates; 
    } 
    
}