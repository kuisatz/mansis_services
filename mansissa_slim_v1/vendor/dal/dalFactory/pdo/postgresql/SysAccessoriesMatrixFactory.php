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
class SysAccessoriesMatrixFactory  implements \Zend\ServiceManager\FactoryInterface{
    
    public function createService(\Zend\ServiceManager\ServiceLocatorInterface $serviceLocator) {
        $sysAccessoriesMatrix= new \DAL\PDO\Oracle\SysAccessoriesMatrix()   ;   
        $slimapp = $serviceLocator->get('slimapp') ;            
        $sysAccessoriesMatrix -> setSlimApp($slimapp); 
        return $sysAccessoriesMatrix; 
    } 
    
}