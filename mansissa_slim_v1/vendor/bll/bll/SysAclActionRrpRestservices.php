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
class SysAclActionRrpRestservices extends \BLL\BLLSlim {

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
        $DAL = $this->slimApp->getDALManager()->get('sysAclActionRrpRestservicesPostgrePDO');
        return $DAL->insert($params);
    }

    /**
     * Data update function
     * @param array $params
     * @return array
     */
    public function update($params = array()) {
        $DAL = $this->slimApp->getDALManager()->get('sysAclActionRrpRestservicesPostgrePDO');
        return $DAL->update($params);
    }

    /**
     * Data delete function
     * @param array $params
     * @return array
     */
    public function delete($params = array()) {
        $DAL = $this->slimApp->getDALManager()->get('sysAclActionRrpRestservicesPostgrePDO');
        return $DAL->delete($params);
    }

    /**
     * get all data
     * @param array $params
     * @return array
     */
    public function getAll($params = array()) {
        $DAL = $this->slimApp->getDALManager()->get('sysAclActionRrpRestservicesPostgrePDO');
        return $DAL->getAll($params);
    }

    /**
     * Function to fill datagrid on user interface layer
     * @param array | null $params
     * @return array
     */
    public function fillGrid($params = array()) {
        $DAL = $this->slimApp->getDALManager()->get('sysAclActionRrpRestservicesPostgrePDO');
        $resultSet = $DAL->fillGrid($params);
        return $resultSet['resultSet'];
    }

    /**
     * Function to get datagrid row count on user interface layer
     * @param array $params
     * @return array
     */
    public function fillGridRowTotalCount($params = array()) {
        $DAL = $this->slimApp->getDALManager()->get('sysAclActionRrpRestservicesPostgrePDO');
        $resultSet = $DAL->fillGridRowTotalCount($params);
        return $resultSet['resultSet'];
    }
  
  
   /**
     * Function to fill datagrid on user interface layer
     * @param array | null $params
     * @return array
     */
    public function fillActionRrpRestServicesList ($params = array()) {        
        $DAL = $this->slimApp->getDALManager()->get('sysAclActionRrpRestservicesPostgrePDO');
        $resultSet = $DAL->fillActionRrpRestServicesList($params);  
        return $resultSet['resultSet'];
    }
    
    /**
     * Function to get datagrid row count on user interface layer
     * @param array $params
     * @return array
     */
    public function fillActionRrpRestServicesListRtc($params = array()) {
        $DAL = $this->slimApp->getDALManager()->get('sysAclActionRrpRestservicesPostgrePDO');
        $resultSet = $DAL->fillActionRrpRestServicesListRtc($params);  
        return $resultSet['resultSet'];
    }    
 
    /**
     * Function to fill text on user interface layer
     * @param array $params
     * @return array
     */
    public function fillActionRestServicesOfPrivileges($params = array()) {
        $DAL = $this->slimApp->getDALManager()->get('sysAclActionRrpRestservicesPostgrePDO');
        $resultSet = $DAL->fillActionRestServicesOfPrivileges($params);
        return $resultSet['resultSet'];
    }     
    
     /**
     * Function to fill datagrid on user interface layer
     * @param array | null $params
     * @return array
     */
    public function fillNotInActionRestServicesOfPrivileges ($params = array()) {        
        $DAL = $this->slimApp->getDALManager()->get('sysAclActionRrpRestservicesPostgrePDO');
        $resultSet = $DAL->fillNotInActionRestServicesOfPrivileges($params);  
        return $resultSet['resultSet'];
    }
    
    /**
     * Function to get datagrid row count on user interface layer
     * @param array $params
     * @return array
     */
    public function fillNotInActionRestServicesOfPrivilegesRtc($params = array()) {
        $DAL = $this->slimApp->getDALManager()->get('sysAclActionRrpRestservicesPostgrePDO');
        $resultSet = $DAL->fillNotInActionRestServicesOfPrivilegesRtc($params);  
        return $resultSet['resultSet'];
    }    
    
    /**
     * Function to fill text on user interface layer
     * @param array $params
     * @return array
     */
    public function fillNotInActionRestServicesOfPrivilegesTree($params = array()) {
        $DAL = $this->slimApp->getDALManager()->get('sysAclActionRrpRestservicesPostgrePDO');  
     // print_r($params);
         if (isset($params['parent_id']) && ($params['parent_id'] == 0))  { 
            $resultSet = $DAL->fillNotInActionServicesGroupsTree($params);
        } else {     
            $resultSet = $DAL->fillNotInActionRestServicesTree($params);
        }        
        return $resultSet['resultSet'];
    }
    
    
      /**
     * Function to fill text on user interface layer
     * @param array $params
     * @return array
     */
    public function fillActionRestServicesOfPrivilegesTree($params = array()) {
        $DAL = $this->slimApp->getDALManager()->get('sysAclActionRrpRestservicesPostgrePDO');  
     // print_r($params);
         if (isset($params['parent_id']) && ($params['parent_id'] == 0))  { 
            $resultSet = $DAL->fillActionRestServicesGroupsOfPrivilegesTree($params);
        } else {     
            $resultSet = $DAL->fillActionRestServicesOfPrivilegesTree($params);
        }        
        return $resultSet['resultSet'];
    }
    

}
