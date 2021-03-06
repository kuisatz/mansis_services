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
class SysCommissionPricerangeDefsFactory  implements \Zend\ServiceManager\FactoryInterface{
    
    public function createService(\Zend\ServiceManager\ServiceLocatorInterface $serviceLocator) {
        $sysCommissionPricerangeDefs = new \DAL\PDO\Oracle\SysCommissionPricerangeDefs()   ;   
        $slimapp = $serviceLocator->get('slimapp') ;            
        $sysCommissionPricerangeDefs -> setSlimApp($slimapp); 
        return $sysCommissionPricerangeDefs; 
    } 
    
}