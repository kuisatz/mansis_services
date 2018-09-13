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
class SysVatPolicyTypesFactory  implements \Zend\ServiceManager\FactoryInterface{
    
    public function createService(\Zend\ServiceManager\ServiceLocatorInterface $serviceLocator) {
        $sysVatPolicyTypes = new \DAL\PDO\Postresql\SysVatPolicyTypes() ;   
        $slimapp = $serviceLocator->get('slimapp') ;            
        $sysVatPolicyTypes -> setSlimApp($slimapp); 
        return $sysVatPolicyTypes; 
    } 
    
}