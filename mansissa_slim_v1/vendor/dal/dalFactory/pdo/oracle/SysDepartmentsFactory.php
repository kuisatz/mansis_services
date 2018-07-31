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
class SysDepartmentsFactory  implements \Zend\ServiceManager\FactoryInterface{
    
    public function createService(\Zend\ServiceManager\ServiceLocatorInterface $serviceLocator) {
        $sysDepartments = new \DAL\PDO\Oracle\SysDepartments() ;   
        $slimapp = $serviceLocator->get('slimapp') ;            
        $sysDepartments -> setSlimApp($slimapp); 
        return $sysDepartments; 
    } 
    
}