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
class SysVillageFactory  implements \Zend\ServiceManager\FactoryInterface{
    
    public function createService(\Zend\ServiceManager\ServiceLocatorInterface $serviceLocator) {
        $sysVillage = new \DAL\PDO\Oracle\SysVillage()   ;   
        $slimapp = $serviceLocator->get('slimapp') ;            
        $sysVillage -> setSlimApp($slimapp); 
        return $sysVillage; 
    } 
    
}