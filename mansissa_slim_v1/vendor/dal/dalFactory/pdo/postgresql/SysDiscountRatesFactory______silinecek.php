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
class SysDiscountRatesFactory  implements \Zend\ServiceManager\FactoryInterface{
    
    public function createService(\Zend\ServiceManager\ServiceLocatorInterface $serviceLocator) {
        $sysDiscountRates = new \DAL\PDO\Oracle\SysDiscountRates() ;   
        $slimapp = $serviceLocator->get('slimapp') ;            
        $sysDiscountRates -> setSlimApp($slimapp); 
        return $sysDiscountRates; 
    } 
    
}