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
class BlAdminActivationReport extends \BLL\BLLSlim{
    
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
        $DAL = $this->slimApp->getDALManager()->get('blAdminActivationReportPostgrePDO');
        return $DAL->insert($params);
    }
    
    /**
     * Data update function
     * @param array | null $params
     * @return array
     */
    public function update($params = array()) {
        $DAL = $this->slimApp->getDALManager()->get('blAdminActivationReportPostgrePDO');
        return $DAL->update($params);
    }
    
    /**
     * Data delete function
     * @param array | null $params
     * @return array
     */
    public function delete($params = array()) {
        $DAL = $this->slimApp->getDALManager()->get('blAdminActivationReportPostgrePDO');
        return $DAL->delete($params);
    }

    /**
     * get all data
     * @param array | null $params
     * @return array
     */
    public function getAll($params = array()) {
        $DAL = $this->slimApp->getDALManager()->get('blAdminActivationReportPostgrePDO');
        return $DAL->getAll($params);
    }
    
    
    /**
     *  
     * @param array$params
     * @return array
     */
    public function getConsultantOperation($params = array()) {
        $DAL = $this->slimApp->getDALManager()->get('blAdminActivationReportPostgrePDO');
        $resultSet = $DAL->getConsultantOperation($params);  
        return $resultSet;
    }
    
       /**
     * 
     * @param array$params
     * @return array
     */
    public function getAllConsultantFirmCount($params = array()) {
        $DAL = $this->slimApp->getDALManager()->get('blAdminActivationReportPostgrePDO');
        $resultSet = $DAL->getAllConsultantFirmCount($params);  
        return $resultSet;
    }
    
     /**
     *  
     * @param array$params
     * @return array
     */
    public function getUpDashBoardCount($params = array()) {
        $DAL = $this->slimApp->getDALManager()->get('blAdminActivationReportPostgrePDO');
        $resultSet = $DAL->getUpDashBoardCount($params);  
        return $resultSet;
    }
    
        
     /**
     *  
     * @param array$params
     * @return array
     */
    public function getDashBoardHighCharts($params = array()) {
        $DAL = $this->slimApp->getDALManager()->get('blAdminActivationReportPostgrePDO');
        $resultSet = $DAL->getDashBoardHighCharts($params);  
        return $resultSet;
    }
    
   
    
    
    
    
}

