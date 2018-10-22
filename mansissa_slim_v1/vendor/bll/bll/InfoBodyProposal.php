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
class InfoBodyProposal  extends \BLL\BLLSlim{
    
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
        $DAL = $this->slimApp->getDALManager()->get('infoBodyProposalPostgrePDO');
        return $DAL->insert($params);
    }
    
    /**
     * Data update function 
     * @param array  $params
     * @return array
     */
    public function update( $params = array()) {
        $DAL = $this->slimApp->getDALManager()->get('infoBodyProposalPostgrePDO');
        return $DAL->update( $params);
    }
    
    /**
     * Data delete function
     * @param array  $params
     * @return array
     */
    public function delete( $params = array()) {
        $DAL = $this->slimApp->getDALManager()->get('infoBodyProposalPostgrePDO');
        return $DAL->delete($params);
    }

    /**
     * get all data
     * @param array  $params
     * @return array
     */
    public function getAll($params = array()) {
        $DAL = $this->slimApp->getDALManager()->get('infoBodyProposalPostgrePDO');
        return $DAL->getAll($params);
    }
    
    /**
     * Function to fill datagrid on user interface layer
     * @param array | null $params
     * @return array
     */
    public function fillGrid ($params = array()) { 
        $DAL = $this->slimApp->getDALManager()->get('infoBodyProposalPostgrePDO');
        $resultSet = $DAL->fillGrid($params);  
        return $resultSet['resultSet'];
    }
    
    /**
     * Function to get datagrid row count on user interface layer
     * @param array | null $params
     * @return array
     */
    public function fillGridRowTotalCount($params = array()) {
        $DAL = $this->slimApp->getDALManager()->get('infoBodyProposalPostgrePDO');
        $resultSet = $DAL->fillGridRowTotalCount($params);  
        return $resultSet['resultSet'];
    }
   
     
    /**
     * Function to fill text on user interface layer
     * @param array $params
     * @return array 
     * ornek için bırakıldı
    */
    public function customerBodyProposalDdList($params = array()) {
        $DAL = $this->slimApp->getDALManager()->get('infoBodyProposalPostgrePDO');
        $resultSet = $DAL->customerBodyProposalDdList($params);
        return $resultSet['resultSet'];
    }
     
      
    /**
     * Function to fill datagrid on user interface layer
     * @param array | null $params
     * @return array
     */
    public function fillCustomerBodyProposalGridx ($params = array()) { 
        $DAL = $this->slimApp->getDALManager()->get('infoBodyProposalPostgrePDO');
        $resultSet = $DAL->fillCustomerBodyProposalGridx($params);  
        return $resultSet['resultSet'];
    }
    
    /**
     * Function to get datagrid row count on user interface layer
     * @param array | null $params
     * @return array
     */
    public function fillCustomerBodyProposalGridxRtl($params = array()) {
        $DAL = $this->slimApp->getDALManager()->get('infoBodyProposalPostgrePDO');
        $resultSet = $DAL->fillCustomerBodyProposalGridxRtl($params);  
        return $resultSet['resultSet'];
    }
    
    /**
     * public key / private key and value update function
     * @param array | null $params
     * @return array
     */
    public function makeActiveOrPassive($params = array()) {
        $DAL = $this->slimApp->getDALManager()->get('infoBodyProposalPostgrePDO');
        return $DAL->makeActiveOrPassive($params);
    }
    
    /**
     * Data delete action function
     * @param array | null $params
     * @return array
     */
    public function deletedAct($params = array()) {
        $DAL = $this->slimApp->getDALManager()->get('infoBodyProposalPostgrePDO');
        return $DAL->deletedAct($params);
    }
    
    /**
     * Data insert action function
     * @param array | null $params
     * @return array
     */
    public function insertAct($params = array()) {
        $DAL = $this->slimApp->getDALManager()->get('infoBodyProposalPostgrePDO');
        return $DAL->insertAct($params);
    }
    
    /**
     * Data update action function
     * @param array | null $params
     * @return array
     */
    public function updateAct($params = array()) {
        $DAL = $this->slimApp->getDALManager()->get('infoBodyProposalPostgrePDO');
        return $DAL->updateAct($params);
    }
    
}

