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
class SysRmDeffFactory  implements \Zend\ServiceManager\FactoryInterface{
    
    public function createService(\Zend\ServiceManager\ServiceLocatorInterface $serviceLocator) {
        $sysRmDeff = new \DAL\PDO\postresql\SysRmDeff() ;   
        $slimapp = $serviceLocator->get('slimapp') ;            
        $sysRmDeff -> setSlimApp($slimapp); 
        return $sysRmDeff; 
    } 
    
}