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
 * created date : 08.02.2016
 */
class SysOperationTypesFactory  implements \Zend\ServiceManager\FactoryInterface{
    
    public function createService(\Zend\ServiceManager\ServiceLocatorInterface $serviceLocator) {
        $sysOperationTypes  = new \DAL\PDO\Postresql\SysOperationTypes();   
        $slimapp = $serviceLocator->get('slimapp') ;            
        $sysOperationTypes -> setSlimApp($slimapp);
        
 
        
        return $sysOperationTypes;
      
    }
    
    
}