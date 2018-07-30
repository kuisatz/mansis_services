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
class InfoUsersVerbal extends \BLL\BLLSlim{
    
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
        $DAL = $this->slimApp->getDALManager()->get('infoUsersVerbalOraPDO');
        return $DAL->insert($params);
    }
    
    /**
     * Check Data function
     * @param array | null $params
     * @return array
     */
    public function haveRecords($params = array()) {
        $DAL = $this->slimApp->getDALManager()->get('infoUsersVerbalOraPDO');
        return $DAL->haveRecords($params);
    }    
    
    /**
     * Data update function
     * @param array | null $params
     * @return array
     */
    public function update($params = array()) {     
        $DAL = $this->slimApp->getDALManager()->get('infoUsersVerbalOraPDO');
        return $DAL->update($params);
    }
    
    /**
     * Data delete function
     * @param array | null $params
     * @return array
     */
    public function delete( $params = array()) {
        $DAL = $this->slimApp->getDALManager()->get('infoUsersVerbalOraPDO');
        return $DAL->delete($params);
    }

    /**
     * get all data
     * @param array | null $params
     * @return array
     */
    public function getAll($params = array()) {
        $DAL = $this->slimApp->getDALManager()->get('infoUsersVerbalOraPDO');
        return $DAL->getAll($params);
    }
    
    /**
     * Function to fill datagrid on user interface layer
     * @param array | null $params
     * @return array
     */
    public function fillGrid ($params = array()) {
        $DAL = $this->slimApp->getDALManager()->get('infoUsersVerbalOraPDO');
        $resultSet = $DAL->fillGrid($params);  
        return $resultSet['resultSet'];
    }
    
    /**
     * Function to get datagrid row count on user interface layer
     * @param array | null $params
     * @return array
     */
    public function fillGridRowTotalCount($params = array()) {
        $DAL = $this->slimApp->getDALManager()->get('infoUsersVerbalOraPDO');
        $resultSet = $DAL->fillGridRowTotalCount($params);  
        return $resultSet['resultSet'];
    }
    
     /**
     * Data delete action function
     * @param array | null $params
     * @return array
     */
    public function deletedAct($params = array()) {
        $DAL = $this->slimApp->getDALManager()->get('infoUsersVerbalOraPDO');
        return $DAL->deletedAct($params);
    } 
    
    /**
     * Data update function   
     * @param array $params
     * @return array
     */     
    public function fillUsersVerbalNpk ($params = array()) {
        $DAL = $this->slimApp->getDALManager()->get('infoUsersVerbalOraPDO');
        $resultSet = $DAL->fillUsersVerbalNpk($params);  
        return $resultSet['resultSet'];
    }

    /**
     * Data update function   
     * @param array $params
     * @return array
     */     
    public function fillUsersVerbalNpkGuest ($params = array()) {
        $DAL = $this->slimApp->getDALManager()->get('infoUsersVerbalOraPDO');
        $resultSet = $DAL->fillUsersVerbalNpkGuest($params);  
        return $resultSet['resultSet'];
    }
    
    
    /**
     * get consultant confirmation process details
     * @param array $params
     * @return array
     */
    public function getUserVerbalConsultant($params = array()) {
        $DAL = $this->slimApp->getDALManager()->get('infoUsersVerbalOraPDO');
        return $DAL->getUserVerbalConsultant($params);
    }
  
    
    
}

