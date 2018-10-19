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
class InfoProjectAccFactory  implements \Zend\ServiceManager\FactoryInterface{
    
    public function createService(\Zend\ServiceManager\ServiceLocatorInterface $serviceLocator) {
        $namespc = new \DAL\PDO\Postresql\InfoProjectAcc() ;   
        $slimapp = $serviceLocator->get('slimapp') ;            
        $namespc -> setSlimApp($slimapp); 
        return $namespc; 
    } 
    
}