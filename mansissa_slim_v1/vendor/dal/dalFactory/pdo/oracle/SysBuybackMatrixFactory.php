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
class SysBuybackMatrixFactory  implements \Zend\ServiceManager\FactoryInterface{
    
    public function createService(\Zend\ServiceManager\ServiceLocatorInterface $serviceLocator) {
        $sysBuybackMatrix = new \DAL\PDO\Oracle\SysBuybackMatrix()   ;   
        $slimapp = $serviceLocator->get('slimapp') ;            
        $sysBuybackMatrix -> setSlimApp($slimapp); 
        return $sysBuybackMatrix; 
    } 
    
}