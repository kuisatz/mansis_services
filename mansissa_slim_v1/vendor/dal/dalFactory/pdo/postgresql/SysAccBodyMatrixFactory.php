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
class SysAccBodyMatrixFactory  implements \Zend\ServiceManager\FactoryInterface{
    
    public function createService(\Zend\ServiceManager\ServiceLocatorInterface $serviceLocator) {
        $sysAccBodyMatrix = new \DAL\PDO\Oracle\SysAccBodyMatrix()   ;   
        $slimapp = $serviceLocator->get('slimapp') ;            
        $sysAccBodyMatrix -> setSlimApp($slimapp); 
        return $sysAccBodyMatrix; 
    } 
    
}