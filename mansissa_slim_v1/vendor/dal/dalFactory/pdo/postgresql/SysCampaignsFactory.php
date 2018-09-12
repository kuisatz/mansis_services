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
class SysCampaignsFactory  implements \Zend\ServiceManager\FactoryInterface{
    
    public function createService(\Zend\ServiceManager\ServiceLocatorInterface $serviceLocator) {
        $sysCampaigns = new \DAL\PDO\postresql\SysCampaigns()   ;   
        $slimapp = $serviceLocator->get('slimapp') ;            
        $sysCampaigns -> setSlimApp($slimapp); 
        return $sysCampaigns; 
    } 
    
}