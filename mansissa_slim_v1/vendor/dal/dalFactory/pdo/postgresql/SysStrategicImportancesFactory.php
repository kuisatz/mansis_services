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
class SysStrategicImportancesFactory  implements \Zend\ServiceManager\FactoryInterface{
    
    public function createService(\Zend\ServiceManager\ServiceLocatorInterface $serviceLocator) {
        $sysStrategicImportances = new \DAL\PDO\Postresql\SysStrategicImportances() ;   
        $slimapp = $serviceLocator->get('slimapp') ;            
        $sysStrategicImportances -> setSlimApp($slimapp); 
        return $sysStrategicImportances; 
    } 
    
}