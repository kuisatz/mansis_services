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
class SysTopusedBranchdealersFactory  implements \Zend\ServiceManager\FactoryInterface{
    
    public function createService(\Zend\ServiceManager\ServiceLocatorInterface $serviceLocator) {
        $sysTopusedBranchdealers = new \DAL\PDO\Oracle\SysTopusedBranchdealers() ;   
        $slimapp = $serviceLocator->get('slimapp') ;            
        $sysTopusedBranchdealers -> setSlimApp($slimapp); 
        return $sysTopusedBranchdealers; 
    } 
    
}