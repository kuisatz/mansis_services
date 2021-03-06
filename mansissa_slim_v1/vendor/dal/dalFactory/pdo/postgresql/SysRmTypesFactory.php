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
class SysRmTypesFactory  implements \Zend\ServiceManager\FactoryInterface{
    
    public function createService(\Zend\ServiceManager\ServiceLocatorInterface $serviceLocator) {
        $sysRmTypes = new \DAL\PDO\Postresql\SysRmTypes() ;   
        $slimapp = $serviceLocator->get('slimapp') ;            
        $sysRmTypes -> setSlimApp($slimapp); 
        return $sysRmTypes; 
    } 
    
}