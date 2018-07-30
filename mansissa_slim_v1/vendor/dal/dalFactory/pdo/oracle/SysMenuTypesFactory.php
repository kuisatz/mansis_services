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
 * created date : 20.07.2016
 */
class SysMenuTypesFactory  implements \Zend\ServiceManager\FactoryInterface{
    
    public function createService(\Zend\ServiceManager\ServiceLocatorInterface $serviceLocator) {
        $sysMenuTypes  = new \DAL\PDO\Oracle\SysMenuTypes();
        $slimapp = $serviceLocator->get('slimapp');
        $sysMenuTypes -> setSlimApp($slimapp);
        return $sysMenuTypes;      
    }
    
    
}