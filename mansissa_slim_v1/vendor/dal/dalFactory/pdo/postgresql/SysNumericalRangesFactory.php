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
class SysNumericalRangesFactory  implements \Zend\ServiceManager\FactoryInterface{
    
    public function createService(\Zend\ServiceManager\ServiceLocatorInterface $serviceLocator) {
        $sysNumericalRanges = new \DAL\PDO\Postresql\SysNumericalRanges() ;   
        $slimapp = $serviceLocator->get('slimapp') ;            
        $sysNumericalRanges -> setSlimApp($slimapp); 
        return $sysNumericalRanges; 
    } 
    
}