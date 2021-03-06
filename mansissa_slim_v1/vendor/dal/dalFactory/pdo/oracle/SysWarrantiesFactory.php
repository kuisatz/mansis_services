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
class SysWarrantiesFactory  implements \Zend\ServiceManager\FactoryInterface{
    
    public function createService(\Zend\ServiceManager\ServiceLocatorInterface $serviceLocator) {
        $sysWarranties = new \DAL\PDO\Oracle\SysWarranties() ;   
        $slimapp = $serviceLocator->get('slimapp') ;            
        $sysWarranties -> setSlimApp($slimapp); 
        return $sysWarranties; 
    } 
    
}