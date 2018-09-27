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
class InfoCustomerFactory  implements \Zend\ServiceManager\FactoryInterface{
    
    public function createService(\Zend\ServiceManager\ServiceLocatorInterface $serviceLocator) {
        $infoCustomer = new \DAL\PDO\Postresql\InfoCustomer() ;   
        $slimapp = $serviceLocator->get('slimapp') ;            
        $infoCustomer -> setSlimApp($slimapp); 
        return $infoCustomer; 
    } 
    
}