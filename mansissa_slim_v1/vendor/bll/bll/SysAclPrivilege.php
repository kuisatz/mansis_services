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
class SysAclPrivilege extends \BLL\BLLSlim{
    
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
        $DAL = $this->slimApp->getDALManager()->get('sysAclPrivilegePostgrePDO');
        return $DAL->insert($params);
    }
    
    /**
     * Data update function
     * @param array $params
     * @return array
     */
    public function update($params = array()) {
        $DAL = $this->slimApp->getDALManager()->get('sysAclPrivilegePostgrePDO');
        return $DAL->update($params);
    }
    
    /**
     * Data delete function
     * @param array $params
     * @return array
     */
    public function delete($params = array()) {
        $DAL = $this->slimApp->getDALManager()->get('sysAclPrivilegePostgrePDO');
        return $DAL->delete($params);
    }

    /**
     * get all data
     * @param array $params
     * @return array
     */
    public function getAll($params = array()) {
        $DAL = $this->slimApp->getDALManager()->get('sysAclPrivilegePostgrePDO');
        return $DAL->getAll($params);
    }
    
    /**
     * Function to fill datagrid on user interface layer
     * @param array | null $params
     * @return array
     */
    public function fillGrid ($params = array()) {
        
        $DAL = $this->slimApp->getDALManager()->get('sysAclPrivilegePostgrePDO');
        $resultSet = $DAL->fillGrid($params);  
        return $resultSet['resultSet'];
    }
    
    /**
     * Function to get datagrid row count on user interface layer
     * @param array $params
     * @return array
     */
    public function fillGridRowTotalCount($params = array()) {
        $DAL = $this->slimApp->getDALManager()->get('sysAclPrivilegePostgrePDO');
        $resultSet = $DAL->fillGridRowTotalCount($params);  
        return $resultSet['resultSet'];
    }
 
     
    /**
     * Function to fill text on user interface layer
     * @param array $params
     * @return array
     */
    public function fillComboBoxFullPrivilege ($params = array()) {
        
        $DAL = $this->slimApp->getDALManager()->get('sysAclPrivilegePostgrePDO');
        $resultSet = $DAL->fillComboBoxFullPrivilege($params);  
        return $resultSet['resultSet'];
    }
     
    /**
     * Function to fill datagrid on user interface layer
     * @param array | null $params
     * @return array
     */
    public function fillPrivilegesList ($params = array()) {        
        $DAL = $this->slimApp->getDALManager()->get('sysAclPrivilegePostgrePDO');
        $resultSet = $DAL->fillPrivilegesList($params);  
        return $resultSet['resultSet'];
    }
    
    /**
     * Function to get datagrid row count on user interface layer
     * @param array $params
     * @return array
     */
    public function fillPrivilegesListRtc($params = array()) {
        $DAL = $this->slimApp->getDALManager()->get('sysAclPrivilegePostgrePDO');
        $resultSet = $DAL->fillPrivilegesListRtc($params);  
        return $resultSet['resultSet'];
    }     
    public function makeActiveOrPassive($params = array()) {
        $DAL = $this->slimApp->getDALManager()->get('sysAclPrivilegePostgrePDO');
        return $DAL->makeActiveOrPassive($params);
    } 
    
    
    /**
     * Function to fill text on user interface layer
     * @param array $params
     * @return array
     */
    public function fillResourceGroups($params = array()) {
        $DAL = $this->slimApp->getDALManager()->get('sysAclPrivilegePostgrePDO');        
         if (isset($params['parent_id']) && ($params['parent_id'] == 0))  { 
            $resultSet = $DAL->fillResourceGroups($params);
        } else {            
            if (isset($params['state']) && ($params['state'] == "closed") && 
                isset($params['last_node']) && ($params['last_node'] == "true") &&   
                isset($params['machine']) && $params['machine'] == "false" )  
            {            
                $resultSet = $DAL->fillResourceGroupsPrivileges($params);
            } else {                        
                $resultSet = $DAL->fillResourceGroups($params);                
            }
        }        
        return $resultSet['resultSet'];
    }
    /**
     * Function to fill text on user interface layer
     * @param array $params
     * @return array
     */
    public function fillPrivilegesOfRoles($params = array()) {
        $DAL = $this->slimApp->getDALManager()->get('sysAclPrivilegePostgrePDO');
        $resultSet = $DAL->fillPrivilegesOfRoles($params);
        return $resultSet['resultSet'];
    }
        /**
     * Function to fill text on user interface layer
     * @param array $params
     * @return array
     */
    public function fillNotInPrivilegesOfRoles($params = array()) {
        $DAL = $this->slimApp->getDALManager()->get('sysAclPrivilegePostgrePDO');
        $resultSet = $DAL->fillNotInPrivilegesOfRoles($params);
        return $resultSet['resultSet'];
    }
    /**
     * Function to fill text on user interface layer
     * @param array $params
     * @return array
     */
    public function fillPrivilegesOfRolesDdList($params = array()) {
        $DAL = $this->slimApp->getDALManager()->get('sysAclPrivilegePostgrePDO');
        $resultSet = $DAL->fillPrivilegesOfRolesDdList($params);
        return $resultSet['resultSet'];
    }
     
    /**
     * Function to fill datagrid on user interface layer
     * @param array | null $params
     * @return array
    */
    public function fillPrivilegesOfRolesList($params = array()) {        
        $DAL = $this->slimApp->getDALManager()->get('sysAclPrivilegePostgrePDO');
        $resultSet = $DAL->fillPrivilegesOfRolesList($params);  
        return $resultSet['resultSet'];
    }
    
    /**
     * Function to get datagrid row count on user interface layer
     * @param array $params
     * @return array
     */
    public function fillPrivilegesOfRolesListRtc($params = array()) {
        $DAL = $this->slimApp->getDALManager()->get('sysAclPrivilegePostgrePDO');
        $resultSet = $DAL->fillPrivilegesOfRolesListRtc($params);  
        return $resultSet['resultSet'];
    }    
    
}

