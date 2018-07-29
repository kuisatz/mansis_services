<?php
/**
 *  Rest Api Proxy Library
 *
 * @link      
 * @copyright Copyright (c) 2017 
 * @author Mustafa Zeynel Dağlı
 * @version 0.0.1
 * @deprecated since version 0.2
 */

namespace Proxy;

/**
 * base abstract class for proxy helpers
 */
abstract class AbstractProxyHelper {
    protected $proxyClass;
    
    
    


    /**
     * set proxy class
     * @param \OAuth2\SlimProxyClass $proxyClass
     * @author Mustafa Zeynel Dağlı 
     * @since 0.0.1
     */
    public function setProxyClass(\Proxy\Proxy\AbstractProxy $proxyClass) {
        $this->proxyClass = $proxyClass;
    }
    
    /**
     * return proxy class
     * @return \Proxy\Prox\AbstractProxy
     * @author Mustafa Zeynel Dağlı 
     * @since 0.0.1
     */
    public function getProxyClass() {
        return $this->proxyClass;
    }
  
}
