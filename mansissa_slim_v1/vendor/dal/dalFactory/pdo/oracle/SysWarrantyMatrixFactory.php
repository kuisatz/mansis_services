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
class SysWarrantyMatrixFactory  implements \Zend\ServiceManager\FactoryInterface{
    
    public function createService(\Zend\ServiceManager\ServiceLocatorInterface $serviceLocator) {
        $sysWarrantyMatrix = new \DAL\PDO\Oracle\SysWarrantyMatrix() ;   
        $slimapp = $serviceLocator->get('slimapp') ;            
        $sysWarrantyMatrix -> setSlimApp($slimapp); 
        return $sysWarrantyMatrix; 
    } 
    
}