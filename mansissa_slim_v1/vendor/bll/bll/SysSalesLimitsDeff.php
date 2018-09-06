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
class SysSalesLimitsDeff extends \BLL\BLLSlim{
    
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
        $DAL = $this->slimApp->getDALManager()->get('sysSalesLimitsDeffOraPDO');
        return $DAL->insert($params);
    }
    
    /**
     * Data update function 
     * @param array  $params
     * @return array
     */
    public function update( $params = array()) {
        $DAL = $this->slimApp->getDALManager()->get('sysSalesLimitsDeffOraPDO');
        return $DAL->update( $params);
    }
    
    /**
     * Data delete function
     * @param array  $params
     * @return array
     */
    public function delete( $params = array()) {
        $DAL = $this->slimApp->getDALManager()->get('sysSalesLimitsDeffOraPDO');
        return $DAL->delete($params);
    }

    /**
     * get all data
     * @param array  $params
     * @return array
     */
    public function getAll($params = array()) {
        $DAL = $this->slimApp->getDALManager()->get('sysSalesLimitsDeffOraPDO');
        return $DAL->getAll($params);
    }
    
    /**
     * Function to fill datagrid on user interface layer
     * @param array | null $params
     * @return array
     */
    public function fillGrid ($params = array()) {
        
        $DAL = $this->slimApp->getDALManager()->get('sysSalesLimitsDeffOraPDO');
        $resultSet = $DAL->fillGrid($params);  
        return $resultSet['resultSet'];
    }
    
    /**
     * Function to get datagrid row count on user interface layer
     * @param array | null $params
     * @return array
     */
    public function fillGridRowTotalCount($params = array()) {
        $DAL = $this->slimApp->getDALManager()->get('sysSalesLimitsDeffOraPDO');
        $resultSet = $DAL->fillGridRowTotalCount($params);  
        return $resultSet['resultSet'];
    }
    
    /**
     * Function to fill text on user interface layer
     * @param array $params
     * @return array 
    */
    public function salesLimitsDeffDdList($params = array()) {
        $DAL = $this->slimApp->getDALManager()->get('sysSalesLimitsDeffOraPDO');
        $resultSet = $DAL->salesLimitsDeffDdList($params);
        return $resultSet['resultSet'];
    }
    
        /**
     * Function to fill datagrid on user interface layer
     * @param array | null $params
     * @return array
     */
    public function fillSalesLimitsDeffGridx ($params = array()) { 
        $DAL = $this->slimApp->getDALManager()->get('sysSalesLimitsDeffOraPDO');
        $resultSet = $DAL->fillSalesLimitsDeffGridx($params);  
        return $resultSet['resultSet'];
    }
    
    /**
     * Function to get datagrid row count on user interface layer
     * @param array | null $params
     * @return array
     */
    public function fillSalesLimitsDeffGridxRtl($params = array()) {
        $DAL = $this->slimApp->getDALManager()->get('sysSalesLimitsDeffOraPDO');
        $resultSet = $DAL->fillSalesLimitsDeffGridxRtl($params);  
        return $resultSet['resultSet'];
    }
    
    /**
     * public key / private key and value update function
     * @param array | null $params
     * @return array
     */
    public function makeActiveOrPassive($params = array()) {
        $DAL = $this->slimApp->getDALManager()->get('sysSalesLimitsDeffOraPDO');
        return $DAL->makeActiveOrPassive($params);
    }
    
    /**
     * Data delete action function
     * @param array | null $params
     * @return array
     */
    public function deletedAct($params = array()) {
        $DAL = $this->slimApp->getDALManager()->get('sysSalesLimitsDeffOraPDO');
        return $DAL->deletedAct($params);
    }
    
    /**
     * Data insert action function
     * @param array | null $params
     * @return array
     */
    public function insertAct($params = array()) {
        $DAL = $this->slimApp->getDALManager()->get('sysSalesLimitsDeffOraPDO');
        return $DAL->insertAct($params);
    }
    
    /**
     * Data update action function
     * @param array | null $params
     * @return array
     */
    public function updateAct($params = array()) {
        $DAL = $this->slimApp->getDALManager()->get('sysSalesLimitsDeffOraPDO');
        return $DAL->updateAct($params);
    }
    
 
}

