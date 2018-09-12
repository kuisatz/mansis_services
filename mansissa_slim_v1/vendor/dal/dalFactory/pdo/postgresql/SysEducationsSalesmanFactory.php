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
class SysEducationsSalesmanFactory  implements \Zend\ServiceManager\FactoryInterface{
    
    public function createService(\Zend\ServiceManager\ServiceLocatorInterface $serviceLocator) {
        $sysEducationsSalesman = new \DAL\PDO\postresql\SysEducationsSalesman() ;   
        $slimapp = $serviceLocator->get('slimapp') ;            
        $sysEducationsSalesman -> setSlimApp($slimapp); 
        return $sysEducationsSalesman; 
    } 
    
}