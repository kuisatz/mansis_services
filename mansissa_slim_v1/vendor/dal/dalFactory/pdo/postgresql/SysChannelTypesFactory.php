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
class SysChannelTypesFactory  implements \Zend\ServiceManager\FactoryInterface{
    
    public function createService(\Zend\ServiceManager\ServiceLocatorInterface $serviceLocator) {
        $sysChannelTypes = new \DAL\PDO\Oracle\SysChannelTypes()   ;   
        $slimapp = $serviceLocator->get('slimapp') ;            
        $sysChannelTypes -> setSlimApp($slimapp); 
        return $sysChannelTypes; 
    } 
    
}