<?php

/**
 *  Framework 
 *
 * @link       
 * @copyright Copyright (c) 2017
 * @license   
 */
namespace DAL\PDO\Oracle;

/**
 * Class using Zend\ServiceManager\FactoryInterface
 * created to be used by DAL MAnager
 * @
 * @author Okan CIRAN
 * @since 29.07.2018 
 */ 
class SysBranchAndDealersDeff extends \DAL\DalSlim {

    /** 
     * @author Okan CIRAN
     * @ sys_branch_and_dealers_deff tablosundan parametre olarak  gelen id kaydını siler. !!
     * @version v 1.0  29.07.2018
     * @param array $params
     * @return array   
     * @throws \PDOException
     */
    public function delete($params = array()) {
     try {
            $pdo = $this->slimApp->getServiceManager()->get('pgConnectFactory');
            $pdo->beginTransaction();
            $opUserId = InfoUsers::getUserId(array('pk' => $params['pk']));
            if (\Utill\Dal\Helper::haveRecord($opUserId)) {
                $opUserIdValue = $opUserId ['resultSet'][0]['user_id'];                
                $statement = $pdo->prepare(" 
                UPDATE sys_branch_and_dealers_deff
                SET deleted= 1, active = 1,
                     op_user_id = " . intval($opUserIdValue) . "     
                WHERE id = ".  intval($params['id'])  );            
                $update = $statement->execute();
                $afterRows = $statement->rowCount();
                $errorInfo = $statement->errorInfo();
                if ($errorInfo[0] != "00000" && $errorInfo[1] != NULL && $errorInfo[2] != NULL)
                    throw new \PDOException($errorInfo[0]);
                $pdo->commit();
                return array("found" => true, "errorInfo" => $errorInfo, "affectedRowsCount" => $afterRows);
            } else {
                $errorInfo = '23502';  /// 23502  not_null_violation
                $pdo->rollback();
                return array("found" => false, "errorInfo" => $errorInfo, "resultSet" => '');
            }
        } catch (\PDOException $e /* Exception $e */) {
            $pdo->rollback();
            return array("found" => false, "errorInfo" => $e->getMessage());
        }
    } 

    /**
     * @author Okan CIRAN
     * @ sys_branch_and_dealers_deff tablosundaki tüm kayıtları getirir.  !!
     * @version v 1.0  29.07.2018  
     * @param array $params
     * @return array
     * @throws \PDOException 
     */
    public function getAll($params = array()) {
        try {
            $pdo = $this->slimApp->getServiceManager()->get('pgConnectFactory');
            $languageId = NULL;
            $languageIdValue = 647;
            if ((isset($params['language_code']) && $params['language_code'] != "")) {                
                $languageId = SysLanguage::getLanguageId(array('language_code' => $params['language_code']));
                if (\Utill\Dal\Helper::haveRecord($languageId)) {
                    $languageIdValue = $languageId ['resultSet'][0]['id'];                    
                }
            }  
            $statement = $pdo->prepare("              
               SELECT 
                        a.id,
                        COALESCE(NULLIF(ax.name, ''), a.name_eng) AS menu_type_name,
                        a.name_eng AS menu_type_name_eng,	
			COALESCE(NULLIF(ax.description, ''), a.description_eng) AS description,
                        a.description_eng,
                        a.deleted,
			COALESCE(NULLIF(sd15x.description , ''), sd15.description_eng) AS state_deleted,
                        a.active,
			COALESCE(NULLIF(sd16x.description , ''), sd16.description_eng) AS state_active,
			COALESCE(NULLIF(lx.id, NULL), 385) AS language_id,
			COALESCE(NULLIF(lx.language, ''), l.language_eng) AS language_name,			 
                        a.op_user_id,
                        u.username AS op_user_name
                FROM sys_branch_and_dealers_deff a
                INNER JOIN sys_language l ON l.id = a.language_id AND l.deleted =0 AND l.active = 0 
                LEFT JOIN sys_language lx ON lx.id = ".intval($languageIdValue)." AND lx.deleted =0 AND lx.active =0
                INNER JOIN sys_specific_definitions sd15 ON sd15.main_group = 15 AND sd15.first_group= a.deleted AND sd15.language_id = a.language_id AND sd15.deleted = 0 
                INNER JOIN sys_specific_definitions sd16 ON sd16.main_group = 16 AND sd16.first_group= a.active AND sd16.language_id = a.language_id AND sd16.deleted = 0
                INNER JOIN info_users u ON u.id = a.op_user_id    
                LEFT JOIN sys_specific_definitions sd15x ON (sd15x.id = sd15.id OR sd15x.language_parent_id = sd15.id) AND sd15x.language_id =lx.id  AND sd15x.deleted =0 AND sd15x.active =0 
                LEFT JOIN sys_specific_definitions sd16x ON (sd16x.id = sd16.id OR sd16x.language_parent_id = sd16.id)AND sd16x.language_id = lx.id  AND sd16x.deleted = 0 AND sd16x.active = 0
                LEFT JOIN sys_branch_and_dealers_deff ax ON (ax.id = a.id OR ax.language_parent_id = a.id) AND ax.deleted =0 AND ax.active =0 AND lx.id = ax.language_id
                
                ORDER BY menu_type_name

                                 ");

            $statement->execute();
            $result = $statement->fetchAll(\PDO::FETCH_ASSOC);
            $errorInfo = $statement->errorInfo();
            if ($errorInfo[0] != "00000" && $errorInfo[1] != NULL && $errorInfo[2] != NULL)
                throw new \PDOException($errorInfo[0]);
            return array("found" => true, "errorInfo" => $errorInfo, "resultSet" => $result);
        } catch (\PDOException $e /* Exception $e */) {
            return array("found" => false, "errorInfo" => $e->getMessage());
        }
    }

    /**
     * @author Okan CIRAN
     * @ sys_branch_and_dealers_deff tablosuna yeni bir kayıt oluşturur.  !!
     * @version v 1.0  29.07.2018
     * @param type $params
     * @return array
     * @throws \PDOException
     */
    public function insert($params = array()) {
        try {
            $pdo = $this->slimApp->getServiceManager()->get('pgConnectFactory');
            $pdo->beginTransaction();
            $opUserId = InfoUsers::getUserId(array('pk' => $params['pk']));
            if (\Utill\Dal\Helper::haveRecord($opUserId)) {
                $opUserIdValue = $opUserId ['resultSet'][0]['user_id'];
                $languageId = NULL;
                $languageIdValue = 647;
                if ((isset($params['language_code']) && $params['language_code'] != "")) {
                    $languageId = SysLanguage::getLanguageId(array('language_code' => $params['language_code']));
                    if (\Utill\Dal\Helper::haveRecord($languageId)) {
                        $languageIdValue = $languageId ['resultSet'][0]['id'];
                    }
                }
                $kontrol = $this->haveRecords(array('name' => $params['name']));
                if (!\Utill\Dal\Helper::haveRecord($kontrol)) {

                $sql = "
                INSERT INTO sys_branch_and_dealers_deff(
                        name, 
                        name_eng, 
                        description, 
                        description_eng, 
                        language_id,
                        op_user_id
                        )
                VALUES (
                        '".$params['name']."',
                        '".$params['name_eng']."',
                        '".$params['description']."',
                        '".$params['description_eng']."',
                        ".intval($languageIdValue).",
                        ".intval($opUserIdValue)."
                                             )   ";
                    $statement = $pdo->prepare($sql);                            
                //   echo debugPDO($sql, $params);
                    $result = $statement->execute();                  
                    $insertID = $pdo->lastInsertId('sys_branch_and_dealers_deff_id_seq');
                    $errorInfo = $statement->errorInfo();  
                    if ($errorInfo[0] != "00000" && $errorInfo[1] != NULL && $errorInfo[2] != NULL)
                        throw new \PDOException($errorInfo[0]);
                    $pdo->commit();
                    return array("found" => true, "errorInfo" => $errorInfo, "lastInsertId" => $insertID);
                } else {
                    $errorInfo = '23505';
                    $errorInfoColumn = 'name';
                    $pdo->rollback();
                    return array("found" => false, "errorInfo" => $errorInfo, "resultSet" => '', "errorInfoColumn" => $errorInfoColumn);
                }
            } else {
                $errorInfo = '23502';   // 23502  not_null_violation
                $errorInfoColumn = 'pk';
                $pdo->rollback();
                return array("found" => false, "errorInfo" => $errorInfo, "resultSet" => '', "errorInfoColumn" => $errorInfoColumn);
            }
        } catch (\PDOException $e /* Exception $e */) {
            $pdo->rollback();
            return array("found" => false, "errorInfo" => $e->getMessage());
        }
    }

    /**
     * @author Okan CIRAN
     * @ sys_branch_and_dealers_deff tablosunda property_name daha önce kaydedilmiş mi ?  
     * @version v 1.0 13.03.2016
     * @param type $params
     * @return array
     * @throws \PDOException
     */
    public function haveRecords($params = array()) {
        try {
            $pdo = $this->slimApp->getServiceManager()->get('pgConnectFactory');
            $addSql = "";
            if (isset($params['id'])) {
                $addSql = " AND a.id != " . intval($params['id']) . " ";
            }
            $sql = "  
            SELECT  
                a.name ,
                '" . $params['name'] . "' AS value, 
                LOWER(a.name) = LOWER(TRIM('" . $params['name'] . "')) AS control,
                CONCAT(a.name, ' daha önce kayıt edilmiş. Lütfen Kontrol Ediniz !!!' ) AS message
            FROM sys_branch_and_dealers_deff  a                          
            WHERE 
                LOWER(REPLACE(name,' ','')) = LOWER(REPLACE('" . $params['name'] . "',' ',''))
                  " . $addSql . " 
                AND a.deleted =0    
                               ";
            $statement = $pdo->prepare($sql);
         // echo debugPDO($sql, $params);
            $statement->execute();
            $result = $statement->fetchAll(\PDO::FETCH_ASSOC);
            $errorInfo = $statement->errorInfo();
            if ($errorInfo[0] != "00000" && $errorInfo[1] != NULL && $errorInfo[2] != NULL)
                throw new \PDOException($errorInfo[0]);
            return array("found" => true, "errorInfo" => $errorInfo, "resultSet" => $result);
        } catch (\PDOException $e /* Exception $e */) {
            return array("found" => false, "errorInfo" => $e->getMessage());
        }
    }

    /**
     * @author Okan CIRAN
     * sys_branch_and_dealers_deff tablosuna parametre olarak gelen id deki kaydın bilgilerini günceller   !!
     * @version v 1.0  29.07.2018
     * @param type $params
     * @return array
     * @throws \PDOException
     */
    public function update($params = array()) {
        try {
            $pdo = $this->slimApp->getServiceManager()->get('pgConnectFactory');
            $pdo->beginTransaction();
            $opUserId = InfoUsers::getUserId(array('pk' => $params['pk']));
            if (\Utill\Dal\Helper::haveRecord($opUserId)) {
                $opUserIdValue = $opUserId ['resultSet'][0]['user_id'];
                $languageId = NULL;
                $languageIdValue = 647;
                if ((isset($params['language_code']) && $params['language_code'] != "")) {
                    $languageId = SysLanguage::getLanguageId(array('language_code' => $params['language_code']));
                    if (\Utill\Dal\Helper::haveRecord($languageId)) {
                        $languageIdValue = $languageId ['resultSet'][0]['id'];
                    }
                }
                $kontrol = $this->haveRecords(array('id' => $params['id'], 'name' => $params['name']));
                if (!\Utill\Dal\Helper::haveRecord($kontrol)) {
                    $sql = "
                    UPDATE sys_branch_and_dealers_deff
                    SET 
                        name= '".$params['name']."',  
                        name_eng=  '".$params['name_eng']."',  
                        description= '".$params['description']."',  
                        description_eng=  '".$params['description_eng']."',                         
                        language_id = ".intval($languageIdValue).",
                        op_user_id = ".intval($opUserIdValue)."
                    WHERE id = " . intval($params['id']);
                    $statement = $pdo->prepare($sql);
                    //echo debugPDO($sql, $params);
                    $update = $statement->execute();
                    $affectedRows = $statement->rowCount();
                    $errorInfo = $statement->errorInfo();
                    if ($errorInfo[0] != "00000" && $errorInfo[1] != NULL && $errorInfo[2] != NULL)
                        throw new \PDOException($errorInfo[0]);
                    $pdo->commit();
                    return array("found" => true, "errorInfo" => $errorInfo, "affectedRowsCount" => $affectedRows);
                } else {
                    // 23505 	unique_violation
                    $errorInfo = '23505';
                    $errorInfoColumn = 'name';
                    $pdo->rollback();
                    return array("found" => false, "errorInfo" => $errorInfo, "resultSet" => '', "errorInfoColumn" => $errorInfoColumn);
                }
            } else {
                $errorInfo = '23502';   // 23502  not_null_violation
                $errorInfoColumn = 'pk';
                $pdo->rollback();
                return array("found" => false, "errorInfo" => $errorInfo, "resultSet" => '', "errorInfoColumn" => $errorInfoColumn);
            }
        } catch (\PDOException $e /* Exception $e */) {
            $pdo->rollback();
            return array("found" => false, "errorInfo" => $e->getMessage());
        }
    }

    /**
     * @author Okan CIRAN
     * @ Gridi doldurmak için sys_branch_and_dealers_deff tablosundan kayıtları döndürür !!
     * @version v 1.0  29.07.2018
     * @param array | null $args
     * @return array
     * @throws \PDOException
     */
    public function fillGrid($args = array()) {
        if (isset($args['page']) && $args['page'] != "" && isset($args['rows']) && $args['rows'] != "") {
            $offset = ((intval($args['page']) - 1) * intval($args['rows']));
            $limit = intval($args['rows']);
        } else {
            $limit = 10;
            $offset = 0;
        }

        $sortArr = array();
        $orderArr = array();
        if (isset($args['sort']) && $args['sort'] != "") {
            $sort = trim($args['sort']);
            $sortArr = explode(",", $sort);
            if (count($sortArr) === 1)
                $sort = trim($args['sort']);
        } else {
            $sort = "menu_type_name ";
        }

        if (isset($args['order']) && $args['order'] != "") {
            $order = trim($args['order']);
            $orderArr = explode(",", $order);
            //print_r($orderArr);
            if (count($orderArr) === 1)
                $order = trim($args['order']);
        } else {
            $order = "ASC";
        }

        $languageId = NULL;
        $languageIdValue = 647;
        if ((isset($args['language_code']) && $args['language_code'] != "")) {                
            $languageId = SysLanguage::getLanguageId(array('language_code' => $args['language_code']));
            if (\Utill\Dal\Helper::haveRecord($languageId)) {
                $languageIdValue = $languageId ['resultSet'][0]['id'];                    
            }
        }  
        try {
            $pdo = $this->slimApp->getServiceManager()->get('pgConnectFactory');
            $sql = "
                SELECT 
                        a.id,
                        COALESCE(NULLIF(ax.name, ''), a.name_eng) AS menu_type_name,
                        a.name_eng AS menu_type_name_eng,	
			COALESCE(NULLIF(ax.description, ''), a.description_eng) AS description,
                        a.description_eng,
                        a.deleted,
			COALESCE(NULLIF(sd15x.description , ''), sd15.description_eng) AS state_deleted,
                        a.active,
			COALESCE(NULLIF(sd16x.description , ''), sd16.description_eng) AS state_active,
			COALESCE(NULLIF(lx.id, NULL), 385) AS language_id,
			COALESCE(NULLIF(lx.language, ''), l.language_eng) AS language_name,			 
                        a.op_user_id,
                        u.username AS op_user_name
                FROM sys_branch_and_dealers_deff a
                INNER JOIN sys_language l ON l.id = a.language_id AND l.deleted =0 AND l.active = 0 
                LEFT JOIN sys_language lx ON lx.id = ".intval($languageIdValue)." AND lx.deleted =0 AND lx.active =0
                INNER JOIN sys_specific_definitions sd15 ON sd15.main_group = 15 AND sd15.first_group= a.deleted AND sd15.language_id = a.language_id AND sd15.deleted = 0 
                INNER JOIN sys_specific_definitions sd16 ON sd16.main_group = 16 AND sd16.first_group= a.active AND sd16.language_id = a.language_id AND sd16.deleted = 0
                INNER JOIN info_users u ON u.id = a.op_user_id    
                LEFT JOIN sys_specific_definitions sd15x ON (sd15x.id = sd15.id OR sd15x.language_parent_id = sd15.id) AND sd15x.language_id =lx.id  AND sd15x.deleted =0 AND sd15x.active =0 
                LEFT JOIN sys_specific_definitions sd16x ON (sd16x.id = sd16.id OR sd16x.language_parent_id = sd16.id)AND sd16x.language_id = lx.id  AND sd16x.deleted = 0 AND sd16x.active = 0
                LEFT JOIN sys_branch_and_dealers_deff ax ON (ax.id = a.id OR ax.language_parent_id = a.id) AND ax.deleted =0 AND ax.active =0 AND lx.id = ax.language_id
                
                WHERE a.deleted =0 AND a.language_parent_id =0  
                ORDER BY    " . $sort . " "
                    . "" . $order . " "
                    . "LIMIT " . $pdo->quote($limit) . " "
                    . "OFFSET " . $pdo->quote($offset) . " ";
            $statement = $pdo->prepare($sql);
            $parameters = array(
                'sort' => $sort,
                'order' => $order,
                'limit' => $pdo->quote($limit),
                'offset' => $pdo->quote($offset),
            );
            //   echo debugPDO($sql, $parameters);
            $statement->execute();
            $result = $statement->fetchAll(\PDO::FETCH_ASSOC);
            $errorInfo = $statement->errorInfo();
            if ($errorInfo[0] != "00000" && $errorInfo[1] != NULL && $errorInfo[2] != NULL)
                throw new \PDOException($errorInfo[0]);
            return array("found" => true, "errorInfo" => $errorInfo, "resultSet" => $result);
        } catch (\PDOException $e /* Exception $e */) {
            //$debugSQLParams = $statement->debugDumpParams();
            return array("found" => false, "errorInfo" => $e->getMessage()/* , 'debug' => $debugSQLParams */);
        }
    }

    /**
     * @author Okan CIRAN
     * @ Gridi doldurmak için sys_branch_and_dealers_deff tablosundan çekilen kayıtlarının kaç tane olduğunu döndürür   !!
     * @version v 1.0  29.07.2018
     * @param array | null $args
     * @return array
     * @throws \PDOException
     */
    public function fillGridRowTotalCount($params = array()) {
        try {
            $pdo = $this->slimApp->getServiceManager()->get('pgConnectFactory');
            
            $sql = "
                SELECT 
                     COUNT(a.id) AS COUNT 
                FROM sys_branch_and_dealers_deff a
                INNER JOIN sys_language l ON l.id = a.language_id AND l.deleted =0 AND l.active = 0                 
                INNER JOIN sys_specific_definitions sd15 ON sd15.main_group = 15 AND sd15.first_group= a.deleted AND sd15.language_id = a.language_id AND sd15.deleted = 0 
                INNER JOIN sys_specific_definitions sd16 ON sd16.main_group = 16 AND sd16.first_group= a.active AND sd16.language_id = a.language_id AND sd16.deleted = 0
                INNER JOIN info_users u ON u.id = a.op_user_id    
                
                WHERE a.deleted =0 AND a.language_parent_id =0  
                    ";
            $statement = $pdo->prepare($sql);
            // echo debugPDO($sql, $params);
            $statement->execute();
            $result = $statement->fetchAll(\PDO::FETCH_ASSOC);
            $errorInfo = $statement->errorInfo();
            if ($errorInfo[0] != "00000" && $errorInfo[1] != NULL && $errorInfo[2] != NULL)
                throw new \PDOException($errorInfo[0]);
            return array("found" => true, "errorInfo" => $errorInfo, "resultSet" => $result);
        } catch (\PDOException $e /* Exception $e */) {
            //$debugSQLParams = $statement->debugDumpParams();
            return array("found" => false, "errorInfo" => $e->getMessage()/* , 'debug' => $debugSQLParams */);
        }
    }
    
    /*
     * @author Okan CIRAN
     * @ sys_branch_and_dealers_deff tablosundan parametre olarak  gelen id kaydın aktifliğini
     *  0(aktif) ise 1 , 1 (pasif) ise 0  yapar. !!
      * @version v 1.0  29.07.2018
     * @param type $params
     * @return array
     * @throws \PDOException
     */
    public function makeActiveOrPassive($params = array()) {
        try {
            $pdo = $this->slimApp->getServiceManager()->get('pgConnectFactory');
            $pdo->beginTransaction();
            $opUserId = InfoUsers::getUserId(array('pk' => $params['pk']));
            if (\Utill\Dal\Helper::haveRecord($opUserId)) {
                $opUserIdValue = $opUserId ['resultSet'][0]['user_id'];
                if (isset($params['id']) && $params['id'] != "") {

                    $sql = "                 
                UPDATE sys_branch_and_dealers_deff
                SET active = (  SELECT   
                                CASE active
                                    WHEN 0 THEN 1
                                    ELSE 0
                                END activex
                                FROM sys_branch_and_dealers_deff
                                WHERE id = " . intval($params['id']) . "
                ),
                op_user_id = " . intval($opUserIdValue) . "
                WHERE id = " . intval($params['id']);
                    $statement = $pdo->prepare($sql);
                    //  echo debugPDO($sql, $params);
                    $update = $statement->execute();
                    $afterRows = $statement->rowCount();
                    $errorInfo = $statement->errorInfo();
                    if ($errorInfo[0] != "00000" && $errorInfo[1] != NULL && $errorInfo[2] != NULL)
                        throw new \PDOException($errorInfo[0]);
                }
                $pdo->commit();
                return array("found" => true, "errorInfo" => $errorInfo, "affectedRowsCount" => $afterRows);
            } else {
                $errorInfo = '23502';   // 23502  not_null_violation
                $errorInfoColumn = 'pk';
                $pdo->rollback();
                return array("found" => false, "errorInfo" => $errorInfo, "resultSet" => '', "errorInfoColumn" => $errorInfoColumn);
            }
        } catch (\PDOException $e /* Exception $e */) {
            $pdo->rollback();
            return array("found" => false, "errorInfo" => $e->getMessage());
        }
    }
                            
    /** 
     * @author Okan CIRAN
     * @ bayi isimleri dropdown ya da tree ye doldurmak için sys_branch_and_dealers_deff tablosundan kayıtları döndürür !!
     * @version v 1.0  11.08.2018
     * @param array | null $args
     * @return array
     * @throws \PDOException 
     */
    public function  branchesDealersDeffDdList($params = array()) {
        try {
            $pdo = $this->slimApp->getServiceManager()->get('pgConnectFactory');         
                            
            $statement = $pdo->prepare("    
                SELECT                    
                    a.act_parent_id AS id, 	
                    a.name AS name,  
                    a.name_eng AS name_eng,
                    0 as parent_id,
                    a.active,
                    0 AS state_type   
                FROM sys_branch_and_dealers_deff a    
                WHERE   
                    a.deleted = 0 AND
                    a.active =0   
                ORDER BY  a.priority , a.name  
                                 ");
            $statement->execute();
            $result = $statement->fetchAll(\PDO::FETCH_ASSOC); 
            $errorInfo = $statement->errorInfo();
            if ($errorInfo[0] != "00000" && $errorInfo[1] != NULL && $errorInfo[2] != NULL)
                throw new \PDOException($errorInfo[0]);
            return array("found" => true, "errorInfo" => $errorInfo, "resultSet" => $result);
        } catch (\PDOException $e /* Exception $e */) {           
            return array("found" => false, "errorInfo" => $e->getMessage());
        }
    }
                            
    /** 
     * @author Okan CIRAN
     * @ bayi isimlerini grid formatında döndürür !! ana tablo  sys_branch_and_dealers_deff 
     * @version v 1.0  15.08.2018
     * @param array | null $args 
     * @return array
     * @throws \PDOException  
     */  
    public function fillBranchesDealersDeffGridx($params = array()) {
        try {
            if (isset($params['page']) && $params['page'] != "" && isset($params['rows']) && $params['rows'] != "") {
                $offset = ((intval($params['page']) - 1) * intval($params['rows']));
                $limit = intval($params['rows']);
            } else {
                $limit = 10;
                $offset = 0;
            }

            $sortArr = array();
            $orderArr = array();
            $addSql = NULL;
            if (isset($params['sort']) && $params['sort'] != "") {
                $sort = trim($params['sort']);
                $sortArr = explode(",", $sort);
                if (count($sortArr) === 1)
                    $sort = trim($params['sort']);
            } else {
                $sort = " COALESCE(NULLIF(ax.name, ''), a.name_eng)  ";
            }

            if (isset($params['order']) && $params['order'] != "") {
                $order = trim($params['order']);
                $orderArr = explode(",", $order);
                //print_r($orderArr);
                if (count($orderArr) === 1)
                    $order = trim($params['order']);
            } else {
                $order = "ASC";
            }
            $sorguStr = null;                            
            if (isset($params['filterRules'])) {
                $filterRules = trim($params['filterRules']);
                $jsonFilter = json_decode($filterRules, true);
              
                $sorguExpression = null;
                foreach ($jsonFilter as $std) {
                    if ($std['value'] != null) {
                        switch (trim($std['field'])) {
                            case 'name':
                                $sorguExpression = ' ILIKE \'%' . $std['value'] . '%\' ';
                                $sorguStr.=" AND COALESCE(NULLIF(ax.name, ''), a.name_eng)" . $sorguExpression . ' ';
                              
                                break;
                            case 'country_name':
                                $sorguExpression = ' ILIKE \'%' . $std['value'] . '%\'  ';
                                $sorguStr.=" AND c.name" . $sorguExpression . ' ';

                                break; 
                            case 'region_name':
                                $sorguExpression = ' ILIKE \'%' . $std['value'] . '%\'  ';
                                $sorguStr.=" AND d.name" . $sorguExpression . ' ';

                                break; 
                             case 'city_name':
                                $sorguExpression = ' ILIKE \'%' . $std['value'] . '%\'  ';
                                $sorguStr.=" AND e.name" . $sorguExpression . ' ';

                                break; 
                             case 'departman_name':
                                $sorguExpression = ' ILIKE \'%' . $std['value'] . '%\'  ';
                                $sorguStr.=" AND f.name" . $sorguExpression . ' ';

                                break; 
                             case 'branch_no':
                                $sorguExpression = ' ILIKE \'%' . $std['value'] . '%\' ';
                                $sorguStr.=" AND a.branch_no" . $sorguExpression . ' ';
                              
                                break;
                            case 'address1':
                                $sorguExpression = ' ILIKE \'%' . $std['value'] . '%\' ';
                                $sorguStr.=" AND a.address1" . $sorguExpression . ' ';
                              
                                break;
                             case 'address2':
                                $sorguExpression = ' ILIKE \'%' . $std['value'] . '%\' ';
                                $sorguStr.=" AND a.address2" . $sorguExpression . ' ';
                              
                                break;
                             case 'address3':
                                $sorguExpression = ' ILIKE \'%' . $std['value'] . '%\' ';
                                $sorguStr.=" AND a.address3" . $sorguExpression . ' ';
                              
                                break;
                            case 'op_user_name':
                                $sorguExpression = ' ILIKE \'%' . $std['value'] . '%\'  ';
                                $sorguStr.=" AND u.username" . $sorguExpression . ' ';

                                break;
                            case 'state_active':
                                $sorguExpression = ' ILIKE \'%' . $std['value'] . '%\'  ';
                                $sorguStr.=" AND COALESCE(NULLIF(sd16x.description, ''), sd16.description_eng)" . $sorguExpression . ' ';

                                break;
                            
                            default:
                                break;
                        }
                    }
                }
            } else {
                $sorguStr = null;
                $filterRules = "";
            }
            $sorguStr = rtrim($sorguStr, "AND "); 

            $pdo = $this->slimApp->getServiceManager()->get('pgConnectFactory');       
                            
            $languageIdValue = 385;
            if (isset($params['language_code']) && $params['language_code'] != "") { 
                $languageCodeParams = array('language_code' => $params['language_code'],);
                $languageId = $this->slimApp-> getBLLManager()->get('languageIdBLL');  
                $languageIdsArray= $languageId->getLanguageId($languageCodeParams);
                if (\Utill\Dal\Helper::haveRecord($languageIdsArray)) { 
                     $languageIdValue = $languageIdsArray ['resultSet'][0]['id']; 
                }    
            }    
            if (isset($params['LanguageID']) && $params['LanguageID'] != "") {
                $languageIdValue = $params['LanguageID'];
            }  
            $countryID =0 ;
            if (isset($params['CountryID']) && $params['CountryID'] != "") {
                $countryID = $params['CountryID'];
                $addSql .="  a.country_id  = " . intval($countryID). "  AND  " ; 
            }               
            $countryRegionID =0 ;
            if (isset($params['CountryRegionID']) && $params['CountryRegionID'] != "") {
                $countryRegionID = $params['CountryRegionID'];
                $addSql .="  a.country_region_id  = " . intval($countryRegionID). "  AND  " ; 
            }  
            $cityID =0 ;
            if (isset($params['CityID']) && $params['CityID'] != "") {
                $cityID = $params['CityID'];
                $addSql .="  a.city_id  = " . intval($cityID). "  AND  " ; 
            }  
            $sisDepartmentID =0 ;
            if (isset($params['SisDepartmentID']) && $params['SisDepartmentID'] != "") {
                $sisDepartmentID = $params['SisDepartmentID'];
                $addSql .="  a.sis_department_id  = " . intval($sisDepartmentID). "  AND  " ; 
            }  
                $sql = "  
                SELECT 
                    a.id, 
                    a.act_parent_id as apid,  
                    a.name  AS name,
                    a.branch_no,
                    a.address1,
                    a.address2,
                    a.address3,
                    a.country_id, 
                    c.name as country_name,
                    a.country_region_id, 
                    d.name as region_name,
                    a.city_id, 
                    e.name as city_name,
                    a.sis_department_id,
                    f.name as departman_name,  
                    a.active,
                    COALESCE(NULLIF(sd16x.description, ''), sd16.description_eng) AS state_active,
                   /* a.deleted,
                    COALESCE(NULLIF(sd15x.description, ''), sd15.description_eng) AS state_deleted,*/
                    a.op_user_id,
                    u.username AS op_user_name,  
                    a.s_date date_saved,
                    a.c_date date_modified,
                    /* a.priority, */ 
                    COALESCE(NULLIF(lx.id, NULL), 385) AS language_id, 
                    lx.language_main_code language_code, 
                    COALESCE(NULLIF(lx.language, ''), 'en') AS language_name
                FROM sys_branch_and_dealers_deff a                    
                INNER JOIN sys_language l ON l.id = 385 AND l.show_it =0
                LEFT JOIN sys_language lx ON lx.id =" . intval($languageIdValue) . "  AND lx.show_it =0  
                INNER JOIN info_users u ON u.id = a.op_user_id 
                /*----*/   
                left JOIN sys_countrys c ON c.act_parent_id = a.country_id AND c.show_it = 0 and c.language_id = l.id 
                left JOIN sys_country_regions d ON d.act_parent_id = a.country_region_id AND d.show_it = 0  and d.language_id = l.id
                left JOIN sys_city e ON e.act_parent_id = a.city_id AND e.show_it = 0 and e.language_id = l.id 
                left JOIN sys_sis_departments f ON f.act_parent_id = a.sis_department_id AND f.show_it = 0 and f.language_id = l.id
                /*----*/   
                /* INNER JOIN sys_specific_definitions sd15 ON sd15.main_group = 15 AND sd15.first_group= a.deleted AND sd15.deleted =0 AND sd15.active =0 AND sd15.language_parent_id =0 */
                INNER JOIN sys_specific_definitions sd16 ON sd16.main_group = 16 AND sd16.first_group= a.active AND sd16.deleted = 0 AND sd16.active = 0 AND sd16.language_id =l.id
                /**/
                /*  LEFT JOIN sys_specific_definitions sd15x ON sd15x.language_id =lx.id AND (sd15x.id = sd15.id OR sd15x.language_parent_id = sd15.id) AND sd15x.deleted =0 AND sd15x.active =0  */
                LEFT JOIN sys_specific_definitions sd16x ON sd16x.language_id = lx.id AND (sd16x.id = sd16.id OR sd16x.language_parent_id = sd16.id) AND sd16x.deleted = 0 AND sd16x.active = 0
                    
                WHERE  
                    a.deleted =0 AND
                    a.show_it =0   
                     
                " . $addSql . "
                " . $sorguStr . " 
               /*  ORDER BY    " . $sort . " "
                    . "" . $order . " "
                    . "LIMIT " . $pdo->quote($limit) . " "
                    . "OFFSET " . $pdo->quote($offset) . "  
               */      
                  ";
            $statement = $pdo->prepare($sql);
            $parameters = array(
                'sort' => $sort,
                'order' => $order,
                'limit' => $pdo->quote($limit),
                'offset' => $pdo->quote($offset),
            ); 
                $statement = $pdo->prepare($sql);
                //  echo debugPDO($sql, $parameters);                
                $statement->execute();
                $result = $statement->fetchAll(\PDO::FETCH_ASSOC);
                $errorInfo = $statement->errorInfo(); 
              //  $ColumnCount = $statement->columnCount();
                if ($errorInfo[0] != "00000" && $errorInfo[1] != NULL && $errorInfo[2] != NULL)
                    throw new \PDOException($errorInfo[0]);
                return array("found" => true, "errorInfo" => $errorInfo, "resultSet" => $result);
                            
        } catch (\PDOException $e /* Exception $e */) {
            //$debugSQLParams = $statement->debugDumpParams();
            return array("found" => false, "errorInfo" => $e->getMessage()/* , 'debug' => $debugSQLParams */);
        }
    }
    
    /** 
     * @author Okan CIRAN
     * @ bayi isimlerini grid formatında gösterilirken kaç kayıt olduğunu döndürür !! ana tablo  sys_branch_and_dealers_deff 
     * @version v 1.0  15.08.2018
     * @param array | null $args
     * @return array
     * @throws \PDOException  
     */  
    public function fillBranchesDealersDeffGridxRtl($params = array()) {
        try {             
            $sorguStr = null;    
            $addSql = null;
            if (isset($params['filterRules'])) {
                $filterRules = trim($params['filterRules']);
                $jsonFilter = json_decode($filterRules, true);
              
                $sorguExpression = null;
                foreach ($jsonFilter as $std) {
                    if ($std['value'] != null) {
                        switch (trim($std['field'])) {
                           case 'name':
                                $sorguExpression = ' ILIKE \'%' . $std['value'] . '%\' ';
                                $sorguStr.=" AND COALESCE(NULLIF(ax.name, ''), a.name_eng)" . $sorguExpression . ' ';
                              
                                break;
                            case 'country_name':
                                $sorguExpression = ' ILIKE \'%' . $std['value'] . '%\'  ';
                                $sorguStr.=" AND c.name" . $sorguExpression . ' ';

                                break; 
                            case 'region_name':
                                $sorguExpression = ' ILIKE \'%' . $std['value'] . '%\'  ';
                                $sorguStr.=" AND d.name" . $sorguExpression . ' ';

                                break; 
                             case 'city_name':
                                $sorguExpression = ' ILIKE \'%' . $std['value'] . '%\'  ';
                                $sorguStr.=" AND e.name" . $sorguExpression . ' ';

                                break; 
                             case 'departman_name':
                                $sorguExpression = ' ILIKE \'%' . $std['value'] . '%\'  ';
                                $sorguStr.=" AND f.name" . $sorguExpression . ' ';

                                break; 
                             case 'branch_no':
                                $sorguExpression = ' ILIKE \'%' . $std['value'] . '%\' ';
                                $sorguStr.=" AND a.branch_no" . $sorguExpression . ' ';
                              
                                break;
                             case 'address1':
                                $sorguExpression = ' ILIKE \'%' . $std['value'] . '%\' ';
                                $sorguStr.=" AND a.address1" . $sorguExpression . ' ';
                              
                                break;
                             case 'address2':
                                $sorguExpression = ' ILIKE \'%' . $std['value'] . '%\' ';
                                $sorguStr.=" AND a.address2" . $sorguExpression . ' ';
                              
                                break;
                             case 'address3':
                                $sorguExpression = ' ILIKE \'%' . $std['value'] . '%\' ';
                                $sorguStr.=" AND a.address3" . $sorguExpression . ' ';
                              
                                break;
                            case 'op_user_name':
                                $sorguExpression = ' ILIKE \'%' . $std['value'] . '%\'  ';
                                $sorguStr.=" AND u.username" . $sorguExpression . ' ';

                                break;
                            case 'state_active':
                                $sorguExpression = ' ILIKE \'%' . $std['value'] . '%\'  ';
                                $sorguStr.=" AND COALESCE(NULLIF(sd16x.description, ''), sd16.description_eng)" . $sorguExpression . ' ';

                                break;
                            
                            default:
                                break;
                        }
                    }
                }
            } else {
                $sorguStr = null;
                $filterRules = "";
            }
            $sorguStr = rtrim($sorguStr, "AND "); 

            $pdo = $this->slimApp->getServiceManager()->get('pgConnectFactory');       
                            
            $languageIdValue = 385;
            if (isset($params['language_code']) && $params['language_code'] != "") { 
                $languageCodeParams = array('language_code' => $params['language_code'],);
                $languageId = $this->slimApp-> getBLLManager()->get('languageIdBLL');  
                $languageIdsArray= $languageId->getLanguageId($languageCodeParams);
                if (\Utill\Dal\Helper::haveRecord($languageIdsArray)) { 
                     $languageIdValue = $languageIdsArray ['resultSet'][0]['id']; 
                }    
            }    
            if (isset($params['LanguageID']) && $params['LanguageID'] != "") {
                $languageIdValue = $params['LanguageID'];
            }  
            $countryID =0 ;
            if (isset($params['CountryID']) && $params['CountryID'] != "") {
                $countryID = $params['CountryID'];
                $addSql .="  a.country_id  = " . intval($countryID). "  AND  " ; 
            }               
            $countryRegionID =0 ;
            if (isset($params['CountryRegionID']) && $params['CountryRegionID'] != "") {
                $countryRegionID = $params['CountryRegionID'];
                $addSql .="  a.country_region_id  = " . intval($countryRegionID). "  AND  " ; 
            }  
            $cityID =0 ;
            if (isset($params['CityID']) && $params['CityID'] != "") {
                $cityID = $params['CityID'];
                $addSql .="  a.city_id  = " . intval($cityID). "  AND  " ; 
            }  
            $sisDepartmentID =0 ;
            if (isset($params['SisDepartmentID']) && $params['SisDepartmentID'] != "") {
                $sisDepartmentID = $params['SisDepartmentID'];
                $addSql .="  a.sis_department_id  = " . intval($sisDepartmentID). "  AND  " ; 
            }  

                $sql = "
                   SELECT COUNT(asdx.id) count FROM ( 
                        SELECT 
                            a.id, 
                            a.name  AS name,
                            a.branch_no,
                            a.address1,
                            a.address2,
                            a.address3, 
                            c.name as country_name, 
                            d.name as region_name, 
                            e.name as city_name, 
                            f.name as departman_name,  
                            COALESCE(NULLIF(sd16x.description, ''), sd16.description_eng) AS state_active, 
                            u.username AS op_user_name 
                        FROM sys_branch_and_dealers_deff a                    
                        INNER JOIN sys_language l ON l.id = 385 AND l.show_it =0
                        LEFT JOIN sys_language lx ON lx.id =" . intval($languageIdValue) . "  AND lx.show_it =0  
                        INNER JOIN info_users u ON u.id = a.op_user_id 
                        /*----*/   
                        left JOIN sys_countrys c ON c.act_parent_id = a.country_id AND c.show_it = 0 and c.language_id = l.id 
                        left JOIN sys_country_regions d ON d.act_parent_id = a.country_region_id AND d.show_it = 0  and d.language_id = l.id
                        left JOIN sys_city e ON e.act_parent_id = a.city_id AND e.show_it = 0 and e.language_id = l.id 
                        left JOIN sys_sis_departments f ON f.act_parent_id = a.sis_department_id AND f.show_it = 0 and f.language_id = l.id
                        /*----*/   
                        /* INNER JOIN sys_specific_definitions sd15 ON sd15.main_group = 15 AND sd15.first_group= a.deleted AND sd15.deleted =0 AND sd15.active =0 AND sd15.language_parent_id =0 */
                        INNER JOIN sys_specific_definitions sd16 ON sd16.main_group = 16 AND sd16.first_group= a.active AND sd16.deleted = 0 AND sd16.active = 0 AND sd16.language_id =l.id
                        /**/
                        /*  LEFT JOIN sys_specific_definitions sd15x ON sd15x.language_id =lx.id AND (sd15x.id = sd15.id OR sd15x.language_parent_id = sd15.id) AND sd15x.deleted =0 AND sd15x.active =0  */
                        LEFT JOIN sys_specific_definitions sd16x ON sd16x.language_id = lx.id AND (sd16x.id = sd16.id OR sd16x.language_parent_id = sd16.id) AND sd16x.deleted = 0 AND sd16x.active = 0

                        WHERE  
                            a.deleted =0 AND
                            a.show_it =0  
                         " . $addSql . "
                         " . $sorguStr . " 
                    ) asdx
                        
                         "; 
                $statement = $pdo->prepare($sql);
                //  echo debugPDO($sql, $parameters);                
                $statement->execute();
                $result = $statement->fetchAll(\PDO::FETCH_ASSOC);
                $errorInfo = $statement->errorInfo(); 
                if ($errorInfo[0] != "00000" && $errorInfo[1] != NULL && $errorInfo[2] != NULL)
                    throw new \PDOException($errorInfo[0]);
                return array("found" => true, "errorInfo" => $errorInfo, "resultSet" => $result);
                            
        } catch (\PDOException $e /* Exception $e */) {
            //$debugSQLParams = $statement->debugDumpParams();
            return array("found" => false, "errorInfo" => $e->getMessage()/* , 'debug' => $debugSQLParams */);
        }
    }
    
    /**
     * @author Okan CIRAN
     * @ sys_branch_and_dealers_deff tablosundan parametre olarak  gelen id kaydını active ve show_it alanlarını 1 yapar. !!
     * @version v 1.0  24.08.2018
     * @param type $params
     * @return array
     * @throws \PDOException
     */
    public function makePassive($params = array()) {
        try {
            $pdo = $this->slimApp->getServiceManager()->get('pgConnectFactory'); 
            $statement = $pdo->prepare(" 
                UPDATE sys_branch_and_dealers_deff
                SET                         
                    c_date =  timezone('Europe/Istanbul'::text, ('now'::text)::timestamp(0) with time zone) ,                     
                    active = 1 ,
                    show_it =1 
                WHERE id = :id or language_parent_id = :id");
            $statement->bindValue(':id', $params['id'], \PDO::PARAM_INT);
            $update = $statement->execute();
            $afterRows = $statement->rowCount();
            $errorInfo = $statement->errorInfo();
            if ($errorInfo[0] != "00000" && $errorInfo[1] != NULL && $errorInfo[2] != NULL)
                throw new \PDOException($errorInfo[0]); 
            return array("found" => true, "errorInfo" => $errorInfo, "affectedRowsCount" => $afterRows);
        } catch (\PDOException $e /* Exception $e */) { 
            return array("found" => false, "errorInfo" => $e->getMessage());
        }
    }
    
    /**
     * @author Okan CIRAN     
     * @ sys_branch_and_dealers_deff tablosundan parametre olarak  gelen id kaydın active veshow_it  alanını 1 yapar ve 
     * yeni yeni kayıt oluşturarak deleted ve active = 1  show_it =0 olarak  yeni kayıt yapar. !  
     * @version v 1.0  24.08.2018
     * @param array | null $args
     * @return array
     * @throws \PDOException
     */
    public function deletedAct($params = array()) {
        $pdo = $this->slimApp->getServiceManager()->get('pgConnectFactory');
        try { 
            $pdo->beginTransaction();
            $opUserIdParams = array('pk' => $params['pk'],);
            $opUserIdArray = $this->slimApp->getBLLManager()->get('opUserIdBLL');
            $opUserId = $opUserIdArray->getUserId($opUserIdParams);
            if (\Utill\Dal\Helper::haveRecord($opUserId)) {
                $opUserIdValue = $opUserId ['resultSet'][0]['user_id'];
                $opUserRoleIdValue = $opUserId ['resultSet'][0]['role_id'];

                $this->makePassive(array('id' => $params['id']));

                $statementInsert = $pdo->prepare(" 
                    INSERT INTO sys_branch_and_dealers_deff (
                        name,
                        branch_no,
                        address1,
                        address2,
                        address3,
                        postalcode,
                        country_id,
                        country_region_id,
                        city_id,
                        sis_department_id, 
                         
                        active,
                        deleted,
                        op_user_id,
                        act_parent_id,
                        show_it
                        )
                    SELECT
                        name,
                        branch_no,
                        address1,
                        address2,
                        address3,
                        postalcode,
                        country_id,
                        country_region_id,
                        city_id,
                        sis_department_id, 
                         
                        1 AS active,  
                        1 AS deleted, 
                        " . intval($opUserIdValue) . " AS op_user_id, 
                        act_parent_id,
                        0 AS show_it 
                    FROM sys_branch_and_dealers_deff 
                    WHERE id  =" . intval($params['id']) . " OR language_parent_id = " . intval($params['id']) . "  
                    )");

                $insertAct = $statementInsert->execute();
                $affectedRows = $statementInsert->rowCount(); 
                $errorInfo = $statementInsert->errorInfo();

                $pdo->commit();
                return array("found" => true, "errorInfo" => $errorInfo, "affectedRowsCount" => $affectedRows);
            } else {
                $errorInfo = '23502';  /// 23502  not_null_violation
                $errorInfoColumn = 'pk';
                $pdo->rollback();
                return array("found" => false, "errorInfo" => $errorInfo, "resultSet" => '', "errorInfoColumn" => $errorInfoColumn);
            }
        } catch (\PDOException $e /* Exception $e */) {
            $pdo->rollback();
            return array("found" => false, "errorInfo" => $e->getMessage());
        }
    }

    /**
     * @author Okan CIRAN
     * @ sys_branch_and_dealers_deff tablosuna yeni bir kayıt oluşturur.  !! 
     * @version v 1.0  26.08.2018
     * @param type $params
     * @return array
     * @throws \PDOException
     */
    public function insertAct($params = array()) {
        try {
            $pdo = $this->slimApp->getServiceManager()->get('pgConnectFactory');
            $pdo->beginTransaction();
                           
            $errorInfo[0] = "99999"; 
            $name = null;
            if ((isset($params['Name']) && $params['Name'] != "")) {
                $name = $params['Name'];
            } else {
                throw new \PDOException($errorInfo[0]);
            }
            $branchNo = null;
            if ((isset($params['BranchNo']) && $params['BranchNo'] != "")) {
                $branchNo = $params['BranchNo'];
            } else {
                throw new \PDOException($errorInfo[0]);
            }
            $address1 = null;
            if ((isset($params['Address1']) && $params['Address1'] != "")) {
                $address1 = $params['Address1'];
            } else {
                throw new \PDOException($errorInfo[0]);
            }
            $address2 = null;
            if ((isset($params['Address2']) && $params['Address2'] != "")) {
                $address2 = $params['Address2'];
            } else {
                throw new \PDOException($errorInfo[0]);
            }
            $address3 = null;
            if ((isset($params['Address3']) && $params['Address3'] != "")) {
                $address3 = $params['Address3'];
            } else {
                throw new \PDOException($errorInfo[0]);
            }
            $postalCode = null;
            if ((isset($params['PostalCode']) && $params['PostalCode'] != "")) {
                $postalCode = $params['PostalCode'];
            } else {
                throw new \PDOException($errorInfo[0]);
            }
            $countryId = -1111;
            if ((isset($params['CountryId']) && $params['CountryId'] != "")) {
                $countryId = intval($params['CountryId']);
            } else {
                throw new \PDOException($errorInfo[0]);
            }
            $countryRegionId = -1111;
            if ((isset($params['CountryRegionId']) && $params['CountryRegionId'] != "")) {
                $countryRegionId = intval($params['CountryRegionId']);
            } else {
                throw new \PDOException($errorInfo[0]);
            }
            $cityId = -1111;
            if ((isset($params['CityId']) && $params['CityId'] != "")) {
                $cityId = intval($params['CityId']);
            } else {
                throw new \PDOException($errorInfo[0]);
            }
            $sisDepartmentId = -1111;
            if ((isset($params['SisDepartmentId']) && $params['SisDepartmentId'] != "")) {
                $sisDepartmentId = intval($params['SisDepartmentId']);
            } else {
                throw new \PDOException($errorInfo[0]);
            }
                           
            $opUserId = InfoUsers::getUserId(array('pk' => $params['pk']));
            if (\Utill\Dal\Helper::haveRecord($opUserId)) {
                $opUserIdValue = $opUserId ['resultSet'][0]['user_id'];

                $kontrol = $this->haveRecords(
                        array( 
                            'name' => $name, 
                            'branch_no' => $branchNo, 
                ));
                if (!\Utill\Dal\Helper::haveRecord($kontrol)) {
                    $sql = "
                    INSERT INTO sys_branch_and_dealers_deff(
                            name,
                            branch_no,
                            address1,
                            address2,
                            address3,
                            postalcode,
                            country_id,
                            country_region_id,
                            city_id,
                            sis_department_id, 

                            op_user_id,
                            act_parent_id  
                            )
                    VALUES (
                            '" . $name . "',
                            '" . $address1 . "',
                            '" . $address2 . "',
                            '" . $address3 . "',
                            '" . $postalCode . "', 
                            '" . $branchNo . "',
                            " . intval($countryId) . ",
                            " . intval($countryRegionId) . ",
                            " . intval($cityId) . ",
                            " . intval($sisDepartmentId) . ",

                            " . intval($opUserIdValue) . ",
                           (SELECT last_value FROM sys_branch_and_dealers_deff_id_seq)
                                                 )   ";
                    $statement = $pdo->prepare($sql);
                    //   echo debugPDO($sql, $params);
                    $result = $statement->execute();
                    $errorInfo = $statement->errorInfo();
                    if ($errorInfo[0] != "00000" && $errorInfo[1] != NULL && $errorInfo[2] != NULL)
                        throw new \PDOException($errorInfo[0]);
                    $insertID = $pdo->lastInsertId('sys_branch_and_dealers_deff_id_seq');
                           
                    $pdo->commit();
                    return array("found" => true, "errorInfo" => $errorInfo, "lastInsertId" => $insertID);
                } else {
                    $errorInfo = '23505';
                    $errorInfoColumn = 'name';
                    $pdo->rollback();
                    return array("found" => false, "errorInfo" => $errorInfo, "resultSet" => '', "errorInfoColumn" => $errorInfoColumn);
                }
            } else {
                $errorInfo = '23502';   // 23502  not_null_violation
                $errorInfoColumn = 'pk';
                $pdo->rollback();
                return array("found" => false, "errorInfo" => $errorInfo, "resultSet" => '', "errorInfoColumn" => $errorInfoColumn);
            }
        } catch (\PDOException $e /* Exception $e */) {
            // $pdo->rollback();
            return array("found" => false, "errorInfo" => $e->getMessage());
        }
    }

 
    
}
