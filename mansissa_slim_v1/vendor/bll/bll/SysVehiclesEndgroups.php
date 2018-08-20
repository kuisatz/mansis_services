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
class SysVehiclesEndgroups extends \BLL\BLLSlim{
    
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
        $DAL = $this->slimApp->getDALManager()->get('sysVehiclesEndgroupsOraPDO');
        return $DAL->insert($params);
    }
    
    /**
     * Data update function 
     * @param array  $params
     * @return array
     */
    public function update( $params = array()) {
        $DAL = $this->slimApp->getDALManager()->get('sysVehiclesEndgroupsOraPDO');
        return $DAL->update( $params);
    }
    
    /**
     * Data delete function
     * @param array  $params
     * @return array
     */
    public function delete( $params = array()) {
        $DAL = $this->slimApp->getDALManager()->get('sysVehiclesEndgroupsOraPDO');
        return $DAL->delete($params);
    }

    /**
     * get all data
     * @param array  $params
     * @return array
     */
    public function getAll($params = array()) {
        $DAL = $this->slimApp->getDALManager()->get('sysVehiclesEndgroupsOraPDO');
        return $DAL->getAll($params);
    }
    
    /**
     * Function to fill datagrid on user interface layer
     * @param array | null $params
     * @return array
     */
    public function fillGrid ($params = array()) {
        
        $DAL = $this->slimApp->getDALManager()->get('sysVehiclesEndgroupsOraPDO');
        $resultSet = $DAL->fillGrid($params);  
        return $resultSet['resultSet'];
    }
    
    /**
     * Function to get datagrid row count on user interface layer
     * @param array | null $params
     * @return array
     */
    public function fillGridRowTotalCount($params = array()) {
        $DAL = $this->slimApp->getDALManager()->get('sysVehiclesEndgroupsOraPDO');
        $resultSet = $DAL->fillGridRowTotalCount($params);  
        return $resultSet['resultSet'];
    }
   
     
    /**
     * Function to fill text on user interface layer
     * @param array $params
     * @return array 
    */
    public function vehiclesEndgroupsCostDdList($params = array()) {
        $DAL = $this->slimApp->getDALManager()->get('sysVehiclesEndgroupsOraPDO');
        $resultSet = $DAL->vehiclesEndgroupsCostDdList($params);
        return $resultSet['resultSet'];
    }
    
     /**
     * Function to fill text on user interface layer
     * @param array $params
     * @return array 
    */
    public function vehiclesEndgroupsBbDdList($params = array()) {
        $DAL = $this->slimApp->getDALManager()->get('sysVehiclesEndgroupsOraPDO');
        $resultSet = $DAL->vehiclesEndgroupsBbDdList($params);
        return $resultSet['resultSet'];
    }
    /**
     * Function to fill datagrid on user interface layer
     * @param array | null $params
     * @return array
     */
    public function fillVehiclesEndgroupsGridx ($params = array()) {        
        $DAL = $this->slimApp->getDALManager()->get('sysVehiclesEndgroupsOraPDO');
        $resultSet = $DAL->fillVehiclesEndgroupsGridx($params);  
        return $resultSet['resultSet'];
    }
    
    /**
     * Function to get datagrid row count on user interface layer
     * @param array | null $params
     * @return array
     */
    public function fillVehiclesEndgroupsGridxRtl($params = array()) {
        $DAL = $this->slimApp->getDALManager()->get('sysVehiclesEndgroupsOraPDO');
        $resultSet = $DAL->fillVehiclesEndgroupsGridxRtl($params);  
        return $resultSet['resultSet'];
    }
   
    
}

