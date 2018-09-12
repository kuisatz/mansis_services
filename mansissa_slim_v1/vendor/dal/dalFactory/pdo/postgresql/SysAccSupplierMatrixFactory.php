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
class SysAccSupplierMatrixFactory  implements \Zend\ServiceManager\FactoryInterface{
    
    public function createService(\Zend\ServiceManager\ServiceLocatorInterface $serviceLocator) {
        $sysAccSupplierMatrix= new \DAL\PDO\postresql\SysAccSupplierMatrix()   ;   
        $slimapp = $serviceLocator->get('slimapp') ;            
        $sysAccSupplierMatrix -> setSlimApp($slimapp); 
        return $sysAccSupplierMatrix; 
    } 
    
}