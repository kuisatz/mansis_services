<?php

/**
 *  Framework 
 *
 * @link       
 * @copyright Copyright (c) 2017
 * @license   
 */

namespace BLL\BLL;

/**
 * Business Layer class for report Configuration entity
 */

class SysNavigationLeft extends \BLL\BLLSlim{
    
     /**
     * constructor
     */
    public function __construct() {
        //parent::__construct();
    }
       /**
     * Data insert function
     * @param array | null $params
     * @return array
     */ 
  public function insert($params = array()) {
        $DAL = $this->slimApp->getDALManager()->get('sysNavigationLeftPostgrePDO');
        return $DAL->insert($params);
    }
    
    /**
     * Data update function
     * @param array | null $params
     * @return array
     */
    public function update($params = array()) {
        $DAL = $this->slimApp->getDALManager()->get('sysNavigationLeftPostgrePDO');
        return $DAL->update( $params);
    }
    
    /**
     * Data delete function
     * @param array | null $params
     * @return array
     */
    public function delete($params = array()) {
        $DAL = $this->slimApp->getDALManager()->get('sysNavigationLeftPostgrePDO');
        return $DAL->delete($params);
    }

    /**
     * get all data
     * @param array | null $params
     * @return array
     */
    public function getAll($params = array()) {
        $DAL = $this->slimApp->getDALManager()->get('sysNavigationLeftPostgrePDO');
        return $DAL->getAll($params );
    }
    
    /**
     * Function to fill datagrid on user interface layer
     * @param array | null $params
     * @return array
     */
    public function fillGrid ($params = array()) {
      //  print_r('123123asdasdasd') ; 
        $DAL = $this->slimApp->getDALManager()->get('sysNavigationLeftPostgrePDO');
        $resultSet = $DAL->fillGrid($params);  
        return $resultSet['resultSet'];
    }
    
    /**
     * Function to get datagrid row count on user interface layer
     * @param array | null $params
     * @return array
     */
    public function fillGridRowTotalCount($params = array()) {
        $DAL = $this->slimApp->getDALManager()->get('sysNavigationLeftPostgrePDO');
        $resultSet = $DAL->fillGridRowTotalCount($params);  
        return $resultSet['resultSet'];
    }

        /**
     * Function to get datagrid row count on user interface layer
     * @param array | null $params
        * pk zorunlu 
     * @return array
     */
    public function pkGetLeftMenu($params = array()) {
        $DAL = $this->slimApp->getDALManager()->get('sysNavigationLeftPostgrePDO');
        $resultSet = $DAL->pkGetLeftMenu($params);  
        return $resultSet['resultSet'];
    }

    public function getLeftMenuFull() {
        $DAL = $this->slimApp->getDALManager()->get('sysNavigationLeftPostgrePDO');
        $resultSet = $DAL->getLeftMenuFull();  
        return $resultSet['resultSet'];
    }
    /*
     * Data update function   
     * @param array $params
     * @return array
     */
    public function fillGridForAdmin($params = array()) {        
        $DAL = $this->slimApp->getDALManager()->get('sysNavigationLeftPostgrePDO');     
        return $DAL->fillGridForAdmin($params);
    }
    
     /**
     * Function to get datagrid row count on user interface layer
     * @param array | null $params
     * @return array
     */
    public function fillGridForAdminRtc($params = array()) {
        $DAL = $this->slimApp->getDALManager()->get('sysNavigationLeftPostgrePDO');
        $resultSet = $DAL->fillGridForAdminRtc($params);  
        return $resultSet['resultSet'];
    } 
    
   
   /**
     * Function to fill text on user interface layer
     * @param array $params
     * @return array
     */
    public function fillForAdminTree($params = array()) {        
        $DAL = $this->slimApp->getDALManager()->get('sysNavigationLeftPostgrePDO');     
        return $DAL->fillForAdminTree($params);
    }
    
     /**
     * public key / private key and value update function
     * @param array | null $params
     * @return array
     */
    public function makeActiveOrPassive($params = array()) {
        $DAL = $this->slimApp->getDALManager()->get('sysNavigationLeftPostgrePDO');
        return $DAL->makeActiveOrPassive($params);
    }
    
    
    
    
}