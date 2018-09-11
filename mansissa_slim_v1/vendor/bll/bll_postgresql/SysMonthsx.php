<?php
/**
 *  Framework 
 *
 * @link       
 * @copyright Copyright (c) 2017
 * @license   
 */

namespace BLL\BLL_POSTGRE;

/**
 * Business Layer class for report Configuration entity
 */
class SysMonthsx extends \BLL\BLLSlim{
    
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
        $DAL = $this->slimApp->getDALManager()->get('sysMonthsPostgrePDO');
        return $DAL->insert($params);
    }
    
    /**
     * Data update function 
     * @param array  $params
     * @return array
     */
    public function update( $params = array()) {
        $DAL = $this->slimApp->getDALManager()->get('sysMonthsPostgrePDO');
        return $DAL->update( $params);
    }
    
    /**
     * Data delete function
     * @param array  $params
     * @return array
     */
    public function delete( $params = array()) {
        $DAL = $this->slimApp->getDALManager()->get('sysMonthsPostgrePDO');
        return $DAL->delete($params);
    }

    /**
     * get all data
     * @param array  $params
     * @return array
     */
    public function getAll($params = array()) {
        $DAL = $this->slimApp->getDALManager()->get('sysMonthsPostgrePDO');
        return $DAL->getAll($params);
    }
    
    /**
     * Function to fill datagrid on user interface layer
     * @param array | null $params
     * @return array
     */
    public function fillGrid ($params = array()) {
        
        $DAL = $this->slimApp->getDALManager()->get('sysMonthsPostgrePDO');
        $resultSet = $DAL->fillGrid($params);  
        return $resultSet['resultSet'];
    }
    
    /**
     * Function to get datagrid row count on user interface layer
     * @param array | null $params
     * @return array
     */
    public function fillGridRowTotalCount($params = array()) {
        $DAL = $this->slimApp->getDALManager()->get('sysMonthsPostgrePDO');
        $resultSet = $DAL->fillGridRowTotalCount($params);  
        return $resultSet['resultSet'];
    }
   
     
    /**
     * Function to fill text on user interface layer
     * @param array $params
     * @return array 
    */
    public function monthsMainGroupDdList($params = array()) {
        $DAL = $this->slimApp->getDALManager()->get('sysMonthsPostgrePDO');
        $resultSet = $DAL->monthsMainGroupDdList($params);
        return $resultSet['resultSet'];
    }
     
    /**
     * Function to fill text on user interface layer
     * @param array $params
     * @return array 
    */
    public function monthsParentDdList($params = array()) {
        $DAL = $this->slimApp->getDALManager()->get('sysMonthsPostgrePDO');
        $resultSet = $DAL->monthsParentDdList($params);
        return $resultSet['resultSet'];
    }
    
    /**
     * Function to fill text on user interface layer
     * @param array $params
     * @return array 
    */
    public function justMonthsDdList($params = array()) {
        $DAL = $this->slimApp->getDALManager()->get('sysMonthsPostgrePDO');
        $resultSet = $DAL->justMonthsDdList($params);
        return $resultSet['resultSet'];
    }
    
    /**
     * Function to fill text on user interface layer
     * @param array $params
     * @return array 
    */
    public function warrantyMonthsDdList($params = array()) {
        $DAL = $this->slimApp->getDALManager()->get('sysMonthsPostgrePDO');
        $resultSet = $DAL->warrantyMonthsDdList($params);
        return $resultSet['resultSet'];
    }
    
    /**
     * Function to fill text on user interface layer
     * @param array $params
     * @return array 
    */
    public function rmMonthsDdList($params = array()) {
        $DAL = $this->slimApp->getDALManager()->get('sysMonthsPostgrePDO');
        $resultSet = $DAL->rmMonthsDdList($params);
        return $resultSet['resultSet'];
    }
    
    /**
     * Function to fill text on user interface layer
     * @param array $params
     * @return array 
    */
    public function tradebackMonthsDdList($params = array()) {
        $DAL = $this->slimApp->getDALManager()->get('sysMonthsPostgrePDO');
        $resultSet = $DAL->tradebackMonthsDdList($params);
        return $resultSet['resultSet'];
    }
    
    /**
     * Function to fill text on user interface layer
     * @param array $params
     * @return array 
    */
    public function buybackMonthsDdList($params = array()) {
        $DAL = $this->slimApp->getDALManager()->get('sysMonthsPostgrePDO');
        $resultSet = $DAL->buybackMonthsDdList($params);
        return $resultSet['resultSet'];
    }
    
    /**
     * Function to fill datagrid on user interface layer
     * @param array | null $params
     * @return array
     */
    public function fillAccBodyDeffGridx ($params = array()) { 
        $DAL = $this->slimApp->getDALManager()->get('sysMonthsPostgrePDO');
        $resultSet = $DAL->fillAccBodyDeffGridx($params);  
        return $resultSet['resultSet'];
    }
    
    /**
     * Function to get datagrid row count on user interface layer
     * @param array | null $params
     * @return array
     */
    public function fillAccBodyDeffGridxRtl($params = array()) {
        $DAL = $this->slimApp->getDALManager()->get('sysMonthsPostgrePDO');
        $resultSet = $DAL->fillAccBodyDeffGridxRtl($params);  
        return $resultSet['resultSet'];
    }
    
    /**
     * public key / private key and value update function
     * @param array | null $params
     * @return array
     */
    public function makeActiveOrPassive($params = array()) {
        $DAL = $this->slimApp->getDALManager()->get('sysMonthsPostgrePDO');
        return $DAL->makeActiveOrPassive($params);
    }
    
    /**
     * Data delete action function
     * @param array | null $params
     * @return array
     */
    public function deletedAct($params = array()) {
        $DAL = $this->slimApp->getDALManager()->get('sysMonthsPostgrePDO');
        return $DAL->deletedAct($params);
    }
    
    /**
     * Data insert action function
     * @param array | null $params
     * @return array
     */
    public function insertAct($params = array()) {
        $DAL = $this->slimApp->getDALManager()->get('sysMonthsPostgrePDO');
        return $DAL->insertAct($params);
    }
    
    /**
     * Data update action function
     * @param array | null $params
     * @return array
     */
    public function updateAct($params = array()) {
        $DAL = $this->slimApp->getDALManager()->get('sysMonthsPostgrePDO');
        return $DAL->updateAct($params);
    }
 
    
    
}

