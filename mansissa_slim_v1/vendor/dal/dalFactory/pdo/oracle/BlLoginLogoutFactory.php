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
class BlLoginLogoutFactory  implements \Zend\ServiceManager\FactoryInterface{
    
    public function createService(\Zend\ServiceManager\ServiceLocatorInterface $serviceLocator) {
        $blLoginLogout  = new \DAL\PDO\Oracle\BlLoginLogout() ;    
        $slimapp = $serviceLocator->get('slimapp') ;            
        $blLoginLogout -> setSlimApp($slimapp);
         
        return $blLoginLogout;
      
    }
    
    
}