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
class SysAccBodyDeffFactory  implements \Zend\ServiceManager\FactoryInterface{
    
    public function createService(\Zend\ServiceManager\ServiceLocatorInterface $serviceLocator) {
        $sysAccBodyDeff = new \DAL\PDO\Oracle\SysAccBodyDeff()   ;   
        $slimapp = $serviceLocator->get('slimapp') ;            
        $sysAccBodyDeff -> setSlimApp($slimapp); 
        return $sysAccBodyDeff; 
    } 
    
}