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
 * created date : 08.12.2015
 */
class BlActivationReportFactory  implements \Zend\ServiceManager\FactoryInterface{
    
    public function createService(\Zend\ServiceManager\ServiceLocatorInterface $serviceLocator) {
        $blActivationReport  = new \DAL\PDO\postresql\BlActivationReport()   ;   
        $slimapp = $serviceLocator->get('slimapp') ;            
        $blActivationReport -> setSlimApp($slimapp);
        
 
        
        return $blActivationReport;
      
    }
    
    
}