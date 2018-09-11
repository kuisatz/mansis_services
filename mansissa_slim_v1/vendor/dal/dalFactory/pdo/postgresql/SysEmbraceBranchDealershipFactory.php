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
class SysEmbraceBranchDealershipFactory  implements \Zend\ServiceManager\FactoryInterface{
    
    public function createService(\Zend\ServiceManager\ServiceLocatorInterface $serviceLocator) {
        $sysEmbraceBranchDealership = new \DAL\PDO\Oracle\SysEmbraceBranchDealership() ;   
        $slimapp = $serviceLocator->get('slimapp') ;            
        $sysEmbraceBranchDealership -> setSlimApp($slimapp); 
        return $sysEmbraceBranchDealership; 
    } 
    
}