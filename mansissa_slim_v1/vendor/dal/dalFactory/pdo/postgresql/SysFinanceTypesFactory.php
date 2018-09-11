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
class SysFinanceTypesFactory  implements \Zend\ServiceManager\FactoryInterface{
    
    public function createService(\Zend\ServiceManager\ServiceLocatorInterface $serviceLocator) {
        $sysFinanceTypes = new \DAL\PDO\Oracle\SysFinanceTypes() ;   
        $slimapp = $serviceLocator->get('slimapp') ;            
        $sysFinanceTypes -> setSlimApp($slimapp); 
        return $sysFinanceTypes; 
    } 
    
}