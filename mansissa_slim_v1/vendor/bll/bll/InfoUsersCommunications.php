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
class InfoUsersCommunications extends \BLL\BLLSlim{
    
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
        $DAL = $this->slimApp->getDALManager()->get('infoUsersCommunicationsPostgrePDO');
        return $DAL->insert($params);
    }
 
    
      /**
     * Check Data function
     * @param array | null $params
     * @return array
     */
    public function haveRecords($params = array()) {
        $DAL = $this->slimApp->getDALManager()->get('infoUsersCommunicationsPostgrePDO');
        return $DAL->haveRecords($params);
    }
    
    
    /**
     * Data update function
     * @param array | null $params
     * @return array
     */
    public function update($params = array()) {
        $DAL = $this->slimApp->getDALManager()->get('infoUsersCommunicationsPostgrePDO');
        return $DAL->update($params);
    }
    
    /**
     * Data delete function
     * @param array | null $params
     * @return array
     */
    public function delete( $params = array()) {
        $DAL = $this->slimApp->getDALManager()->get('infoUsersCommunicationsPostgrePDO');
        return $DAL->delete($params);
    }

    /**
     * get all data
     * @param array | null $params
     * @return array
     */
    public function getAll($params = array()) {
        $DAL = $this->slimApp->getDALManager()->get('infoUsersCommunicationsPostgrePDO');
        return $DAL->getAll($params);
    }
    
    /**
     * Function to fill datagrid on user interface layer
     * @param array | null $params
     * @return array
     */
    public function fillGrid ($params = array()) {
        $DAL = $this->slimApp->getDALManager()->get('infoUsersCommunicationsPostgrePDO');
        $resultSet = $DAL->fillGrid($params);  
        return $resultSet['resultSet'];
    }
    
      
    /**
     * Function to fill datagrid on user interface layer
     * @param array | null $params
     * @return array
     */
    public function fillGridSingular ($params = array()) {
        $DAL = $this->slimApp->getDALManager()->get('infoUsersCommunicationsPostgrePDO');
        $resultSet = $DAL->fillGridSingular($params);  
        return $resultSet['resultSet'];
    }
      
    
        /**
     * Function to get datagrid row count on user interface layer
     * @param array | null $params
     * @return array
     */
    public function fillGridRowTotalCount($params = array()) {
        $DAL = $this->slimApp->getDALManager()->get('infoUsersCommunicationsPostgrePDO');
        $resultSet = $DAL->fillGridRowTotalCount($params);  
        return $resultSet['resultSet'];
    }
    
    /**
     * Function to get datagrid row count on user interface layer
     * @param array | null $params
     * @return array
     */
    public function fillGridSingularRowTotalCount($params = array()) {
        $DAL = $this->slimApp->getDALManager()->get('infoUsersCommunicationsPostgrePDO');
        $resultSet = $DAL->fillGridSingularRowTotalCount($params);  
        return $resultSet['resultSet'];
    }
    
    
    /**
     * Function to fill User Communications Types on user interface layer
     * @param array | null $params
     * @return array
     */
    public function fillUserCommunicationsTypes ($params = array()) {
        $DAL = $this->slimApp->getDALManager()->get('infoUsersCommunicationsPostgrePDO');
        $resultSet = $DAL->fillUserCommunicationsTypes($params);  
        return $resultSet['resultSet'];
    }
    
     /**
     * Data delete action function
     * @param array | null $params
     * @return array
     */
    public function deletedAct($params = array()) {
        $DAL = $this->slimApp->getDALManager()->get('infoUsersCommunicationsPostgrePDO');
        return $DAL->deletedAct($params);
    }
    
    
    
    
    /**
     * Data insert function
     * @param array | null $params
     * @return array
     */
    public function insertTemp($params = array()) {
        $DAL = $this->slimApp->getDALManager()->get('infoUsersCommunicationsPostgrePDO');
        return $DAL->insertTemp($params);
    }
    
    /**
     * Data update function
     * @param array | null $params
     * @return array
     */
    public function updateTemp($params = array()) {
        $DAL = $this->slimApp->getDALManager()->get('infoUsersCommunicationsPostgrePDO');
        return $DAL->updateTemp($params);
    }
    
    /**
     * Function to fill datagrid on user interface layer
     * @param array | null $params
     * @return array
     */
    public function fillGridSingularTemp ($params = array()) {
        $DAL = $this->slimApp->getDALManager()->get('infoUsersCommunicationsPostgrePDO');
        $resultSet = $DAL->fillGridSingularTemp($params);  
        return $resultSet['resultSet'];
    }    
    
    /**
     * Function to get datagrid row count on user interface layer
     * @param array | null $params
     * @return array
     */
    public function fillGridSingularRowTotalCountTemp($params = array()) {
        $DAL = $this->slimApp->getDALManager()->get('infoUsersCommunicationsPostgrePDO');
        $resultSet = $DAL->fillGridSingularRowTotalCountTemp($params);  
        return $resultSet['resultSet'];
    }    
      
    /**
     * Function to fill User Communications Types on user interface layer
     * @param array | null $params
     * @return array
     */
    public function fillUserCommunicationsTypesTemp ($params = array()) {
        $DAL = $this->slimApp->getDALManager()->get('infoUsersCommunicationsPostgrePDO');
        $resultSet = $DAL->fillUserCommunicationsTypesTemp($params);  
        return $resultSet['resultSet'];
    }
    
    /**
    * Data delete action function
    * @param array | null $params
    * @return array
    */
    public function deletedActTemp($params = array()) {
        $DAL = $this->slimApp->getDALManager()->get('infoUsersCommunicationsPostgrePDO');
        return $DAL->deletedActTemp($params);
    }
}

