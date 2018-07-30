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
 * created date : 03.12.2015
 */
class CmpnyEqpmntFactory  implements \Zend\ServiceManager\FactoryInterface{
    
    public function createService(\Zend\ServiceManager\ServiceLocatorInterface $serviceLocator) {
        $cmpnyEqpmnt  = new \DAL\PDO\Oracle\CmpnyEqpmnt() ;   
        $slimapp = $serviceLocator->get('slimapp') ;            
        $cmpnyEqpmnt -> setSlimApp($slimapp);

return $cmpnyEqpmnt;
      
    }
    
    
}