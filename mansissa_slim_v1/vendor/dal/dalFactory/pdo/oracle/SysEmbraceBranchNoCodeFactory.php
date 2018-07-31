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
class SysEmbraceBranchNoCodeFactory  implements \Zend\ServiceManager\FactoryInterface{
    
    public function createService(\Zend\ServiceManager\ServiceLocatorInterface $serviceLocator) {
        $sysEmbraceBranchNoCode = new \DAL\PDO\Oracle\SysEmbraceBranchNoCode() ;   
        $slimapp = $serviceLocator->get('slimapp') ;            
        $sysEmbraceBranchNoCode -> setSlimApp($slimapp); 
        return $sysEmbraceBranchNoCode; 
    } 
    
}