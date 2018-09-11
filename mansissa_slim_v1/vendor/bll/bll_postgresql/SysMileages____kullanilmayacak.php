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
class SysMileages____kullanilmayacak extends \BLL\BLLSlim{
    
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
        $DAL = $this->slimApp->getDALManager()->get('sysMileagesPostgrePDO____');
        return $DAL->insert($params);
    }
    
    /**
     * Data update function 
     * @param array  $params
     * @return array
     */
    public function update( $params = array()) {
        $DAL = $this->slimApp->getDALManager()->get('sysMileagesPostgrePDO____');
        return $DAL->update( $params);
    }
    
    /**
     * Data delete function
     * @param array  $params
     * @return array
     */
    public function delete( $params = array()) {
        $DAL = $this->slimApp->getDALManager()->get('sysMileagesPostgrePDO____');
        return $DAL->delete($params);
    }

    /**
     * get all data
     * @param array  $params
     * @return array
     */
    public function getAll($params = array()) {
        $DAL = $this->slimApp->getDALManager()->get('sysMileagesPostgrePDO____');
        return $DAL->getAll($params);
    }
    
    /**
     * Function to fill datagrid on user interface layer
     * @param array | null $params
     * @return array
     */
    public function fillGrid ($params = array()) {
        
        $DAL = $this->slimApp->getDALManager()->get('sysMileagesPostgrePDO____');
        $resultSet = $DAL->fillGrid($params);  
        return $resultSet['resultSet'];
    }
    
    /**
     * Function to get datagrid row count on user interface layer
     * @param array | null $params
     * @return array
     */
    public function fillGridRowTotalCount($params = array()) {
        $DAL = $this->slimApp->getDALManager()->get('sysMileagesPostgrePDO____');
        $resultSet = $DAL->fillGridRowTotalCount($params);  
        return $resultSet['resultSet'];
    }
   
     
    /**
     * Function to fill text on user interface layer
     * @param array $params
     * @return array 
    */
    public function mileagesMainGroupDdList($params = array()) {
        $DAL = $this->slimApp->getDALManager()->get('sysMileagesPostgrePDO____');
        $resultSet = $DAL->mileagesMainGroupDdList($params);
        return $resultSet['resultSet'];
    }
    
    /**
     * Function to fill text on user interface layer
     * @param array $params
     * @return array 
    */
    public function mileagesParentDdList($params = array()) {
        $DAL = $this->slimApp->getDALManager()->get('sysMileagesPostgrePDO____');
        $resultSet = $DAL->mileagesParentDdList($params);
        return $resultSet['resultSet'];
    }
    
    /**
     * Function to fill text on user interface layer
     * @param array $params
     * @return array 
    */
    public function mileagesRmDdList($params = array()) {
        $DAL = $this->slimApp->getDALManager()->get('sysMileagesPostgrePDO____');
        $resultSet = $DAL->mileagesRmDdList($params);
        return $resultSet['resultSet'];
    }
    
       /**
     * Function to fill text on user interface layer
     * @param array $params
     * @return array 
    */
    public function mileagesBuybackDdList($params = array()) {
        $DAL = $this->slimApp->getDALManager()->get('sysMileagesPostgrePDO____');
        $resultSet = $DAL->mileagesBuybackDdList($params);
        return $resultSet['resultSet'];
    }
    
       /**
     * Function to fill text on user interface layer
     * @param array $params
     * @return array 
    */
    public function mileagesTradebackDdList($params = array()) {
        $DAL = $this->slimApp->getDALManager()->get('sysMileagesPostgrePDO____');
        $resultSet = $DAL->mileagesTradebackDdList($params);
        return $resultSet['resultSet'];
    }
    
       /**
     * Function to fill text on user interface layer
     * @param array $params
     * @return array 
    */
    public function mileagesWarrantyDdList($params = array()) {
        $DAL = $this->slimApp->getDALManager()->get('sysMileagesPostgrePDO____');
        $resultSet = $DAL->mileagesWarrantyDdList($params);
        return $resultSet['resultSet'];
    }
    
    /**
     * Function to fill datagrid on user interface layer
     * @param array | null $params
     * @return array
     */
    public function fillMileagesGridx ($params = array()) { 
        $DAL = $this->slimApp->getDALManager()->get('sysMileagesPostgrePDO____');
        $resultSet = $DAL->fillMileagesGridx($params);  
        return $resultSet['resultSet'];
    }
    
    /**
     * Function to get datagrid row count on user interface layer
     * @param array | null $params
     * @return array
     */
    public function fillMileagesGridxRtl($params = array()) {
        $DAL = $this->slimApp->getDALManager()->get('sysMileagesPostgrePDO____');
        $resultSet = $DAL->fillMileagesGridxRtl($params);  
        return $resultSet['resultSet'];
    }
    
    /**
     * public key / private key and value update function
     * @param array | null $params
     * @return array
     */
    public function makeActiveOrPassive($params = array()) {
        $DAL = $this->slimApp->getDALManager()->get('sysMileagesPostgrePDO____');
        return $DAL->makeActiveOrPassive($params);
    }
    
    /**
     * Data delete action function
     * @param array | null $params
     * @return array
     */
    public function deletedAct($params = array()) {
        $DAL = $this->slimApp->getDALManager()->get('sysMileagesPostgrePDO____');
        return $DAL->deletedAct($params);
    }
    
    /**
     * Data insert action function
     * @param array | null $params
     * @return array
     */
    public function insertAct($params = array()) {
        $DAL = $this->slimApp->getDALManager()->get('sysMileagesPostgrePDO____');
        return $DAL->insertAct($params);
    }
    
    /**
     * Data update action function
     * @param array | null $params
     * @return array
     */
    public function updateAct($params = array()) {
        $DAL = $this->slimApp->getDALManager()->get('sysMileagesPostgrePDO____');
        return $DAL->updateAct($params);
    }
     
}

