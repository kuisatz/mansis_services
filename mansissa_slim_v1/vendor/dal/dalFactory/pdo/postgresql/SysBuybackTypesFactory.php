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
class SysBuybackTypesFactory  implements \Zend\ServiceManager\FactoryInterface{
    
    public function createService(\Zend\ServiceManager\ServiceLocatorInterface $serviceLocator) {
        $sysBuybackTypes = new \DAL\PDO\Oracle\SysBuybackTypes()   ;   
        $slimapp = $serviceLocator->get('slimapp') ;            
        $sysBuybackTypes -> setSlimApp($slimapp); 
        return $sysBuybackTypes; 
    } 
    
}