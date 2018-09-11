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
class SysCauseOfLosingwinningFactory  implements \Zend\ServiceManager\FactoryInterface{
    
    public function createService(\Zend\ServiceManager\ServiceLocatorInterface $serviceLocator) {
        $sysCauseOfLosingwinning = new \DAL\PDO\Oracle\SysCauseOfLosingwinning()   ;   
        $slimapp = $serviceLocator->get('slimapp') ;            
        $sysCauseOfLosingwinning -> setSlimApp($slimapp); 
        return $sysCauseOfLosingwinning; 
    } 
    
}