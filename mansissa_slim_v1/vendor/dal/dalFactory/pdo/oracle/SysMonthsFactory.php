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
class SysMonthsFactory  implements \Zend\ServiceManager\FactoryInterface{
    
    public function createService(\Zend\ServiceManager\ServiceLocatorInterface $serviceLocator) {
        $sysMonths = new \DAL\PDO\Oracle\SysMonths() ;   
        $slimapp = $serviceLocator->get('slimapp') ;            
        $sysMonths -> setSlimApp($slimapp); 
        return $sysMonths; 
    } 
    
}