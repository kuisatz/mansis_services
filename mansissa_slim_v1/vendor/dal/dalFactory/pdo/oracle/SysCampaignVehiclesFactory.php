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
 */
class SysCampaignVehiclesFactory  implements \Zend\ServiceManager\FactoryInterface{
    
    public function createService(\Zend\ServiceManager\ServiceLocatorInterface $serviceLocator) {
        $sysCampaignVehicles = new \DAL\PDO\Oracle\SysCampaignVehicles()   ;   
        $slimapp = $serviceLocator->get('slimapp') ;            
        $sysCampaignVehicles -> setSlimApp($slimapp); 
        return $sysCampaignVehicles; 
    } 
    
}