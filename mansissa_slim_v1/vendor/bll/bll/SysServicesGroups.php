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
class SysServicesGroups extends \BLL\BLLSlim{
    
    /**
     * constructor
     */
    public function __construct() {
        //parent::__construct();
    }
    
    /**
     * DAta insert function
     * @param array | null $params
     * @return array
     */
    public function insert($params = array()) {
        $DAL = $this->slimApp->getDALManager()->get('sysServicesGroupsOraPDO');
        return $DAL->insert($params);
    }
    
    /**
     * Data update function
     * @param array $params
     * @return array
     */
    public function update($params = array()) {
        $DAL = $this->slimApp->getDALManager()->get('sysServicesGroupsOraPDO');
        return $DAL->update($params);
    }
    
    /**
     * Data delete function
     * @param array $params
     * @return array
     */
    public function delete( $params = array()) {
        $DAL = $this->slimApp->getDALManager()->get('sysServicesGroupsOraPDO');
        return $DAL->delete($params);
    }
        /**
     * Data delete function
     * @param array $params
     * @return array
     */
    public function deleteAct( $params = array()) {
        $DAL = $this->slimApp->getDALManager()->get('sysServicesGroupsOraPDO');
        return $DAL->deleteAct($params);
    }
    

    /**
     * get all data
     * @param array $params
     * @return array
     */
    public function getAll($params = array()) {
        $DAL = $this->slimApp->getDALManager()->get('sysServicesGroupsOraPDO');
        return $DAL->getAll($params);
    }
    
    /**
     * Function to fill datagrid on user interface layer
     * @param array | null $params
     * @return array
     */
    public function fillGrid ($params = array()) {        
        $DAL = $this->slimApp->getDALManager()->get('sysServicesGroupsOraPDO');
        $resultSet = $DAL->fillGrid($params);  
        return $resultSet['resultSet'];
    }
    
    /**
     * Function to get datagrid row count on user interface layer
     * @param array $params
     * @return array
     */
    public function fillGridRowTotalCount($params = array()) {
        $DAL = $this->slimApp->getDALManager()->get('sysServicesGroupsOraPDO');
        $resultSet = $DAL->fillGridRowTotalCount($params);  
        return $resultSet['resultSet'];
    }  
    /**
     * public key / private key and value update function
     * @param array | null $params
     * @return array
     */
    public function makeActiveOrPassive($params = array()) {
        $DAL = $this->slimApp->getDALManager()->get('sysServicesGroupsOraPDO');
        return $DAL->makeActiveOrPassive($params);
    }
    /**
     * Function to fill text on user interface layer
     * @param array $params
     * @return array
     */
    public function fillServicesGroupsDdList($params = array()) {
        $DAL = $this->slimApp->getDALManager()->get('sysServicesGroupsOraPDO');
        $resultSet = $DAL->fillServicesGroupsDdList($params);
        return $resultSet['resultSet'];
    }
      /**
     * Function to fill text on user interface layer
     * @param array $params
     * @return array
     */
    public function fillServicesGroupsTree ($params = array()) {        
        $DAL = $this->slimApp->getDALManager()->get('sysServicesGroupsOraPDO');
        $resultSet = $DAL->fillServicesGroupsTree($params);  
        return $resultSet['resultSet'];
    }
    
     /**
     * Function to fill datagrid on user interface layer
     * @param array | null $params
     * @return array
     */
    public function fillServicesGroupsList ($params = array()) {        
        $DAL = $this->slimApp->getDALManager()->get('sysServicesGroupsOraPDO');
        $resultSet = $DAL->fillServicesGroupsList($params);  
        return $resultSet['resultSet'];
    }
    
    /**
     * Function to get datagrid row count on user interface layer
     * @param array $params
     * @return array
     */
    public function fillServicesGroupsListRtc($params = array()) {
        $DAL = $this->slimApp->getDALManager()->get('sysServicesGroupsOraPDO');
        $resultSet = $DAL->fillServicesGroupsListRtc($params);  
        return $resultSet['resultSet'];
    }      
    
    
}

