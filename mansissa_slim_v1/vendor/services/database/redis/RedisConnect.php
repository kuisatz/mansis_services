<?php
/**
 * OSB İMALAT Framework 
 *
 * @link      https://github.com/corner82/slim_test for the canonical source repository
 * @copyright Copyright (c) 2015 OSB İMALAT (http://www.uretimosb.com)
 * @license 
 */

namespace Services\Database\Redis;

 
/**
 * service manager layer for database connection
 * @author Mustafa Zeynel Dağlı
 */
class RedisConnect implements \Zend\ServiceManager\FactoryInterface {
    
    /**
     * service ceration via factory on zend service manager
     * @param \Zend\ServiceManager\ServiceLocatorInterface $serviceLocator
     * @return boolean|\PDO
     */
    public function createService(\Zend\ServiceManager\ServiceLocatorInterface $serviceLocator) {
        try {
            
            $redis = new \Redis(); 
            $redis->connect('127.0.0.1', 6379); 
            $redis->auth('1q2w3e4r'); 
            
            return $redis;
        } catch ( Exception $e ) {
            return false;
        } 


    }

}
