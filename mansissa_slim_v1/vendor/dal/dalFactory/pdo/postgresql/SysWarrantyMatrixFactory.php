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
class SysWarrantyMatrixFactory  implements \Zend\ServiceManager\FactoryInterface{
    
    public function createService(\Zend\ServiceManager\ServiceLocatorInterface $serviceLocator) {
        $sysWarrantyMatrix = new \DAL\PDO\Postresql\SysWarrantyMatrix() ;   
        $slimapp = $serviceLocator->get('slimapp') ;            
        $sysWarrantyMatrix -> setSlimApp($slimapp); 
        return $sysWarrantyMatrix; 
    } 
    
}