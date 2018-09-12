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
class SysBuybackQuotasFactory  implements \Zend\ServiceManager\FactoryInterface{
    
    public function createService(\Zend\ServiceManager\ServiceLocatorInterface $serviceLocator) {
        $sysBuybackQuotas = new \DAL\PDO\postresql\SysBuybackQuotas()   ;   
        $slimapp = $serviceLocator->get('slimapp') ;            
        $sysBuybackQuotas -> setSlimApp($slimapp); 
        return $sysBuybackQuotas; 
    } 
    
}