<?php
/**
 *  Framework 
 *
 * @link       
 * @copyright Copyright (c) 2017
 * @license   
 */

namespace Services\Filter;


/**
 * service manager layer for filter functions for filtering [cdata] tags
 * @author Okan CIRAN
 * @version 15/01/2016
 */
class FilterCdata implements \Zend\ServiceManager\FactoryInterface {
    
    /**
     * service ceration via factory on zend service manager
     * @param \Zend\ServiceManager\ServiceLocatorInterface $serviceLocator
     * @return boolean|\PDO
     */
    public function createService(\Zend\ServiceManager\ServiceLocatorInterface $serviceLocator) {
        // Create a filter chain and filter for usage
        $filterChain = new \Zend\Filter\FilterChain();
        $filterChain ->attach(new \Zend\Filter\PregReplace(array(
                        'pattern'     => array('/<!\[cdata\[(.*?)\]\]>/is',
                                               ),
                        'replacement' => '',
                    ), 200));
        return $filterChain;

    }

}
