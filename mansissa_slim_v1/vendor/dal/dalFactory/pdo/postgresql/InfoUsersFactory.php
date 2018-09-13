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
class InfoUsersFactory implements \Zend\ServiceManager\FactoryInterface {
    
    public function createService(\Zend\ServiceManager\ServiceLocatorInterface $serviceLocator) {
        $infoUsers = new \DAL\PDO\Postresql\InfoUsers();
        $slimApp = $serviceLocator->get('slimApp');
        $infoUsers->setSlimApp($slimApp);
        return $infoUsers;
    }

}
