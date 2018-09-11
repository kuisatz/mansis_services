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
class SysTerrainsFactory  implements \Zend\ServiceManager\FactoryInterface{
    
    public function createService(\Zend\ServiceManager\ServiceLocatorInterface $serviceLocator) {
        $sysTerrains = new \DAL\PDO\Oracle\SysTerrains() ;   
        $slimapp = $serviceLocator->get('slimapp') ;            
        $sysTerrains -> setSlimApp($slimapp); 
        return $sysTerrains; 
    } 
    
}