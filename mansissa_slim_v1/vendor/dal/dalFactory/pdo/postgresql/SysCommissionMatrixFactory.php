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
class SysCommissionMatrixFactory  implements \Zend\ServiceManager\FactoryInterface{
    
    public function createService(\Zend\ServiceManager\ServiceLocatorInterface $serviceLocator) {
        $sysCommissionMatrix = new \DAL\PDO\Postresql\SysCommissionMatrix()   ;   
        $slimapp = $serviceLocator->get('slimapp') ;            
        $sysCommissionMatrix -> setSlimApp($slimapp); 
        return $sysCommissionMatrix; 
    } 
    
}