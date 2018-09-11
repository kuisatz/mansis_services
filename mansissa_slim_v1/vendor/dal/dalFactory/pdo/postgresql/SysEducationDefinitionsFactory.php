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
class SysEducationDefinitionsFactory  implements \Zend\ServiceManager\FactoryInterface{
    
    public function createService(\Zend\ServiceManager\ServiceLocatorInterface $serviceLocator) {
        $sysEducationDefinitions = new \DAL\PDO\Oracle\SysEducationDefinitions() ;   
        $slimapp = $serviceLocator->get('slimapp') ;            
        $sysEducationDefinitions -> setSlimApp($slimapp); 
        return $sysEducationDefinitions; 
    } 
    
}