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
class SysSisStatusFactory  implements \Zend\ServiceManager\FactoryInterface{
    
    public function createService(\Zend\ServiceManager\ServiceLocatorInterface $serviceLocator) {
        $sysSisStatus = new \DAL\PDO\Oracle\SysSisStatus() ;   
        $slimapp = $serviceLocator->get('slimapp') ;            
        $sysSisStatus -> setSlimApp($slimapp); 
        return $sysSisStatus; 
    } 
    
}