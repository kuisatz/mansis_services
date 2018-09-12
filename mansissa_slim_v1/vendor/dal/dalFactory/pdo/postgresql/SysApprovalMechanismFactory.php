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
class SysApprovalMechanismFactory  implements \Zend\ServiceManager\FactoryInterface{
    
    public function createService(\Zend\ServiceManager\ServiceLocatorInterface $serviceLocator) {
        $sysApprovalMechanism = new \DAL\PDO\postresql\SysApprovalMechanism()   ;   
        $slimapp = $serviceLocator->get('slimapp') ;            
        $sysApprovalMechanism -> setSlimApp($slimapp); 
        return $sysApprovalMechanism; 
    } 
    
}