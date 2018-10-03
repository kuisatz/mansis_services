<?php

/**
 *  Framework 
 *
 * @link       
 * @copyright Copyright (c) 2017
 * @license   
 */
namespace DAL\PDO\Postresql;

/**
 * Class using Zend\ServiceManager\FactoryInterface
 * created to be used by DAL MAnager
 * @
 * @author Okan CIRAN
 * @since 29.07.2018 
 */ 
class SysBuybackMatrix extends \DAL\DalSlim {

    /**
     * @author Okan CIRAN
     * @ sys_buyback_matrix tablosundan parametre olarak  gelen id kaydını siler. !!
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
                UPDATE sys_buyback_matrix
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
     * @ sys_buyback_matrix tablosundaki tüm kayıtları getirir.  !!
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
                FROM sys_buyback_matrix a
                INNER JOIN sys_language l ON l.id = a.language_id AND l.deleted =0 AND l.active = 0 
                LEFT JOIN sys_language lx ON lx.id = ".intval($languageIdValue)." AND lx.deleted =0 AND lx.active =0
                INNER JOIN sys_specific_definitions sd15 ON sd15.main_group = 15 AND sd15.first_group= a.deleted AND sd15.language_id = a.language_id AND sd15.deleted = 0 
                INNER JOIN sys_specific_definitions sd16 ON sd16.main_group = 16 AND sd16.first_group= a.active AND sd16.language_id = a.language_id AND sd16.deleted = 0
                INNER JOIN info_users u ON u.id = a.op_user_id    
                LEFT JOIN sys_specific_definitions sd15x ON (sd15x.id = sd15.id OR sd15x.language_parent_id = sd15.id) AND sd15x.language_id =lx.id  AND sd15x.deleted =0 AND sd15x.active =0 
                LEFT JOIN sys_specific_definitions sd16x ON (sd16x.id = sd16.id OR sd16x.language_parent_id = sd16.id)AND sd16x.language_id = lx.id  AND sd16x.deleted = 0 AND sd16x.active = 0
                LEFT JOIN sys_buyback_matrix ax ON (ax.id = a.id OR ax.language_parent_id = a.id) AND ax.deleted =0 AND ax.active =0 AND lx.id = ax.language_id
                
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
     * @ sys_buyback_matrix tablosuna yeni bir kayıt oluşturur.  !!
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
                INSERT INTO sys_buyback_matrix(
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
                    $insertID = $pdo->lastInsertId('sys_buyback_matrix_id_seq');
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
     * @ sys_buyback_matrix tablosunda property_name daha önce kaydedilmiş mi ?  
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
                '' AS name ,
                1 AS value, 
                True AS control,
                CONCAT(  ' daha önce kayıt edilmiş. Lütfen Kontrol Ediniz !!!' ) AS message
            FROM sys_buyback_matrix  a                          
            WHERE 
                a.contract_type_id = " . intval($params['contract_type_id']) . "  AND 
                a.model_id = " . intval($params['model_id']) . "  AND 
                a.terrain_id = " . intval($params['terrain_id']) . "  AND 
                a.month_id = " . intval($params['month_id']) . "  AND 
                a.mileage_id = " . intval($params['mileage_id']) . "    
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
     * sys_buyback_matrix tablosuna parametre olarak gelen id deki kaydın bilgilerini günceller   !!
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
                    UPDATE sys_buyback_matrix
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
     * @ Gridi doldurmak için sys_buyback_matrix tablosundan kayıtları döndürür !!
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
                FROM sys_buyback_matrix a
                INNER JOIN sys_language l ON l.id = a.language_id AND l.deleted =0 AND l.active = 0 
                LEFT JOIN sys_language lx ON lx.id = ".intval($languageIdValue)." AND lx.deleted =0 AND lx.active =0
                INNER JOIN sys_specific_definitions sd15 ON sd15.main_group = 15 AND sd15.first_group= a.deleted AND sd15.language_id = a.language_id AND sd15.deleted = 0 
                INNER JOIN sys_specific_definitions sd16 ON sd16.main_group = 16 AND sd16.first_group= a.active AND sd16.language_id = a.language_id AND sd16.deleted = 0
                INNER JOIN info_users u ON u.id = a.op_user_id    
                LEFT JOIN sys_specific_definitions sd15x ON (sd15x.id = sd15.id OR sd15x.language_parent_id = sd15.id) AND sd15x.language_id =lx.id  AND sd15x.deleted =0 AND sd15x.active =0 
                LEFT JOIN sys_specific_definitions sd16x ON (sd16x.id = sd16.id OR sd16x.language_parent_id = sd16.id)AND sd16x.language_id = lx.id  AND sd16x.deleted = 0 AND sd16x.active = 0
                LEFT JOIN sys_buyback_matrix ax ON (ax.id = a.id OR ax.language_parent_id = a.id) AND ax.deleted =0 AND ax.active =0 AND lx.id = ax.language_id
                
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
     * @ Gridi doldurmak için sys_buyback_matrix tablosundan çekilen kayıtlarının kaç tane olduğunu döndürür   !!
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
                FROM sys_buyback_matrix a
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
     * @ sys_buyback_matrix tablosundan parametre olarak  gelen id kaydın aktifliğini
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
                UPDATE sys_buyback_matrix
                SET active = (  SELECT   
                                CASE active
                                    WHEN 0 THEN 1
                                    ELSE 0
                                END activex
                                FROM sys_buyback_matrix
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
     * @ buyback matrix  tanımlarını grid formatında döndürür !! ana tablo  sys_buyback_matrix 
     * @version v 1.0  15.08.2018
     * @param array | null $args
     * @return array
     * @throws \PDOException  
     */  
    public function fillBuybackMatrixGridx($params = array()) {
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
                $sort = "  a.id ";
            }

