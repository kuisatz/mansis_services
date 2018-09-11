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
class SysTitlesFactory  implements \Zend\ServiceManager\FactoryInterface{
    
    public function createService(\Zend\ServiceManager\ServiceLocatorInterface $serviceLocator) {
        $sysTitles = new \DAL\PDO\Oracle\SysTitles() ;   
        $slimapp = $serviceLocator->get('slimapp') ;            
        $sysTitles -> setSlimApp($slimapp); 
        return $sysTitles; 
    } 
    
}