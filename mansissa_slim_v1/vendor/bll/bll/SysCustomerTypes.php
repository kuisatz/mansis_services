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
class SysCustomerTypes extends \BLL\BLLSlim{
    
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
        $DAL = $this->slimApp->getDALManager()->get('sysCustomerTypesPostgrePDO');
        return $DAL->insert($params);
    }
    
    /**
     * Data update function 
     * @param array  $params
     * @return array
     */
    public function update( $params = array()) {
        $DAL = $this->slimApp->getDALManager()->get('sysCustomerTypesPostgrePDO');
        return $DAL->update( $params);
    }
    
    /**
     * Data delete function
     * @param array  $params
     * @return array
     */
    public function delete( $params = array()) {
        $DAL = $this->slimApp->getDALManager()->get('sysCustomerTypesPostgrePDO');
        return $DAL->delete($params);
    }

    /**
     * get all data
     * @param array  $params
     * @return array
     */
    public function getAll($params = array()) {
        $DAL = $this->slimApp->getDALManager()->get('sysCustomerTypesPostgrePDO');
        return $DAL->getAll($params);
    }
    
    /**
     * Function to fill datagrid on user interface layer
     * @param array | null $params
     * @return array
     */
    public function fillGrid ($params = array()) {
        
        $DAL = $this->slimApp->getDALManager()->get('sysCustomerTypesPostgrePDO');
        $resultSet = $DAL->fillGrid($params);  
        return $resultSet['resultSet'];
    }
    
    /**
     * Function to get datagrid row count on user interface layer
     * @param array | null $params
     * @return array
     */
    public function fillGridRowTotalCount($params = array()) {
        $DAL = $this->slimApp->getDALManager()->get('sysCustomerTypesPostgrePDO');
        $resultSet = $DAL->fillGridRowTotalCount($params);  
        return $resultSet['resultSet'];
    }
   
     
    /**
     * Function to fill text on user interface layer
     * @param array $params
     * @return array
     *  örnek olarak  bırakıldı
    */
    public function fillLanguageDdList($params = array()) {
        $DAL = $this->slimApp->getDALManager()->get('sysCustomerTypesPostgrePDO');
        $resultSet = $DAL->fillLanguageDdList($params);
        return $resultSet['resultSet'];
    }
        
    /**
     * Function to fill text on user interface layer
     * @param array $params
     * @return array 
    */
    public function customerTypesDdList($params = array()) {
        $DAL = $this->slimApp->getDALManager()->get('sysCustomerTypesPostgrePDO');
        $resultSet = $DAL->customerTypesDdList($params);
        return $resultSet['resultSet'];
    }
     
    /**
     * Function to fill datagrid on user interface layer
     * @param array | null $params
     * @return array
     */
    public function fillCustomerTypesGridx ($params = array()) { 
        $DAL = $this->slimApp->getDALManager()->get('sysCustomerTypesPostgrePDO');
        $resultSet = $DAL->fillCustomerTypesGridx($params);  
        return $resultSet['resultSet'];
    }
    
      /**
     * Function to get datagrid row count on user interface layer
     * @param array | null $params
     * @return array
     */
    public function fillCustomerTypesGridxRtl($params = array()) {
        $DAL = $this->slimApp->getDALManager()->get('sysCustomerTypesPostgrePDO');
        $resultSet = $DAL->fillCustomerTypesGridxRtl($params);  
        return $resultSet['resultSet'];
    }
    
    /**
     * public key / private key and value update function
     * @param array | null $params
     * @return array
     */
    public function makeActiveOrPassive($params = array()) {
        $DAL = $this->slimApp->getDALManager()->get('sysCustomerTypesPostgrePDO');
        return $DAL->makeActiveOrPassive($params);
    }
    
    /**
     * Data delete action function
     * @param array | null $params
     * @return array
     */
    public function deletedAct($params = array()) {
        $DAL = $this->slimApp->getDALManager()->get('sysCustomerTypesPostgrePDO');
        return $DAL->deletedAct($params);
    }
    
    /**
     * Data insert action function
     * @param array | null $params
     * @return array
     */
    public function insertAct($params = array()) {
        $DAL = $this->slimApp->getDALManager()->get('sysCustomerTypesPostgrePDO');
        return $DAL->insertAct($params);
    }
    
    /**
     * Data update action function
     * @param array | null $params
     * @return array
     */
    public function updateAct($params = array()) {
        $DAL = $this->slimApp->getDALManager()->get('sysCustomerTypesPostgrePDO');
        return $DAL->updateAct($params);
    }
    
     /**
     * Data update action function
     * @param array | null $params
     * @return array
     */
    public function updateActLng($params = array()) {
        $DAL = $this->slimApp->getDALManager()->get('sysCustomerTypesPostgrePDO');
        return $DAL->updateActLng($params);
    }
   
    
}

