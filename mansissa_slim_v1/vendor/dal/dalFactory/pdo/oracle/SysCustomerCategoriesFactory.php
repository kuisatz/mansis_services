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
class SysCustomerCategoriesFactory  implements \Zend\ServiceManager\FactoryInterface{
    
    public function createService(\Zend\ServiceManager\ServiceLocatorInterface $serviceLocator) {
        $sysCustomerCategories = new \DAL\PDO\Oracle\SysCustomerCategories() ;   
        $slimapp = $serviceLocator->get('slimapp') ;            
        $sysCustomerCategories -> setSlimApp($slimapp); 
        return $sysCustomerCategories; 
    } 
    
}