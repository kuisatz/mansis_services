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
class SysNumericalRanges extends \BLL\BLLSlim{
    
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
        $DAL = $this->slimApp->getDALManager()->get('sysNumericalRangesPostgrePDO');
        return $DAL->insert($params);
    }
    
    /**
     * Data update function 
     * @param array  $params
     * @return array
     */
    public function update( $params = array()) {
        $DAL = $this->slimApp->getDALManager()->get('sysNumericalRangesPostgrePDO');
        return $DAL->update( $params);
    }
    
    /**
     * Data delete function
     * @param array  $params
     * @return array
     */
    public function delete( $params = array()) {
        $DAL = $this->slimApp->getDALManager()->get('sysNumericalRangesPostgrePDO');
        return $DAL->delete($params);
    }

    /**
     * get all data
     * @param array  $params
     * @return array
     */
    public function getAll($params = array()) {
        $DAL = $this->slimApp->getDALManager()->get('sysNumericalRangesPostgrePDO');
        return $DAL->getAll($params);
    }
    
    /**
     * Function to fill datagrid on user interface layer
     * @param array | null $params
     * @return array
     */
    public function fillGrid ($params = array()) {
        
        $DAL = $this->slimApp->getDALManager()->get('sysNumericalRangesPostgrePDO');
        $resultSet = $DAL->fillGrid($params);  
        return $resultSet['resultSet'];
    }
    
    /**
     * Function to get datagrid row count on user interface layer
     * @param array | null $params
     * @return array
     */
    public function fillGridRowTotalCount($params = array()) {
        $DAL = $this->slimApp->getDALManager()->get('sysNumericalRangesPostgrePDO');
        $resultSet = $DAL->fillGridRowTotalCount($params);  
        return $resultSet['resultSet'];
    }
   
     
    /**
     * Function to fill text on user interface layer
     * @param array $params
     * @return array 
    */
    public function numericalRangesMainGroupDdList($params = array()) {
        $DAL = $this->slimApp->getDALManager()->get('sysNumericalRangesPostgrePDO');
        $resultSet = $DAL->numericalRangesMainGroupDdList($params);
        return $resultSet['resultSet'];
    }
  
    /**
     * Function to fill text on user interface layer
     * @param array $params
     * @return array 
    */
    public function numericalRangesParentDdList($params = array()) {
        $DAL = $this->slimApp->getDALManager()->get('sysNumericalRangesPostgrePDO');
        $resultSet = $DAL->numericalRangesParentDdList($params);
        return $resultSet['resultSet'];
    }
    
    /**
     * Function to fill text on user interface layer
     * @param array $params
     * @return array 
    */
    public function numericalRangesBuybackDdList($params = array()) {
        $DAL = $this->slimApp->getDALManager()->get('sysNumericalRangesPostgrePDO');
        $resultSet = $DAL->numericalRangesBuybackDdList($params);
        return $resultSet['resultSet'];
    }
    
    /**
     * Function to fill text on user interface layer
     * @param array $params
     * @return array 
    */
    public function numericalRangesDemoDdList($params = array()) {
        $DAL = $this->slimApp->getDALManager()->get('sysNumericalRangesPostgrePDO');
        $resultSet = $DAL->numericalRangesDemoDdList($params);
        return $resultSet['resultSet'];
    }
    
    /**
     * Function to fill text on user interface layer
     * @param array $params
     * @return array 
    */
    public function numericalRangesRmDdList($params = array()) {
        $DAL = $this->slimApp->getDALManager()->get('sysNumericalRangesPostgrePDO');
        $resultSet = $DAL->numericalRangesRmDdList($params);
        return $resultSet['resultSet'];
    }
    
    /**
     * Function to fill text on user interface layer
     * @param array $params
     * @return array 
    */
    public function numericalRangesTradebackDdList($params = array()) {
        $DAL = $this->slimApp->getDALManager()->get('sysNumericalRangesPostgrePDO');
        $resultSet = $DAL->numericalRangesTradebackDdList($params);
        return $resultSet['resultSet'];
    }
    
    /**
     * Function to fill text on user interface layer
     * @param array $params
     * @return array 
    */
    public function numericalRangesVeichlesDdList($params = array()) {
        $DAL = $this->slimApp->getDALManager()->get('sysNumericalRangesPostgrePDO');
        $resultSet = $DAL->numericalRangesVeichlesDdList($params);
        return $resultSet['resultSet'];
    }
    /**
     * Function to fill text on user interface layer
     * @param array $params
     * @return array 
    */
    public function numericalRangesEmployeesDdList($params = array()) {
        $DAL = $this->slimApp->getDALManager()->get('sysNumericalRangesPostgrePDO');
        $resultSet = $DAL->numericalRangesEmployeesDdList($params);
        return $resultSet['resultSet'];
    }
    
        /**
     * Function to fill datagrid on user interface layer
     * @param array | null $params
     * @return array
     */
    public function fillNumericalRangesGridx ($params = array()) { 
        $DAL = $this->slimApp->getDALManager()->get('sysNumericalRangesPostgrePDO');
        $resultSet = $DAL->fillNumericalRangesGridx($params);  
        return $resultSet['resultSet'];
    }
    
    /**
     * Function to get datagrid row count on user interface layer
     * @param array | null $params
     * @return array
     */
    public function fillNumericalRangesGridxRtl($params = array()) {
        $DAL = $this->slimApp->getDALManager()->get('sysNumericalRangesPostgrePDO');
        $resultSet = $DAL->fillNumericalRangesGridxRtl($params);  
        return $resultSet['resultSet'];
    }
    
    /**
     * public key / private key and value update function
     * @param array | null $params
     * @return array
     */
    public function makeActiveOrPassive($params = array()) {
        $DAL = $this->slimApp->getDALManager()->get('sysNumericalRangesPostgrePDO');
        return $DAL->makeActiveOrPassive($params);
    }
    
    /**
     * Data delete action function
     * @param array | null $params
     * @return array
     */
    public function deletedAct($params = array()) {
        $DAL = $this->slimApp->getDALManager()->get('sysNumericalRangesPostgrePDO');
        return $DAL->deletedAct($params);
    }
    
    /**
     * Data insert action function
     * @param array | null $params
     * @return array
     */
    public function insertAct($params = array()) {
        $DAL = $this->slimApp->getDALManager()->get('sysNumericalRangesPostgrePDO');
        return $DAL->insertAct($params);
    }
    
    /**
     * Data update action function
     * @param array | null $params
     * @return array
     */
    public function updateAct($params = array()) {
        $DAL = $this->slimApp->getDALManager()->get('sysNumericalRangesPostgrePDO');
        return $DAL->updateAct($params);
    }
    
 
    
}

