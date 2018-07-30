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
class LogConnectionFactory implements \Zend\ServiceManager\FactoryInterface {
    
    public function createService(\Zend\ServiceManager\ServiceLocatorInterface $serviceLocator) {
        $logConnection = new \DAL\PDO\postresql\LogConnection();
        $slimApp = $serviceLocator->get('slimApp');
        $logConnection->setSlimApp($slimApp);
        return $logConnection;
    }

}
