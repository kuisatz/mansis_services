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
class InfoProjectFactory  implements \Zend\ServiceManager\FactoryInterface{
    
    public function createService(\Zend\ServiceManager\ServiceLocatorInterface $serviceLocator) {
        $infoProject = new \DAL\PDO\Postresql\InfoProject() ;   
        $slimapp = $serviceLocator->get('slimapp') ;            
        $infoProject -> setSlimApp($slimapp); 
        return $infoProject; 
    } 
    
}