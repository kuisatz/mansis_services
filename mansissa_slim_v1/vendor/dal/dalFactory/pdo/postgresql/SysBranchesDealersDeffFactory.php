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
class SysBranchesDealersDeffFactory  implements \Zend\ServiceManager\FactoryInterface{
    
    public function createService(\Zend\ServiceManager\ServiceLocatorInterface $serviceLocator) {
        $sysBranchesDealersDeff = new \DAL\PDO\Postresql\SysBranchAndDealersDeff()   ;   
        $slimapp = $serviceLocator->get('slimapp') ;            
        $sysBranchesDealersDeff -> setSlimApp($slimapp); 
        return $sysBranchesDealersDeff; 
    } 
    
}