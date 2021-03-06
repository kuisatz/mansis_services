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
class SysRoadtypesFactory  implements \Zend\ServiceManager\FactoryInterface{
    
    public function createService(\Zend\ServiceManager\ServiceLocatorInterface $serviceLocator) {
        $sysRoadtypes = new \DAL\PDO\Postresql\SysRoadtypes() ;   
        $slimapp = $serviceLocator->get('slimapp') ;            
        $sysRoadtypes -> setSlimApp($slimapp); 
        return $sysRoadtypes; 
    } 
    
}