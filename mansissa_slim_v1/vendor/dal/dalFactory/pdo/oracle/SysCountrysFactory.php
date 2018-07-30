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
 * created date : 08.12.2015
 */
class SysCountrysFactory  implements \Zend\ServiceManager\FactoryInterface{
    
    public function createService(\Zend\ServiceManager\ServiceLocatorInterface $serviceLocator) {
        $sysCountrys  = new \DAL\PDO\Oracle\SysCountrys()   ;   
        $slimapp = $serviceLocator->get('slimapp') ;            
        $sysCountrys -> setSlimApp($slimapp);
         
        
        return $sysCountrys;
      
    }
    
    
}