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
class SysAclRoles extends \BLL\BLLSlim {

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
        $DAL = $this->slimApp->getDALManager()->get('sysAclRolesPostgrePDO');
        return $DAL->insert($params);
    }

    /**
     * Data update function
     * @param array $params
     * @return array
     */
    public function update($params = array()) {
        $DAL = $this->slimApp->getDALManager()->get('sysAclRolesPostgrePDO');
        return $DAL->update($params);
    }

    /**
     * Data delete function
     * @param array $params
     * @return array
     */
    public function delete($params = array()) {
        $DAL = $this->slimApp->getDALManager()->get('sysAclRolesPostgrePDO');
        return $DAL->delete($params);
    }

    /**
     * get all data
     * @param array $params
     * @return array
     */
    public function getAll($params = array()) {
        $DAL = $this->slimApp->getDALManager()->get('sysAclRolesPostgrePDO');
        return $DAL->getAll($params);
    }

    /**
     * Function to fill datagrid on user interface layer
     * @param array | null $params
     * @return array
     */
    public function fillGrid($params = array()) {

        $DAL = $this->slimApp->getDALManager()->get('sysAclRolesPostgrePDO');
        $resultSet = $DAL->fillGrid($params);
        return $resultSet['resultSet'];
    }

    /**
     * Function to get datagrid row count on user interface layer
     * @param array $params
     * @return array
     */
    public function fillGridRowTotalCount($params = array()) {
        $DAL = $this->slimApp->getDALManager()->get('sysAclRolesPostgrePDO');
        $resultSet = $DAL->fillGridRowTotalCount($params);
        return $resultSet['resultSet'];
    }

    /**
     *  
     * @param array  $params
     * @return array
     */
    public function fillComboBoxMainRoles($params = array()) {
        $DAL = $this->slimApp->getDALManager()->get('sysAclRolesPostgrePDO');
        $resultSet = $DAL->fillComboBoxMainRoles($params);
        return $resultSet['resultSet'];
    }

    /**
     * Function to fill text on user interface layer
     * @param array $params
     * @return array
     */
    public function fillFullRolesDdList($params = array()) {

        $DAL = $this->slimApp->getDALManager()->get('sysAclRolesPostgrePDO');
        $resultSet = $DAL->fillFullRolesDdList($params);
        return $resultSet['resultSet'];
    }

    /**
     * Data update function   
     * @param array $params
     * @return array
     */
    public function updateChild($params = array()) {
        $DAL = $this->slimApp->getDALManager()->get('sysAclRolesPostgrePDO');
        return $DAL->updateChild($params);
    }
    
        /**
     * Function to fill text on user interface layer
     * @param array $params
     * @return array
     */
    public function fillComboBoxRoles($params = array()) {
        $DAL = $this->slimApp->getDALManager()->get('sysAclRolesPostgrePDO');
        $resultSet = $DAL->fillComboBoxRoles($params);
        return $resultSet['resultSet'];
    }
    
    /**
     * Function to fill text on user interface layer
     * @param array $params
     * @return array
     */
    public function fillRolesTree ($params = array()) {        
        $DAL = $this->slimApp->getDALManager()->get('sysAclRolesPostgrePDO');
        $resultSet = $DAL->fillRolesTree($params);  
        return $resultSet['resultSet'];
    }
    
   /**
     * Function to fill datagrid on user interface layer
     * @param array | null $params
     * @return array
     */
    public function fillRolesPropertiesList ($params = array()) {        
        $DAL = $this->slimApp->getDALManager()->get('sysAclRolesPostgrePDO');
        $resultSet = $DAL->fillRolesPropertiesList($params);  
        return $resultSet['resultSet'];
    }
    
    /**
     * Function to get datagrid row count on user interface layer
     * @param array $params
     * @return array
     */
    public function fillRolesPropertiesListRtc($params = array()) {
        $DAL = $this->slimApp->getDALManager()->get('sysAclRolesPostgrePDO');
        $resultSet = $DAL->fillRolesPropertiesListRtc($params);  
        return $resultSet['resultSet'];
    }    
    /**
     * public key / private key and value update function
     * @param array | null $params
     * @return array
     */
    public function makeActiveOrPassive($params = array()) {
        $DAL = $this->slimApp->getDALManager()->get('sysAclRolesPostgrePDO');
        return $DAL->makeActiveOrPassive($params);
    } 
     
   /**
     * Function to fill text on user interface layer
     * @param array $params
     * @return array
     */
    public function fillConsultantRolesDdlist($params = array()) {
        $DAL = $this->slimApp->getDALManager()->get('sysAclRolesPostgrePDO');
        $resultSet = $DAL->FillConsultantRolesDdlist($params);
        return $resultSet['resultSet'];
    }
    
   /**
     * Function to fill text on user interface layer
     * @param array $params
     * @return array
     */
    public function fillRolesDdlist($params = array()) {
        $DAL = $this->slimApp->getDALManager()->get('sysAclRolesPostgrePDO');
        $resultSet = $DAL->fillRolesDdlist($params);
        return $resultSet['resultSet'];
    }
     
   /**
     * Function to fill text on user interface layer
     * @param array $params
     * @return array
     */
    public function fillClusterRolesDdlist($params = array()) {
        $DAL = $this->slimApp->getDALManager()->get('sysAclRolesPostgrePDO');
        $resultSet = $DAL->fillClusterRolesDdlist($params);
        return $resultSet['resultSet'];
    }
}
