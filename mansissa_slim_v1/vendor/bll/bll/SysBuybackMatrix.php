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
class SysBuybackMatrix extends \BLL\BLLSlim{
    
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
        $DAL = $this->slimApp->getDALManager()->get('sysBuybackMatrixPostgrePDO');
        return $DAL->insert($params);
    }
    
    /**
     * Data update function 
     * @param array  $params
     * @return array
     */
    public function update( $params = array()) {
        $DAL = $this->slimApp->getDALManager()->get('sysBuybackMatrixPostgrePDO');
        return $DAL->update( $params);
    }
    
    /**
     * Data delete function
     * @param array  $params
     * @return array
     */
    public function delete( $params = array()) {
        $DAL = $this->slimApp->getDALManager()->get('sysBuybackMatrixPostgrePDO');
        return $DAL->delete($params);
    }

    /**
     * get all data
     * @param array  $params
     * @return array
     */
    public function getAll($params = array()) {
        $DAL = $this->slimApp->getDALManager()->get('sysBuybackMatrixPostgrePDO');
        return $DAL->getAll($params);
    }
    
    /**
     * Function to fill datagrid on user interface layer
     * @param array | null $params
     * @return array
     */
    public function fillGrid ($params = array()) {  
        $DAL = $this->slimApp->getDALManager()->get('sysBuybackMatrixPostgrePDO');
        $resultSet = $DAL->fillGrid($params);  
        return $resultSet['resultSet'];
    }
     
    /**
     * Function to fill datagrid on user interface layer
     * @param array | null $params
     * @return array
     */
    public function fillBuybackMatrixGridx ($params = array()) { 
        $DAL = $this->slimApp->getDALManager()->get('sysBuybackMatrixPostgrePDO');
        $resultSet = $DAL->fillBuybackMatrixGridx($params);  
        return $resultSet['resultSet'];
    }
    
    /**
     * Function to get datagrid row count on user interface layer
     * @param array | null $params
     * @return array
     */
    public function fillBuybackMatrixGridxRtl($params = array()) {
        $DAL = $this->slimApp->getDALManager()->get('sysBuybackMatrixPostgrePDO');
        $resultSet = $DAL->fillBuybackMatrixGridxRtl($params);  
        return $resultSet['resultSet'];
    }
      
    /**
     * Function to fill datagrid on user interface layer
     * @param array | null $params
     * @return array
     */
    public function fillTradebackMatrixGridx ($params = array()) { 
        $DAL = $this->slimApp->getDALManager()->get('sysBuybackMatrixPostgrePDO');
        $resultSet = $DAL->fillTradebackMatrixGridx($params);  
        return $resultSet['resultSet'];
    }
    
    /**
     * Function to get datagrid row count on user interface layer
     * @param array | null $params
     * @return array
     */
    public function fillTradebackMatrixGridxRtl($params = array()) {
        $DAL = $this->slimApp->getDALManager()->get('sysBuybackMatrixPostgrePDO');
        $resultSet = $DAL->fillTradebackMatrixGridxRtl($params);  
        return $resultSet['resultSet'];
    }
      
    /**
     * Function to get datagrid row count on user interface layer
     * @param array | null $params
     * @return array
     */
    public function fillGridRowTotalCount($params = array()) {
        $DAL = $this->slimApp->getDALManager()->get('sysBuybackMatrixPostgrePDO');
        $resultSet = $DAL->fillGridRowTotalCount($params);  
        return $resultSet['resultSet'];
    }
     
    /**
     * public key / private key and value update function
     * @param array | null $params
     * @return array
     */
    public function makeActiveOrPassive($params = array()) {
        $DAL = $this->slimApp->getDALManager()->get('sysBuybackMatrixPostgrePDO');
        return $DAL->makeActiveOrPassive($params);
    }
    
    /**
     * Data delete action function
     * @param array | null $params
     * @return array
     */
    public function deletedAct($params = array()) {
        $DAL = $this->slimApp->getDALManager()->get('sysBuybackMatrixPostgrePDO');
        return $DAL->deletedAct($params);
    }
    
    /**
     * Data insert action function
     * @param array | null $params
     * @return array
     */
    public function insertBBAct($params = array()) {
        $DAL = $this->slimApp->getDALManager()->get('sysBuybackMatrixPostgrePDO');
        return $DAL->insertBBAct($params);
    }
    
     /**
     * Data insert action function
     * @param array | null $params
     * @return array
     */
    public function insertTBAct($params = array()) {
        $DAL = $this->slimApp->getDALManager()->get('sysBuybackMatrixPostgrePDO');
        return $DAL->insertTBAct($params);
    }
    
    /**
     * Data update action function
     * @param array | null $params
     * @return array
     */
    public function updateBBAct($params = array()) {
        $DAL = $this->slimApp->getDALManager()->get('sysBuybackMatrixPostgrePDO');
        return $DAL->updateBBAct($params);
    }
    
     /**
     * Data update action function
     * @param array | null $params
     * @return array
     */
    public function updateTBAct($params = array()) {
        $DAL = $this->slimApp->getDALManager()->get('sysBuybackMatrixPostgrePDO');
        return $DAL->updateTBAct($params);
    }
    
     /**
     * Data update action function
     * @param array | null $params
     * @return array
     */
    public function updateActLng($params = array()) {
        $DAL = $this->slimApp->getDALManager()->get('sysBuybackMatrixPostgrePDO');
        return $DAL->updateActLng($params);
    }
     
    /**
     * Function to fill datagrid on user interface layer
     * @param array | null $params
     * @return array
     */
    public function fillBBSpecialGridx ($params = array()) { 
        $DAL = $this->slimApp->getDALManager()->get('sysBuybackMatrixPostgrePDO');
        $resultSet = $DAL->fillBBSpecialGridx($params);  
        return $resultSet['resultSet'];
    }
   
    
}

