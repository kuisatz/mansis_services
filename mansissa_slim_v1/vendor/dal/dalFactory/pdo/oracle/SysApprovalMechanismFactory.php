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
class SysApprovalMechanismFactory  implements \Zend\ServiceManager\FactoryInterface{
    
    public function createService(\Zend\ServiceManager\ServiceLocatorInterface $serviceLocator) {
        $sysApprovalMechanism = new \DAL\PDO\Oracle\SysApprovalMechanism()   ;   
        $slimapp = $serviceLocator->get('slimapp') ;            
        $sysApprovalMechanism -> setSlimApp($slimapp); 
        return $sysApprovalMechanism; 
    } 
    
}