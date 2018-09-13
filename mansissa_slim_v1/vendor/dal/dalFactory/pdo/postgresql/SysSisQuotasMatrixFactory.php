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
class SysSisQuotasMatrixFactory  implements \Zend\ServiceManager\FactoryInterface{
    
    public function createService(\Zend\ServiceManager\ServiceLocatorInterface $serviceLocator) {
        $sysSisQuotasMatrix = new \DAL\PDO\Postresql\SysSisQuotasMatrix() ;   
        $slimapp = $serviceLocator->get('slimapp') ;            
        $sysSisQuotasMatrix -> setSlimApp($slimapp); 
        return $sysSisQuotasMatrix; 
    } 
    
}