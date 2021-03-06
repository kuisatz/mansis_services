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
class LogAdminFactory implements \Zend\ServiceManager\FactoryInterface {
    
    public function createService(\Zend\ServiceManager\ServiceLocatorInterface $serviceLocator) {
        $logAdmin = new \DAL\PDO\Postresql\LogAdmin();
        $slimApp = $serviceLocator->get('slimApp');
        $logAdmin->setSlimApp($slimApp);
        return $logAdmin;
    }

}
