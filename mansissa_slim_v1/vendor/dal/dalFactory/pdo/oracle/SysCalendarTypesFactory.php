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
class SysCalendarTypesFactory  implements \Zend\ServiceManager\FactoryInterface{
    
    public function createService(\Zend\ServiceManager\ServiceLocatorInterface $serviceLocator) {
        $sysCalendarTypes= new \DAL\PDO\Oracle\SysCalendarTypes()   ;   
        $slimapp = $serviceLocator->get('slimapp') ;            
        $sysCalendarTypes -> setSlimApp($slimapp); 
        return $sysCalendarTypes; 
    } 
    
}