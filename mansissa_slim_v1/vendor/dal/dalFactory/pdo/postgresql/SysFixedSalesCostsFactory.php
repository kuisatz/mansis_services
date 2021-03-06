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
class SysFixedSalesCostsFactory  implements \Zend\ServiceManager\FactoryInterface{
    
    public function createService(\Zend\ServiceManager\ServiceLocatorInterface $serviceLocator) {
        $sysFixedSalesCosts = new \DAL\PDO\Postresql\SysFixedSalesCosts() ;   
        $slimapp = $serviceLocator->get('slimapp') ;            
        $sysFixedSalesCosts -> setSlimApp($slimapp); 
        return $sysFixedSalesCosts; 
    } 
    
}