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
class SysRmSubsidyMatrixFactory  implements \Zend\ServiceManager\FactoryInterface{
    
    public function createService(\Zend\ServiceManager\ServiceLocatorInterface $serviceLocator) {
        $sysRmSubsidyMatrix = new \DAL\PDO\Oracle\SysRmSubsidyMatrix() ;   
        $slimapp = $serviceLocator->get('slimapp') ;            
        $sysRmSubsidyMatrix -> setSlimApp($slimapp); 
        return $sysRmSubsidyMatrix; 
    } 
    
}