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
class InfoUsersAddressesFactory implements \Zend\ServiceManager\FactoryInterface {
    
    public function createService(\Zend\ServiceManager\ServiceLocatorInterface $serviceLocator) {
        $infoUsersAddresses = new \DAL\PDO\Postresql\InfoUsersAddresses();
        $slimApp = $serviceLocator->get('slimApp');
        $infoUsersAddresses->setSlimApp($slimApp);
        return $infoUsersAddresses;
    }

}
