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
 * @author Okan CIRANĞİ
 * created date : 08.12.2015
 */
class SysVillageFactory  implements \Zend\ServiceManager\FactoryInterface{
    
    public function createService(\Zend\ServiceManager\ServiceLocatorInterface $serviceLocator) {
        $sysVillage = new \DAL\PDO\postresql\SysVillage()   ;   
             //print_r('asqweqweqwewqweeee ') ; 
        $slimapp = $serviceLocator->get('slimapp') ;            
        $sysVillage -> setSlimApp($slimapp);
        
 
        
        return $sysVillage;
      
    }
    
    
}