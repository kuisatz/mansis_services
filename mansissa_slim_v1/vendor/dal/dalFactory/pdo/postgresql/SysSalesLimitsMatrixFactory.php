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
class SysSalesLimitsMatrixFactory  implements \Zend\ServiceManager\FactoryInterface{
    
    public function createService(\Zend\ServiceManager\ServiceLocatorInterface $serviceLocator) {
        $sysSalesLimitsMatrix = new \DAL\PDO\Oracle\SysSalesLimitsMatrix() ;   
        $slimapp = $serviceLocator->get('slimapp') ;            
        $sysSalesLimitsMatrix -> setSlimApp($slimapp); 
        return $sysSalesLimitsMatrix; 
    } 
    
}