            if (isset($params['order']) && $params['order'] != "") {
                $order = trim($params['order']);
                $orderArr = explode(",", $order);
                //print_r($orderArr);
                if (count($orderArr) === 1)
                    $order = trim($params['order']);
            } else {
                $order = "DESC";
            }
            $sorguStr = null;                            
            if (isset($params['filterRules'])) {
                $filterRules = trim($params['filterRules']);
                $jsonFilter = json_decode($filterRules, true);
              
                $sorguExpression = null;
                foreach ($jsonFilter as $std) {
                    if ($std['value'] != null) {
                        switch (trim($std['field'])) {
                            case 'contract_name':
                                $sorguExpression = ' ILIKE \'%' . $std['value'] . '%\' ';
                                $sorguStr.=" AND COALESCE(NULLIF(grdx.name, ''), grd.name_eng)" . $sorguExpression . ' ';
                              
                                break;
                            case 'vahicle_description':
                                $sorguExpression = ' ILIKE \'%' . $std['value'] . '%\'  ';
                                $sorguStr.=" AND crd.description" . $sorguExpression . ' ';

                                break; 
                             case 'buyback_type_name':
                                $sorguExpression = ' ILIKE \'%' . $std['value'] . '%\'  ';
                                $sorguStr.=" AND COALESCE(NULLIF(drdx.name, ''), drd.name_eng)" . $sorguExpression . ' ';

                                break; 
                             case 'terrain_name':
                                $sorguExpression = ' ILIKE \'%' . $std['value'] . '%\'  ';
                                $sorguStr.=" AND COALESCE(NULLIF(hrdx.name, ''), hrd.name_eng)" . $sorguExpression . ' ';

                                break; 
                             case 'month_name':
                                $sorguExpression = ' ILIKE \'%' . $std['value'] . '%\'  ';
                                $sorguStr.=" AND frd.name" . $sorguExpression . ' ';

                                break; 
                             case 'mileage_type_name':
                                $sorguExpression = ' ILIKE \'%' . $std['value'] . '%\'  ';
                                $sorguStr.=" AND erd.name" . $sorguExpression . ' ';

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
            $contractTypeID =0 ;
            if (isset($params['ContractTypeID']) && $params['ContractTypeID'] != "") {
                $contractTypeID = $params['ContractTypeID'];
                $addSql .="  a.contract_type_id  = " . intval($contractTypeID). "  AND  " ; 
            }  
            $monthID =0 ;
            if (isset($params['MonthID']) && $params['MonthID'] != "") {
                $monthID = $params['MonthID'];
                $addSql .="  a.month_id  = " . intval($monthID). "  AND  " ; 
            }  
            $mileageID =0 ;
            if (isset($params['MileageID']) && $params['MileageID'] != "") {
                $mileageID = $params['MileageID'];
                $addSql .="  a.mileage_id  = " . intval($mileageID). "  AND  " ; 
            } 
            $terrainID =0 ;
            if (isset($params['TerrainID']) && $params['TerrainID'] != "") {
                $terrainID = $params['TerrainID'];
                $addSql .="  a.terrain_id  = " . intval($terrainID). "  AND  " ; 
            }  
            $buybackTypeID =0 ;
            if (isset($params['BuybackTypeID']) && $params['BuybackTypeID'] != "") {
                $buybackTypeID = $params['BuybackTypeID'];
                $addSql .="  a.buyback_type_id  = " . intval($buybackTypeID). "  AND  " ; 
            } 
                $sql = "
                    SELECT 
                        a.id, 
                        a.act_parent_id as apid,  
                        a.contract_type_id,
                        COALESCE(NULLIF(grdx.name, ''), grd.name_eng) AS contract_name,
                        a.model_id ,
                        crd.description AS vahicle_description,
                        a.buyback_type_id,
                        COALESCE(NULLIF(drdx.name, ''), drd.name_eng) AS buyback_type_name,
                        a.comfort_super_id,
                        COALESCE(NULLIF(sd19x.description, ''), sd19.description_eng) AS comfort_super_name, 
                        a.terrain_id,
                        COALESCE(NULLIF(hrdx.name, ''), hrd.name_eng) AS terrain_name,
                        a.month_id,
                        frd.mvalue AS month_name,
                        a.mileage_id,
                        erd.mileages1  AS mileage_type_name,
                        a.price,
                         
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
                    FROM sys_buyback_matrix a                    
                    INNER JOIN sys_language l ON l.id = 385 AND l.deleted =0 AND l.active =0
                    LEFT JOIN sys_language lx ON lx.id = " . intval($languageIdValue) . "  AND lx.deleted =0 AND lx.active =0    
                    INNER JOIN info_users u ON u.id = a.op_user_id 
                    /*----*/   
                    INNER JOIN sys_vehicles_trade crd ON crd.act_parent_id = a.model_id AND crd.show_it = 0 AND crd.language_id= l.id
                      
                    INNER JOIN sys_buyback_types drd ON drd.act_parent_id = a.buyback_type_id AND drd.show_it = 0 AND drd.language_id= l.id  
                    LEFT JOIN sys_buyback_types drdx ON (drdx.act_parent_id = drd.act_parent_id OR drdx.language_parent_id= drd.act_parent_id) AND drdx.show_it = 0 AND drdx.language_id =lx.id  
                       
                    INNER JOIN sys_mileagesx erd ON erd.act_parent_id = a.mileage_id AND erd.show_it =  0
                    LEFT JOIN sys_mileagesx erdx ON (erdx.act_parent_id = erd.act_parent_id) AND erdx.show_it = 0 
                    
                    INNER JOIN sys_monthsx frd ON frd.act_parent_id = a.month_id AND frd.show_it = 0  
                    LEFT JOIN sys_monthsx frdx ON (frdx.act_parent_id = frd.act_parent_id  ) AND frdx.show_it = 0 
                    
                    INNER JOIN sys_contract_types grd ON grd.act_parent_id = a.contract_type_id AND grd.show_it = 0 AND grd.language_id= l.id  
                    LEFT JOIN sys_contract_types grdx ON (grdx.act_parent_id = grd.act_parent_id OR grdx.language_parent_id= grd.act_parent_id) AND grdx.show_it = 0 AND grdx.language_id =lx.id  
                   
                    INNER JOIN sys_terrains hrd ON hrd.act_parent_id = a.terrain_id AND hrd.show_it = 0 AND hrd.language_id= l.id  
                    LEFT JOIN sys_terrains hrdx ON (hrdx.act_parent_id = hrd.act_parent_id OR hrdx.language_parent_id= hrd.act_parent_id) AND hrdx.show_it = 0 AND hrdx.language_id =lx.id  
                   
                    /*----*/   
                    /* INNER JOIN sys_specific_definitions sd15 ON sd15.main_group = 15 AND sd15.first_group= a.deleted AND sd15.deleted =0 AND sd15.active =0 AND sd15.language_parent_id =0 */
                    INNER JOIN sys_specific_definitions sd16 ON sd16.main_group = 16 AND sd16.first_group= a.active AND sd16.deleted = 0 AND sd16.active = 0 AND sd16.language_id =l.id
                    /**/
                    /*  LEFT JOIN sys_specific_definitions sd15x ON sd15x.language_id =lx.id AND (sd15x.id = sd15.id OR sd15x.language_parent_id = sd15.id) AND sd15x.deleted =0 AND sd15x.active =0  */
                    LEFT JOIN sys_specific_definitions sd16x ON sd16x.language_id = lx.id AND (sd16x.id = sd16.id OR sd16x.language_parent_id = sd16.id) AND sd16x.deleted = 0 AND sd16x.active = 0
                    INNER JOIN sys_specific_definitions sd19 ON sd19.main_group = 19 AND sd19.first_group= a.comfort_super_id AND sd19.deleted = 0 AND sd19.active = 0 AND sd19.language_id =l.id
                    LEFT JOIN sys_specific_definitions sd19x ON sd19x.language_id = lx.id AND (sd19x.id = sd19.id OR sd19x.language_parent_id = sd19.id) AND sd19x.deleted = 0 AND sd19x.active = 0
                    
                    WHERE  
                        a.deleted =0 AND
                        a.show_it =0 AND 
                        a.contract_type_id = 2                         
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
     * @ buyback matrix  tanımlarını grid formatında gösterilirken kaç kayıt olduğunu döndürür !! ana tablo  sys_buyback_matrix 
     * @version v 1.0  15.08.2018
     * @param array | null $args
     * @return array
     * @throws \PDOException  
     */  
    public function fillBuybackMatrixGridxRtl($params = array()) {
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
                           case 'contract_name':
                                $sorguExpression = ' ILIKE \'%' . $std['value'] . '%\' ';
                                $sorguStr.=" AND COALESCE(NULLIF(grdx.name, ''), grd.name_eng)" . $sorguExpression . ' ';
                              
                                break;
                            case 'vahicle_description':
                                $sorguExpression = ' ILIKE \'%' . $std['value'] . '%\'  ';
                                $sorguStr.=" AND crd.description" . $sorguExpression . ' ';

                                break; 
                             case 'buyback_type_name':
                                $sorguExpression = ' ILIKE \'%' . $std['value'] . '%\'  ';
                                $sorguStr.=" AND COALESCE(NULLIF(drdx.name, ''), drd.name_eng)" . $sorguExpression . ' ';

                                break; 
                             case 'terrain_name':
                                $sorguExpression = ' ILIKE \'%' . $std['value'] . '%\'  ';
                                $sorguStr.=" AND COALESCE(NULLIF(hrdx.name, ''), hrd.name_eng)" . $sorguExpression . ' ';

                                break; 
                             case 'month_name':
                                $sorguExpression = ' ILIKE \'%' . $std['value'] . '%\'  ';
                                $sorguStr.=" AND frd.name" . $sorguExpression . ' ';

                                break; 
                             case 'mileage_type_name':
                                $sorguExpression = ' ILIKE \'%' . $std['value'] . '%\'  ';
                                $sorguStr.=" AND erd.name" . $sorguExpression . ' ';

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
             $contractTypeID =0 ;
            if (isset($params['ContractTypeID']) && $params['ContractTypeID'] != "") {
                $contractTypeID = $params['ContractTypeID'];
                $addSql .="  a.contract_type_id  = " . intval($contractTypeID). "  AND  " ; 
            }  
            $monthID =0 ;
            if (isset($params['MonthID']) && $params['MonthID'] != "") {
                $monthID = $params['MonthID'];
                $addSql .="  a.month_id  = " . intval($monthID). "  AND  " ; 
            }  
            $mileageID =0 ;
            if (isset($params['MileageID']) && $params['MileageID'] != "") {
                $mileageID = $params['MileageID'];
                $addSql .="  a.mileage_id  = " . intval($mileageID). "  AND  " ; 
            } 
            $terrainID =0 ;
            if (isset($params['TerrainID']) && $params['TerrainID'] != "") {
                $terrainID = $params['TerrainID'];
                $addSql .="  a.terrain_id  = " . intval($terrainID). "  AND  " ; 
            }  
            $buybackTypeID =0 ;
            if (isset($params['BuybackTypeID']) && $params['BuybackTypeID'] != "") {
                $buybackTypeID = $params['BuybackTypeID'];
                $addSql .="  a.buyback_type_id  = " . intval($buybackTypeID). "  AND  " ; 
            } 

                $sql = "
                   SELECT COUNT(asdx.id) count FROM ( 
                        SELECT 
                            a.id, 
                            a.act_parent_id as apid,  
                            a.contract_type_id,
                            COALESCE(NULLIF(grdx.name, ''), grd.name_eng) AS contract_name,
                            a.model_id ,
                            crd.description AS vahicle_description,
                            a.buyback_type_id,
                            COALESCE(NULLIF(drdx.name, ''), drd.name_eng) AS buyback_type_name,
                            a.comfort_super_id,
                            COALESCE(NULLIF(sd19x.description, ''), sd19.description_eng) AS comfort_super_name, 
                            a.terrain_id,
                            COALESCE(NULLIF(hrdx.name, ''), hrd.name_eng) AS terrain_name,
                            a.month_id,
                            frd.name AS month_name,
                            a.mileage_id,
                            erd.name  AS mileage_type_name, 
                            COALESCE(NULLIF(sd16x.description, ''), sd16.description_eng) AS state_active, 
                            u.username AS op_user_name 
                        FROM sys_buyback_matrix a                    
                        INNER JOIN sys_language l ON l.id = 385 AND l.deleted =0 AND l.active =0
                        LEFT JOIN sys_language lx ON lx.id = " . intval($languageIdValue) . "  AND lx.deleted =0 AND lx.active =0    
                        INNER JOIN info_users u ON u.id = a.op_user_id 
                        /*----*/   
                        INNER JOIN sys_vehicles_trade crd ON crd.act_parent_id = a.model_id AND crd.show_it = 0 AND crd.language_id= l.id

                        INNER JOIN sys_buyback_types drd ON drd.act_parent_id = a.buyback_type_id AND drd.show_it = 0 AND drd.language_id= l.id  
                        LEFT JOIN sys_buyback_types drdx ON (drdx.act_parent_id = drd.act_parent_id OR drdx.language_parent_id= drd.act_parent_id) AND drdx.show_it = 0 AND drdx.language_id =lx.id  

                        INNER JOIN sys_mileagesx erd ON erd.act_parent_id = a.mileage_id AND erd.show_it =  0
                        LEFT JOIN sys_mileagesx erdx ON (erdx.act_parent_id = erd.act_parent_id) AND erdx.show_it = 0 

                        INNER JOIN sys_monthsx frd ON frd.act_parent_id = a.month_id AND frd.show_it = 0  
                        LEFT JOIN sys_monthsx frdx ON (frdx.act_parent_id = frd.act_parent_id  ) AND frdx.show_it = 0 

                        INNER JOIN sys_contract_types grd ON grd.act_parent_id = a.contract_type_id AND grd.show_it = 0 AND grd.language_id= l.id  
                        LEFT JOIN sys_contract_types grdx ON (grdx.act_parent_id = grd.act_parent_id OR grdx.language_parent_id= grd.act_parent_id) AND grdx.show_it = 0 AND grdx.language_id =lx.id  

                        INNER JOIN sys_terrains hrd ON hrd.act_parent_id = a.terrain_id AND hrd.show_it = 0 AND hrd.language_id= l.id  
                        LEFT JOIN sys_terrains hrdx ON (hrdx.act_parent_id = hrd.act_parent_id OR hrdx.language_parent_id= hrd.act_parent_id) AND hrdx.show_it = 0 AND hrdx.language_id =lx.id  

                        /*----*/   
                        /* INNER JOIN sys_specific_definitions sd15 ON sd15.main_group = 15 AND sd15.first_group= a.deleted AND sd15.deleted =0 AND sd15.active =0 AND sd15.language_parent_id =0 */
                        INNER JOIN sys_specific_definitions sd16 ON sd16.main_group = 16 AND sd16.first_group= a.active AND sd16.deleted = 0 AND sd16.active = 0 AND sd16.language_id =l.id
                        /**/
                        /*  LEFT JOIN sys_specific_definitions sd15x ON sd15x.language_id =lx.id AND (sd15x.id = sd15.id OR sd15x.language_parent_id = sd15.id) AND sd15x.deleted =0 AND sd15x.active =0  */
                        LEFT JOIN sys_specific_definitions sd16x ON sd16x.language_id = lx.id AND (sd16x.id = sd16.id OR sd16x.language_parent_id = sd16.id) AND sd16x.deleted = 0 AND sd16x.active = 0
                    
                        INNER JOIN sys_specific_definitions sd19 ON sd19.main_group = 19 AND sd19.first_group= a.comfort_super_id AND sd19.deleted = 0 AND sd19.active = 0 AND sd19.language_id =l.id
                        LEFT JOIN sys_specific_definitions sd19x ON sd19x.language_id = lx.id AND (sd19x.id = sd19.id OR sd19x.language_parent_id = sd19.id) AND sd19x.deleted = 0 AND sd19x.active = 0
                    
                      where
                            a.deleted =0 AND
                            a.show_it =0  AND 
                            a.contract_type_id = 2  
                        
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
     * @ tradeback matrix  tanımlarını grid formatında döndürür !! ana tablo  sys_buyback_matrix 
     * @version v 1.0  15.08.2018
     * @param array | null $args
     * @return array
     * @throws \PDOException  
     */  
    public function fillTradebackMatrixGridx($params = array()) {
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
                $sort = "  a.id ";
            }

            if (isset($params['order']) && $params['order'] != "") {
                $order = trim($params['order']);
                $orderArr = explode(",", $order);
                //print_r($orderArr);
                if (count($orderArr) === 1)
                    $order = trim($params['order']);
            } else {
                $order = "DESC";
            }
            $sorguStr = null;                            
            if (isset($params['filterRules'])) {
                $filterRules = trim($params['filterRules']);
                $jsonFilter = json_decode($filterRules, true);
              
                $sorguExpression = null;
                foreach ($jsonFilter as $std) {
                    if ($std['value'] != null) {
                        switch (trim($std['field'])) {
                            case 'contract_name':
                                $sorguExpression = ' ILIKE \'%' . $std['value'] . '%\' ';
                                $sorguStr.=" AND COALESCE(NULLIF(grdx.name, ''), grd.name_eng)" . $sorguExpression . ' ';
                              
                                break;
                            case 'vahicle_description':
                                $sorguExpression = ' ILIKE \'%' . $std['value'] . '%\'  ';
                                $sorguStr.=" AND crd.description" . $sorguExpression . ' ';

                                break; 
                             case 'buyback_type_name':
                                $sorguExpression = ' ILIKE \'%' . $std['value'] . '%\'  ';
                                $sorguStr.=" AND COALESCE(NULLIF(drdx.name, ''), drd.name_eng)" . $sorguExpression . ' ';

                                break; 
                             case 'terrain_name':
                                $sorguExpression = ' ILIKE \'%' . $std['value'] . '%\'  ';
                                $sorguStr.=" AND COALESCE(NULLIF(hrdx.name, ''), hrd.name_eng)" . $sorguExpression . ' ';

                                break; 
                             case 'month_name':
                                $sorguExpression = ' ILIKE \'%' . $std['value'] . '%\'  ';
                                $sorguStr.=" AND frd.name" . $sorguExpression . ' ';

                                break; 
                             case 'mileage_type_name':
                                $sorguExpression = ' ILIKE \'%' . $std['value'] . '%\'  ';
                                $sorguStr.=" AND erd.name" . $sorguExpression . ' ';

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
            $contractTypeID =0 ;
            if (isset($params['ContractTypeID']) && $params['ContractTypeID'] != "") {
                $contractTypeID = $params['ContractTypeID'];
                $addSql .="  a.contract_type_id  = " . intval($contractTypeID). "  AND  " ; 
            }  
            $monthID =0 ;
            if (isset($params['MonthID']) && $params['MonthID'] != "") {
                $monthID = $params['MonthID'];
                $addSql .="  a.month_id  = " . intval($monthID). "  AND  " ; 
            }  
            $mileageID =0 ;
            if (isset($params['MileageID']) && $params['MileageID'] != "") {
                $mileageID = $params['MileageID'];
                $addSql .="  a.mileage_id  = " . intval($mileageID). "  AND  " ; 
            } 
            $terrainID =0 ;
            if (isset($params['TerrainID']) && $params['TerrainID'] != "") {
                $terrainID = $params['TerrainID'];
                $addSql .="  a.terrain_id  = " . intval($terrainID). "  AND  " ; 
            }  
            $buybackTypeID =0 ;
            if (isset($params['BuybackTypeID']) && $params['BuybackTypeID'] != "") {
                $buybackTypeID = $params['BuybackTypeID'];
                $addSql .="  a.buyback_type_id  = " . intval($buybackTypeID). "  AND  " ; 
            } 
                $sql = "
                    SELECT 
                        a.id, 
                        a.act_parent_id as apid,  
                        a.contract_type_id,
                        COALESCE(NULLIF(grdx.name, ''), grd.name_eng) AS contract_name,
                        a.model_id ,
                        crd.description AS vahicle_description,
                        a.buyback_type_id,
                        COALESCE(NULLIF(drdx.name, ''), drd.name_eng) AS buyback_type_name,
                        a.comfort_super_id,
                        COALESCE(NULLIF(sd19x.description, ''), sd19.description_eng) AS comfort_super_name, 
                        a.terrain_id,
                        COALESCE(NULLIF(hrdx.name, ''), hrd.name_eng) AS terrain_name,
                        a.month_id,
                        frd.mvalue AS month_name,
                        a.mileage_id,
                        erd.mileages1  AS mileage_type_name,
                        a.price,
                         
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
                    FROM sys_buyback_matrix a                    
                    INNER JOIN sys_language l ON l.id = 385 AND l.deleted =0 AND l.active =0
                    LEFT JOIN sys_language lx ON lx.id = " . intval($languageIdValue) . "  AND lx.deleted =0 AND lx.active =0    
                    INNER JOIN info_users u ON u.id = a.op_user_id 
                    /*----*/   
                    INNER JOIN sys_vehicles_trade crd ON crd.act_parent_id = a.model_id AND crd.show_it = 0 AND crd.language_id= l.id
                      
                    INNER JOIN sys_buyback_types drd ON drd.act_parent_id = a.buyback_type_id AND drd.show_it = 0 AND drd.language_id= l.id  
                    LEFT JOIN sys_buyback_types drdx ON (drdx.act_parent_id = drd.act_parent_id OR drdx.language_parent_id= drd.act_parent_id) AND drdx.show_it = 0 AND drdx.language_id =lx.id  
                       
                    INNER JOIN sys_mileagesx erd ON erd.act_parent_id = a.mileage_id AND erd.show_it =  0
                    LEFT JOIN sys_mileagesx erdx ON (erdx.act_parent_id = erd.act_parent_id) AND erdx.show_it = 0 
                    
                    INNER JOIN sys_monthsx frd ON frd.act_parent_id = a.month_id AND frd.show_it = 0  
                    LEFT JOIN sys_monthsx frdx ON (frdx.act_parent_id = frd.act_parent_id  ) AND frdx.show_it = 0 
                    
                    INNER JOIN sys_contract_types grd ON grd.act_parent_id = a.contract_type_id AND grd.show_it = 0 AND grd.language_id= l.id  
                    LEFT JOIN sys_contract_types grdx ON (grdx.act_parent_id = grd.act_parent_id OR grdx.language_parent_id= grd.act_parent_id) AND grdx.show_it = 0 AND grdx.language_id =lx.id  
                   
                    INNER JOIN sys_terrains hrd ON hrd.act_parent_id = a.terrain_id AND hrd.show_it = 0 AND hrd.language_id= l.id  
                    LEFT JOIN sys_terrains hrdx ON (hrdx.act_parent_id = hrd.act_parent_id OR hrdx.language_parent_id= hrd.act_parent_id) AND hrdx.show_it = 0 AND hrdx.language_id =lx.id  
                   
                    /*----*/   
                    /* INNER JOIN sys_specific_definitions sd15 ON sd15.main_group = 15 AND sd15.first_group= a.deleted AND sd15.deleted =0 AND sd15.active =0 AND sd15.language_parent_id =0 */
                    INNER JOIN sys_specific_definitions sd16 ON sd16.main_group = 16 AND sd16.first_group= a.active AND sd16.deleted = 0 AND sd16.active = 0 AND sd16.language_id =l.id
                    /**/
                    /*  LEFT JOIN sys_specific_definitions sd15x ON sd15x.language_id =lx.id AND (sd15x.id = sd15.id OR sd15x.language_parent_id = sd15.id) AND sd15x.deleted =0 AND sd15x.active =0  */
                    LEFT JOIN sys_specific_definitions sd16x ON sd16x.language_id = lx.id AND (sd16x.id = sd16.id OR sd16x.language_parent_id = sd16.id) AND sd16x.deleted = 0 AND sd16x.active = 0
                    INNER JOIN sys_specific_definitions sd19 ON sd19.main_group = 19 AND sd19.first_group= a.comfort_super_id AND sd19.deleted = 0 AND sd19.active = 0 AND sd19.language_id =l.id
                    LEFT JOIN sys_specific_definitions sd19x ON sd19x.language_id = lx.id AND (sd19x.id = sd19.id OR sd19x.language_parent_id = sd19.id) AND sd19x.deleted = 0 AND sd19x.active = 0
                    
                    WHERE  
                        a.deleted =0 AND
                        a.show_it =0 AND 
                        a.contract_type_id =3                        
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
     * @ buyback matrix  tanımlarını grid formatında gösterilirken kaç kayıt olduğunu döndürür !! ana tablo  sys_buyback_matrix 
     * @version v 1.0  15.08.2018
     * @param array | null $args
     * @return array
     * @throws \PDOException  
     */  
    public function fillTradebackMatrixGridxRtl($params = array()) {
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
                           case 'contract_name':
                                $sorguExpression = ' ILIKE \'%' . $std['value'] . '%\' ';
                                $sorguStr.=" AND COALESCE(NULLIF(grdx.name, ''), grd.name_eng)" . $sorguExpression . ' ';
                              
                                break;
                            case 'vahicle_description':
                                $sorguExpression = ' ILIKE \'%' . $std['value'] . '%\'  ';
                                $sorguStr.=" AND crd.description" . $sorguExpression . ' ';

                                break; 
                             case 'buyback_type_name':
                                $sorguExpression = ' ILIKE \'%' . $std['value'] . '%\'  ';
                                $sorguStr.=" AND COALESCE(NULLIF(drdx.name, ''), drd.name_eng)" . $sorguExpression . ' ';

                                break; 
                             case 'terrain_name':
                                $sorguExpression = ' ILIKE \'%' . $std['value'] . '%\'  ';
                                $sorguStr.=" AND COALESCE(NULLIF(hrdx.name, ''), hrd.name_eng)" . $sorguExpression . ' ';

                                break; 
                             case 'month_name':
                                $sorguExpression = ' ILIKE \'%' . $std['value'] . '%\'  ';
                                $sorguStr.=" AND frd.name" . $sorguExpression . ' ';

                                break; 
                             case 'mileage_type_name':
                                $sorguExpression = ' ILIKE \'%' . $std['value'] . '%\'  ';
                                $sorguStr.=" AND erd.name" . $sorguExpression . ' ';

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
             $contractTypeID =0 ;
            if (isset($params['ContractTypeID']) && $params['ContractTypeID'] != "") {
                $contractTypeID = $params['ContractTypeID'];
                $addSql .="  a.contract_type_id  = " . intval($contractTypeID). "  AND  " ; 
            }  
            $monthID =0 ;
            if (isset($params['MonthID']) && $params['MonthID'] != "") {
                $monthID = $params['MonthID'];
                $addSql .="  a.month_id  = " . intval($monthID). "  AND  " ; 
            }  
            $mileageID =0 ;
            if (isset($params['MileageID']) && $params['MileageID'] != "") {
                $mileageID = $params['MileageID'];
                $addSql .="  a.mileage_id  = " . intval($mileageID). "  AND  " ; 
            } 
            $terrainID =0 ;
            if (isset($params['TerrainID']) && $params['TerrainID'] != "") {
                $terrainID = $params['TerrainID'];
                $addSql .="  a.terrain_id  = " . intval($terrainID). "  AND  " ; 
            }  
            $buybackTypeID =0 ;
            if (isset($params['BuybackTypeID']) && $params['BuybackTypeID'] != "") {
                $buybackTypeID = $params['BuybackTypeID'];
                $addSql .="  a.buyback_type_id  = " . intval($buybackTypeID). "  AND  " ; 
            } 

                $sql = "
                   SELECT COUNT(asdx.id) count FROM ( 
                        SELECT 
                        a.id, 
                        a.act_parent_id as apid,  
                        a.contract_type_id,
                        COALESCE(NULLIF(grdx.name, ''), grd.name_eng) AS contract_name,
                        a.model_id ,
                        crd.description AS vahicle_description,
                        a.buyback_type_id,
                        COALESCE(NULLIF(drdx.name, ''), drd.name_eng) AS buyback_type_name,
                        a.comfort_super_id,
                        COALESCE(NULLIF(sd19x.description, ''), sd19.description_eng) AS comfort_super_name, 
                        a.terrain_id,
                        COALESCE(NULLIF(hrdx.name, ''), hrd.name_eng) AS terrain_name,
                        a.month_id,
                        frd.name AS month_name,
                        a.mileage_id,
                        erd.name  AS mileage_type_name, 
                        COALESCE(NULLIF(sd16x.description, ''), sd16.description_eng) AS state_active, 
                        u.username AS op_user_name 
                    FROM sys_buyback_matrix a                    
                    INNER JOIN sys_language l ON l.id = 385 AND l.deleted =0 AND l.active =0
                    LEFT JOIN sys_language lx ON lx.id = " . intval($languageIdValue) . "  AND lx.deleted =0 AND lx.active =0    
                    INNER JOIN info_users u ON u.id = a.op_user_id 
                    /*----*/   
                    INNER JOIN sys_vehicles_trade crd ON crd.act_parent_id = a.model_id AND crd.show_it = 0 AND crd.language_id= l.id
                      
                    INNER JOIN sys_buyback_types drd ON drd.act_parent_id = a.buyback_type_id AND drd.show_it = 0 AND drd.language_id= l.id  
                    LEFT JOIN sys_buyback_types drdx ON (drdx.act_parent_id = drd.act_parent_id OR drdx.language_parent_id= drd.act_parent_id) AND drdx.show_it = 0 AND drdx.language_id =lx.id  
                       
                    INNER JOIN sys_mileagesx erd ON erd.act_parent_id = a.mileage_id AND erd.show_it =  0
                    LEFT JOIN sys_mileagesx erdx ON (erdx.act_parent_id = erd.act_parent_id) AND erdx.show_it = 0 
                    
                    INNER JOIN sys_monthsx frd ON frd.act_parent_id = a.month_id AND frd.show_it = 0  
                    LEFT JOIN sys_monthsx frdx ON (frdx.act_parent_id = frd.act_parent_id  ) AND frdx.show_it = 0 
                    
                    INNER JOIN sys_contract_types grd ON grd.act_parent_id = a.contract_type_id AND grd.show_it = 0 AND grd.language_id= l.id  
                    LEFT JOIN sys_contract_types grdx ON (grdx.act_parent_id = grd.act_parent_id OR grdx.language_parent_id= grd.act_parent_id) AND grdx.show_it = 0 AND grdx.language_id =lx.id  
                   
                    INNER JOIN sys_terrains hrd ON hrd.act_parent_id = a.terrain_id AND hrd.show_it = 0 AND hrd.language_id= l.id  
                    LEFT JOIN sys_terrains hrdx ON (hrdx.act_parent_id = hrd.act_parent_id OR hrdx.language_parent_id= hrd.act_parent_id) AND hrdx.show_it = 0 AND hrdx.language_id =lx.id  
                   
                    /*----*/   
                    /* INNER JOIN sys_specific_definitions sd15 ON sd15.main_group = 15 AND sd15.first_group= a.deleted AND sd15.deleted =0 AND sd15.active =0 AND sd15.language_parent_id =0 */
                    INNER JOIN sys_specific_definitions sd16 ON sd16.main_group = 16 AND sd16.first_group= a.active AND sd16.deleted = 0 AND sd16.active = 0 AND sd16.language_id =l.id
                    /**/
                    /*  LEFT JOIN sys_specific_definitions sd15x ON sd15x.language_id =lx.id AND (sd15x.id = sd15.id OR sd15x.language_parent_id = sd15.id) AND sd15x.deleted =0 AND sd15x.active =0  */
                    LEFT JOIN sys_specific_definitions sd16x ON sd16x.language_id = lx.id AND (sd16x.id = sd16.id OR sd16x.language_parent_id = sd16.id) AND sd16x.deleted = 0 AND sd16x.active = 0
                    INNER JOIN sys_specific_definitions sd19 ON sd19.main_group = 19 AND sd19.first_group= a.comfort_super_id AND sd19.deleted = 0 AND sd19.active = 0 AND sd19.language_id =l.id
                    LEFT JOIN sys_specific_definitions sd19x ON sd19x.language_id = lx.id AND (sd19x.id = sd19.id OR sd19x.language_parent_id = sd19.id) AND sd19x.deleted = 0 AND sd19x.active = 0
                    
                    WHERE  
                        a.deleted =0 AND
                        a.show_it =0  AND 
                        a.contract_type_id = 3 
                        
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
     * @ sys_buyback_matrix tablosundan parametre olarak  gelen id kaydını active ve show_it alanlarını 1 yapar. !!
     * @version v 1.0  24.08.2018
     * @param type $params
     * @return array
     * @throws \PDOException
     */
    public function makePassive($params = array()) {
        try {
            $pdo = $this->slimApp->getServiceManager()->get('pgConnectFactory'); 
            $statement = $pdo->prepare(" 
                UPDATE sys_buyback_matrix
                SET                         
                    c_date =  timezone('Europe/Istanbul'::text, ('now'::text)::timestamp(0) with time zone) ,                     
                    active = 1 ,
                    show_it =1 
                WHERE id = :id");
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
     * @ sys_buyback_matrix tablosundan parametre olarak  gelen id kaydın active veshow_it  alanını 1 yapar ve 
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
                    INSERT INTO sys_buyback_matrix (
                        contract_type_id,
                        model_id,
                        buyback_type_id,
                        terrain_id,
                        month_id,
                        mileage_id,
                        price,
                         
                        active,
                        deleted,
                        op_user_id,
                        act_parent_id,
                        show_it
                        )
                    SELECT
                        contract_type_id,
                        model_id,
                        buyback_type_id,
                        terrain_id,
                        month_id,
                        mileage_id,
                        price,
                       
                        1 AS active,  
                        1 AS deleted, 
                        " . intval($opUserIdValue) . " AS op_user_id, 
                        act_parent_id,
                        0 AS show_it 
                    FROM sys_buyback_matrix 
                    WHERE id  =" . intval($params['id']) . "    
                   ");

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
     * @ sys_buyback_matrix tablosuna yeni bir kayıt oluşturur.  !! 
     * @version v 1.0  26.08.2018
     * @param type $params
     * @return array
     * @throws \PDOException
     */
    public function insertBBAct($params = array()) {
        try {
            $pdo = $this->slimApp->getServiceManager()->get('pgConnectFactory');
            $pdo->beginTransaction();
                           
            $errorInfo[0] = "99999";
            $contractTypeId = 2;
         /*   if ((isset($params['ContractTypeId']) && $params['ContractTypeId'] != "")) {
                $contractTypeId = intval($params['ContractTypeId']);
            } else {
                throw new \PDOException($errorInfo[0]);
            }     
            $modelId= -1111;
          * *
          */
            if ((isset($params['ModelId']) && $params['ModelId'] != "")) {
                $modelId = intval($params['ModelId']);
            } else {
                throw new \PDOException($errorInfo[0]);
            }
            $buybackTypeId = -1111;
            if ((isset($params['BuybackTypeId']) && $params['BuybackTypeId'] != "")) {
                $buybackTypeId = intval($params['BuybackTypeId']);
            } else {
                throw new \PDOException($errorInfo[0]);
            }
            $terrainId = -1111;
            if ((isset($params['TerrainId']) && $params['TerrainId'] != "")) {
                $terrainId = intval($params['TerrainId']);
            } else {
                throw new \PDOException($errorInfo[0]);
            }                           
            $monthId = -1111;
            if ((isset($params['MonthId']) && $params['MonthId'] != "")) {
                $monthId = intval($params['MonthId']);
            } else {
                throw new \PDOException($errorInfo[0]);
            }
            $mileageId = -1111;
            if ((isset($params['MileageId']) && $params['MileageId'] != "")) {
                $mileageId = intval($params['MileageId']);
            } else {
                throw new \PDOException($errorInfo[0]);
            }
            $price = -1111;
            if ((isset($params['Price']) && $params['Price'] != "")) {
                $price = floatval($params['Price']);
            } else {
                throw new \PDOException($errorInfo[0]);
            }
                           
            $opUserId = InfoUsers::getUserId(array('pk' => $params['pk']));
            if (\Utill\Dal\Helper::haveRecord($opUserId)) {
                $opUserIdValue = $opUserId ['resultSet'][0]['user_id'];

                $kontrol = $this->haveRecords(
                        array(
                            'contract_type_id' => $contractTypeId,
                            'model_id' => $modelId,
                            'buyback_type_id' => $buybackTypeId,
                            'terrain_id' => $terrainId,
                            'month_id' => $monthId,
                            'mileage_id' => $mileageId, 
                ));
                if (!\Utill\Dal\Helper::haveRecord($kontrol)) {
                    $sql = "
                    INSERT INTO sys_buyback_matrix(
                            contract_type_id,
                            model_id,
                            buyback_type_id,
                            terrain_id,
                            month_id,
                            mileage_id,
                            price,

                            op_user_id,
                            act_parent_id  
                            )
                    VALUES (                         
                            " . intval($contractTypeId) . ",
                            " . intval($modelId) . ",
                            " . intval($buybackTypeId) . ",
                            " . intval($terrainId) . ",
                            " . intval($monthId) . ", 
                            " . intval($mileageId) . ",
                            " . floatval($price) . ",

                            " . intval($opUserIdValue) . ",
                           (SELECT last_value FROM sys_buyback_matrix_id_seq)
                                                 )   ";
                    $statement = $pdo->prepare($sql);
                    //   echo debugPDO($sql, $params);
                    $result = $statement->execute();
                    $errorInfo = $statement->errorInfo();
                    if ($errorInfo[0] != "00000" && $errorInfo[1] != NULL && $errorInfo[2] != NULL)
                        throw new \PDOException($errorInfo[0]);
                    $insertID = $pdo->lastInsertId('sys_buyback_matrix_id_seq');
                           
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
    
    /**
     * @author Okan CIRAN
     * @ sys_buyback_matrix tablosuna yeni bir kayıt oluşturur.  !! 
     * @version v 1.0  26.08.2018
     * @param type $params
     * @return array
     * @throws \PDOException
     */
    public function insertTBAct($params = array()) {
        try {
            $pdo = $this->slimApp->getServiceManager()->get('pgConnectFactory');
            $pdo->beginTransaction();
                           
            $errorInfo[0] = "99999";
            $contractTypeId =3;
          /*  if ((isset($params['ContractTypeId']) && $params['ContractTypeId'] != "")) {
                $contractTypeId = intval($params['ContractTypeId']);
            } else {
                throw new \PDOException($errorInfo[0]);
            }     
           * 
           */ 
            $modelId= -1111;
            if ((isset($params['ModelId']) && $params['ModelId'] != "")) {
                $modelId = intval($params['ModelId']);
            } else {
                throw new \PDOException($errorInfo[0]);
            }
            $buybackTypeId = -1111;
            if ((isset($params['BuybackTypeId']) && $params['BuybackTypeId'] != "")) {
                $buybackTypeId = intval($params['BuybackTypeId']);
            } else {
                throw new \PDOException($errorInfo[0]);
            }
            $terrainId = -1111;
            if ((isset($params['TerrainId']) && $params['TerrainId'] != "")) {
                $terrainId = intval($params['TerrainId']);
            } else {
                throw new \PDOException($errorInfo[0]);
            }                           
            $monthId = -1111;
            if ((isset($params['MonthId']) && $params['MonthId'] != "")) {
                $monthId = intval($params['MonthId']);
            } else {
                throw new \PDOException($errorInfo[0]);
            }
            $mileageId = -1111;
            if ((isset($params['MileageId']) && $params['MileageId'] != "")) {
                $mileageId = intval($params['MileageId']);
            } else {
                throw new \PDOException($errorInfo[0]);
            }
            $price = -1111;
            if ((isset($params['Price']) && $params['Price'] != "")) {
                $price = floatval($params['Price']);
            } else {
                throw new \PDOException($errorInfo[0]);
            }
                           
            $opUserId = InfoUsers::getUserId(array('pk' => $params['pk']));
            if (\Utill\Dal\Helper::haveRecord($opUserId)) {
                $opUserIdValue = $opUserId ['resultSet'][0]['user_id'];

                $kontrol = $this->haveRecords(
                        array(
                            'contract_type_id' => $contractTypeId,
                            'model_id' => $modelId,
                            'buyback_type_id' => $buybackTypeId,
                            'terrain_id' => $terrainId,
                            'month_id' => $monthId,
                            'mileage_id' => $mileageId, 
                ));
                if (!\Utill\Dal\Helper::haveRecord($kontrol)) {
                    $sql = "
                    INSERT INTO sys_buyback_matrix(
                            contract_type_id,
                            model_id,
                            buyback_type_id,
                            terrain_id,
                            month_id,
                            mileage_id,
                            price,

                            op_user_id,
                            act_parent_id  
                            )
                    VALUES (                         
                            " . intval($contractTypeId) . ",
                            " . intval($modelId) . ",
                            " . intval($buybackTypeId) . ",
                            " . intval($terrainId) . ",
                            " . intval($monthId) . ", 
                            " . intval($mileageId) . ",
                            " . floatval($price) . ",

                            " . intval($opUserIdValue) . ",
                           (SELECT last_value FROM sys_buyback_matrix_id_seq)
                                                 )   ";
                    $statement = $pdo->prepare($sql);
                    //   echo debugPDO($sql, $params);
                    $result = $statement->execute();
                    $errorInfo = $statement->errorInfo();
                    if ($errorInfo[0] != "00000" && $errorInfo[1] != NULL && $errorInfo[2] != NULL)
                        throw new \PDOException($errorInfo[0]);
                    $insertID = $pdo->lastInsertId('sys_buyback_matrix_id_seq');
                           
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
                           
    /**
     * @author Okan CIRAN
     * sys_buyback_matrix tablosuna parametre olarak gelen id deki kaydın bilgilerini günceller   !!
     * @version v 1.0  26.08.2018
     * @param type $params
     * @return array
     * @throws \PDOException
     */
    public function updateBBAct($params = array()) {
        try {
            $pdo = $this->slimApp->getServiceManager()->get('pgConnectFactory');
            $pdo->beginTransaction();
            $errorInfo[0] = "99999";
            $Id = -1111;
            if ((isset($params['Id']) && $params['Id'] != "")) {
                $Id = intval($params['Id']);
            } else {
                throw new \PDOException($errorInfo[0]);
            }
            $contractTypeId =2 ;
           /* if ((isset($params['ContractTypeId']) && $params['ContractTypeId'] != "")) {
                $contractTypeId = intval($params['ContractTypeId']);
            } else {
                throw new \PDOException($errorInfo[0]);
            }     
            * 
            */
            $modelId= -1111;
            if ((isset($params['ModelId']) && $params['ModelId'] != "")) {
                $modelId = intval($params['ModelId']);
            } else {
                throw new \PDOException($errorInfo[0]);
            }
            $buybackTypeId = -1111;
            if ((isset($params['BuybackTypeId']) && $params['BuybackTypeId'] != "")) {
                $buybackTypeId = intval($params['BuybackTypeId']);
            } else {
                throw new \PDOException($errorInfo[0]);
            }
            $terrainId = -1111;
            if ((isset($params['TerrainId']) && $params['TerrainId'] != "")) {
                $terrainId = intval($params['TerrainId']);
            } else {
                throw new \PDOException($errorInfo[0]);
            }                           
            $monthId = -1111;
            if ((isset($params['MonthId']) && $params['MonthId'] != "")) {
                $monthId = intval($params['MonthId']);
            } else {
                throw new \PDOException($errorInfo[0]);
            }
            $mileageId = -1111;
            if ((isset($params['MileageId']) && $params['MileageId'] != "")) {
                $mileageId = intval($params['MileageId']);
            } else {
                throw new \PDOException($errorInfo[0]);
            }
            $price = -1111;
            if ((isset($params['Price']) && $params['Price'] != "")) {
                $price = floatval($params['Price']);
            } else {
                throw new \PDOException($errorInfo[0]);
            }               
           

            $opUserIdParams = array('pk' => $params['pk'],);
            $opUserIdArray = $this->slimApp->getBLLManager()->get('opUserIdBLL');
            $opUserId = $opUserIdArray->getUserId($opUserIdParams);
            if (\Utill\Dal\Helper::haveRecord($opUserId)) {
                $opUserIdValue = $opUserId ['resultSet'][0]['user_id'];
                $opUserRoleIdValue = $opUserId ['resultSet'][0]['role_id'];

                $kontrol = $this->haveRecords(
                        array(
                            'name' =>$name, 
                            'id' => $Id
                ));
                if (!\Utill\Dal\Helper::haveRecord($kontrol)) {

                    $this->makePassive(array('id' => $params['Id']));

                    $statementInsert = $pdo->prepare("
                INSERT INTO sys_buyback_matrix (  
                        contract_type_id,
                        model_id,
                        buyback_type_id,
                        terrain_id,
                        month_id,
                        mileage_id,
                        price,
                         
                        op_user_id,
                        act_parent_id 
                        )  
                SELECT  
                    " . intval($contractTypeId) . ",
                    " . intval($modelId) . ",
                    " . intval($buybackTypeId) . ",
                    " . intval($terrainId) . ",
                    " . intval($monthId) . ", 
                    " . intval($mileageId) . ",
                    " . floatval($price) . ", 
               
                    " . intval($opUserIdValue) . " AS op_user_id,  
                    act_parent_id
                FROM sys_buyback_matrix 
                WHERE 
                     id  =" . intval($Id) . "                  
                                                ");
                    $result = $statementInsert->execute();
                    $insertID = $pdo->lastInsertId('sys_buyback_matrix_id_seq');
                    $affectedRows = $statementInsert->rowCount();
                    $errorInfo = $statementInsert->errorInfo();
                    if ($errorInfo[0] != "00000" && $errorInfo[1] != NULL && $errorInfo[2] != NULL)
                        throw new \PDOException($errorInfo[0]);

                    $pdo->commit();
                    return array("found" => true, "errorInfo" => $errorInfo, "affectedRowsCount" => $affectedRows,"lastInsertId" => $insertID);
                } else {
                    $errorInfo = '23505';
                    $errorInfoColumn = 'name';
                    $pdo->rollback();
                    return array("found" => false, "errorInfo" => $errorInfo, "resultSet" => '', "errorInfoColumn" => $errorInfoColumn);
                }
            } else {
                $errorInfo = '23502';   // 23502  user_id not_null_violation
                $errorInfoColumn = 'pk';
                $pdo->rollback();
                return array("found" => false, "errorInfo" => $errorInfo, "resultSet" => '', "errorInfoColumn" => $errorInfoColumn);
            }
        } catch (\PDOException $e /* Exception $e */) {
            // $pdo->rollback();
            return array("found" => false, "errorInfo" => $e->getMessage());
        }
    }
                           
    /**
     * @author Okan CIRAN
     * sys_buyback_matrix tablosuna parametre olarak gelen id deki kaydın bilgilerini günceller   !!
     * @version v 1.0  26.08.2018
     * @param type $params
     * @return array
     * @throws \PDOException
     */
    public function updateTBAct($params = array()) {
        try {
            $pdo = $this->slimApp->getServiceManager()->get('pgConnectFactory');
            $pdo->beginTransaction();
            $errorInfo[0] = "99999";
            $Id = -1111;
            if ((isset($params['Id']) && $params['Id'] != "")) {
                $Id = intval($params['Id']);
            } else {
                throw new \PDOException($errorInfo[0]);
            }
            $contractTypeId = 3;
           /* if ((isset($params['ContractTypeId']) && $params['ContractTypeId'] != "")) {
                $contractTypeId = intval($params['ContractTypeId']);
            } else {
                throw new \PDOException($errorInfo[0]);
            }     
            * 
            */
            $modelId= -1111;
            if ((isset($params['ModelId']) && $params['ModelId'] != "")) {
                $modelId = intval($params['ModelId']);
            } else {
                throw new \PDOException($errorInfo[0]);
            }
            $buybackTypeId = -1111;
            if ((isset($params['BuybackTypeId']) && $params['BuybackTypeId'] != "")) {
                $buybackTypeId = intval($params['BuybackTypeId']);
            } else {
                throw new \PDOException($errorInfo[0]);
            }
            $terrainId = -1111;
            if ((isset($params['TerrainId']) && $params['TerrainId'] != "")) {
                $terrainId = intval($params['TerrainId']);
            } else {
                throw new \PDOException($errorInfo[0]);
            }                           
            $monthId = -1111;
            if ((isset($params['MonthId']) && $params['MonthId'] != "")) {
                $monthId = intval($params['MonthId']);
            } else {
                throw new \PDOException($errorInfo[0]);
            }
            $mileageId = -1111;
            if ((isset($params['MileageId']) && $params['MileageId'] != "")) {
                $mileageId = intval($params['MileageId']);
            } else {
                throw new \PDOException($errorInfo[0]);
            }
            $price = -1111;
            if ((isset($params['Price']) && $params['Price'] != "")) {
                $price = floatval($params['Price']);
            } else {
                throw new \PDOException($errorInfo[0]);
            }               
           

            $opUserIdParams = array('pk' => $params['pk'],);
            $opUserIdArray = $this->slimApp->getBLLManager()->get('opUserIdBLL');
            $opUserId = $opUserIdArray->getUserId($opUserIdParams);
            if (\Utill\Dal\Helper::haveRecord($opUserId)) {
                $opUserIdValue = $opUserId ['resultSet'][0]['user_id'];
                $opUserRoleIdValue = $opUserId ['resultSet'][0]['role_id'];

                $kontrol = $this->haveRecords(
                        array(
                            'name' =>$name, 
                            'id' => $Id
                ));
                if (!\Utill\Dal\Helper::haveRecord($kontrol)) {

                    $this->makePassive(array('id' => $params['Id']));

                    $statementInsert = $pdo->prepare("
                INSERT INTO sys_buyback_matrix (  
                        contract_type_id,
                        model_id,
                        buyback_type_id,
                        terrain_id,
                        month_id,
                        mileage_id,
                        price,
                         
                        op_user_id,
                        act_parent_id 
                        )  
                SELECT  
                    " . intval($contractTypeId) . ",
                    " . intval($modelId) . ",
                    " . intval($buybackTypeId) . ",
                    " . intval($terrainId) . ",
                    " . intval($monthId) . ", 
                    " . intval($mileageId) . ",
                    " . floatval($price) . ", 
               
                    " . intval($opUserIdValue) . " AS op_user_id,  
                    act_parent_id
                FROM sys_buyback_matrix 
                WHERE 
                     id  =" . intval($Id) . "                  
                                                ");
                    $result = $statementInsert->execute();
                    $insertID = $pdo->lastInsertId('sys_buyback_matrix_id_seq');
                    $affectedRows = $statementInsert->rowCount();
                    $errorInfo = $statementInsert->errorInfo();
                    if ($errorInfo[0] != "00000" && $errorInfo[1] != NULL && $errorInfo[2] != NULL)
                        throw new \PDOException($errorInfo[0]);

                    $pdo->commit();
                    return array("found" => true, "errorInfo" => $errorInfo, "affectedRowsCount" => $affectedRows,"lastInsertId" => $insertID);
                } else {
                    $errorInfo = '23505';
                    $errorInfoColumn = 'name';
                    $pdo->rollback();
                    return array("found" => false, "errorInfo" => $errorInfo, "resultSet" => '', "errorInfoColumn" => $errorInfoColumn);
                }
            } else {
                $errorInfo = '23502';   // 23502  user_id not_null_violation
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
