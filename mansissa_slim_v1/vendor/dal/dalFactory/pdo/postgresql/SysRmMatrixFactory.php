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
class SysRmMatrixFactory  implements \Zend\ServiceManager\FactoryInterface{
    
    public function createService(\Zend\ServiceManager\ServiceLocatorInterface $serviceLocator) {
        $sysRmMatrix = new \DAL\PDO\Oracle\SysRmMatrix() ;   
        $slimapp = $serviceLocator->get('slimapp') ;            
        $sysRmMatrix -> setSlimApp($slimapp); 
        return $sysRmMatrix; 
    } 
    
}