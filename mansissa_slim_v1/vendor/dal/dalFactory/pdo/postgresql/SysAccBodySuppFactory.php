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
class SysAccBodySuppFactory  implements \Zend\ServiceManager\FactoryInterface{
    
    public function createService(\Zend\ServiceManager\ServiceLocatorInterface $serviceLocator) {
        $sysAccBodySupp = new \DAL\PDO\Postresql\SysAccBodySupp()   ;   
        $slimapp = $serviceLocator->get('slimapp') ;            
        $sysAccBodySupp -> setSlimApp($slimapp); 
        return $sysAccBodySupp; 
    } 
    
}