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
class SysCommissionExtrasFactory  implements \Zend\ServiceManager\FactoryInterface{
    
    public function createService(\Zend\ServiceManager\ServiceLocatorInterface $serviceLocator) {
        $sysCommissionExtras = new \DAL\PDO\Postresql\SysCommissionExtras()   ;   
        $slimapp = $serviceLocator->get('slimapp') ;            
        $sysCommissionExtras -> setSlimApp($slimapp); 
        return $sysCommissionExtras; 
    } 
    
}