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
 * @since 30.07.2018                          
 */ 
class SysWarranties extends \DAL\DalSlim {

    /**
     * @author Okan CIRAN    
     * @ sys_warranty_matrix tablosundan parametre olarak  gelen id kaydını siler. !!
     * @version v 1.0  30.07.2018
     * @param array $params   
     * @return array  
     * @throws \PDOException  
     */
    public function delete($params = array()) {
     try {
            $pdo = $this->slimApp->getServiceManager()->get('oracleConnectFactory');
            $pdo->beginTransaction();
            $opUserId = InfoUsers::getUserId(array('pk' => $params['pk']));
            if (\Utill\Dal\Helper::haveRecord($opUserId)) {
                $opUserIdValue = $opUserId ['resultSet'][0]['user_id'];                
                $statement = $pdo->prepare(" 
                UPDATE sys_warranty_matrix
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
     * @ sys_warranty_matrix tablosundaki tüm kayıtları getirir.  !!
     * @version v 1.0  30.07.2018  
     * @param array $params
     * @return array
     * @throws \PDOException 
     */
    public function getAll($params = array()) {
        try {
            $pdo = $this->slimApp->getServiceManager()->get('oracleConnectFactory');
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
                FROM sys_warranty_matrix a
                INNER JOIN sys_language l ON l.id = a.language_id AND l.deleted =0 AND l.active = 0 
                LEFT JOIN sys_language lx ON lx.id = ".intval($languageIdValue)." AND lx.deleted =0 AND lx.active =0
                INNER JOIN sys_specific_definitions sd15 ON sd15.main_group = 15 AND sd15.first_group= a.deleted AND sd15.language_id = a.language_id AND sd15.deleted = 0 
                INNER JOIN sys_specific_definitions sd16 ON sd16.main_group = 16 AND sd16.first_group= a.active AND sd16.language_id = a.language_id AND sd16.deleted = 0
                INNER JOIN info_users u ON u.id = a.op_user_id    
                LEFT JOIN sys_specific_definitions sd15x ON (sd15x.id = sd15.id OR sd15x.language_parent_id = sd15.id) AND sd15x.language_id =lx.id  AND sd15x.deleted =0 AND sd15x.active =0 
                LEFT JOIN sys_specific_definitions sd16x ON (sd16x.id = sd16.id OR sd16x.language_parent_id = sd16.id)AND sd16x.language_id = lx.id  AND sd16x.deleted = 0 AND sd16x.active = 0
                LEFT JOIN sys_warranty_matrix ax ON (ax.id = a.id OR ax.language_parent_id = a.id) AND ax.deleted =0 AND ax.active =0 AND lx.id = ax.language_id
                
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
     * @ sys_warranty_matrix tablosuna yeni bir kayıt oluşturur.  !!
     * @version v 1.0  30.07.2018
     * @param type $params
     * @return array
     * @throws \PDOException
     */
    public function insert($params = array()) {
        try {
            $pdo = $this->slimApp->getServiceManager()->get('oracleConnectFactory');
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
                INSERT INTO sys_warranty_matrix(
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
                    $insertID = $pdo->lastInsertId('sys_warranty_matrix_id_seq');
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
     * @ sys_warranty_matrix tablosunda property_name daha önce kaydedilmiş mi ?  
     * @version v 1.0 13.03.2016
     * @param type $params
     * @return array
     * @throws \PDOException
     */
    public function haveRecords($params = array()) {
        try {
            $pdo = $this->slimApp->getServiceManager()->get('oracleConnectFactory');
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
            FROM sys_warranty_matrix  a                          
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
     * sys_warranty_matrix tablosuna parametre olarak gelen id deki kaydın bilgilerini günceller   !!
     * @version v 1.0  30.07.2018
     * @param type $params
     * @return array
     * @throws \PDOException
     */
    public function update($params = array()) {
        try {
            $pdo = $this->slimApp->getServiceManager()->get('oracleConnectFactory');
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
                    UPDATE sys_warranty_matrix
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
     * @ Gridi doldurmak için sys_warranty_matrix tablosundan kayıtları döndürür !!
     * @version v 1.0  30.07.2018
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
            $pdo = $this->slimApp->getServiceManager()->get('oracleConnectFactory');
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
                FROM sys_warranty_matrix a
                INNER JOIN sys_language l ON l.id = a.language_id AND l.deleted =0 AND l.active = 0 
                LEFT JOIN sys_language lx ON lx.id = ".intval($languageIdValue)." AND lx.deleted =0 AND lx.active =0
                INNER JOIN sys_specific_definitions sd15 ON sd15.main_group = 15 AND sd15.first_group= a.deleted AND sd15.language_id = a.language_id AND sd15.deleted = 0 
                INNER JOIN sys_specific_definitions sd16 ON sd16.main_group = 16 AND sd16.first_group= a.active AND sd16.language_id = a.language_id AND sd16.deleted = 0
                INNER JOIN info_users u ON u.id = a.op_user_id    
                LEFT JOIN sys_specific_definitions sd15x ON (sd15x.id = sd15.id OR sd15x.language_parent_id = sd15.id) AND sd15x.language_id =lx.id  AND sd15x.deleted =0 AND sd15x.active =0 
                LEFT JOIN sys_specific_definitions sd16x ON (sd16x.id = sd16.id OR sd16x.language_parent_id = sd16.id)AND sd16x.language_id = lx.id  AND sd16x.deleted = 0 AND sd16x.active = 0
                LEFT JOIN sys_warranty_matrix ax ON (ax.id = a.id OR ax.language_parent_id = a.id) AND ax.deleted =0 AND ax.active =0 AND lx.id = ax.language_id
                
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
     * @ Gridi doldurmak için sys_warranty_matrix tablosundan çekilen kayıtlarının kaç tane olduğunu döndürür   !!
     * @version v 1.0  30.07.2018
     * @param array | null $args
     * @return array
     * @throws \PDOException
     */
    public function fillGridRowTotalCount($params = array()) {
        try {
            $pdo = $this->slimApp->getServiceManager()->get('oracleConnectFactory');
            
            $sql = "
                SELECT 
                     COUNT(a.id) AS COUNT 
                FROM sys_warranty_matrix a
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
     * @ sys_warranty_matrix tablosundan parametre olarak  gelen id kaydın aktifliğini
     *  0(aktif) ise 1 , 1 (pasif) ise 0  yapar. !!
      * @version v 1.0  30.07.2018
     * @param type $params
     * @return array
     * @throws \PDOException
     */
    public function makeActiveOrPassive($params = array()) {
        try {
            $pdo = $this->slimApp->getServiceManager()->get('oracleConnectFactory');
            $pdo->beginTransaction();
            $opUserId = InfoUsers::getUserId(array('pk' => $params['pk']));
            if (\Utill\Dal\Helper::haveRecord($opUserId)) {
                $opUserIdValue = $opUserId ['resultSet'][0]['user_id'];
                if (isset($params['id']) && $params['id'] != "") {

                    $sql = "                 
                UPDATE sys_warranty_matrix
                SET active = (  SELECT   
                                CASE active
                                    WHEN 0 THEN 1
                                    ELSE 0
                                END activex
                                FROM sys_warranty_matrix
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
     * @ garanti matrix tanımlarını grid formatında döndürür !! ana tablo  sys_warranty_matrix 
     * @version v 1.0  20.08.2018
     * @param array | null $args 
     * @return array
     * @throws \PDOException  
     */  
    public function fillWarrantyMatrixGridx($params = array()) {
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
                            case 'vehicle_group_name':
                                $sorguExpression = ' ILIKE \'%' . $std['value'] . '%\' ';
                                $sorguStr.=" AND ird.name" . $sorguExpression . ' ';
                              
                                break;
                            case 'vehicle_config_name':
                                $sorguExpression = ' ILIKE \'%' . $std['value'] . '%\'  ';
                                $sorguStr.=" AND krd.name" . $sorguExpression . ' ';

                                break; 
                             case 'warranty_parent_name':
                                $sorguExpression = ' ILIKE \'%' . $std['value'] . '%\'  ';
                                $sorguStr.=" AND COALESCE(NULLIF(frdx.name, ''), frd.name_eng)" . $sorguExpression . ' ';

                                break; 
                             case 'warranty_name':
                                $sorguExpression = ' ILIKE \'%' . $std['value'] . '%\'  ';
                                $sorguStr.=" AND COALESCE(NULLIF(drdx.name, ''), drd.name_eng)" . $sorguExpression . ' ';

                                break; 
                             case 'warranty_type_name':
                                $sorguExpression = ' ILIKE \'%' . $std['value'] . '%\'  ';
                                $sorguStr.=" AND COALESCE(NULLIF(erdx.name, ''), erd.name_eng)" . $sorguExpression . ' ';

                                break; 
                             case 'month1_name':
                                $sorguExpression = ' ILIKE \'%' . $std['value'] . '%\'  ';
                                $sorguStr.=" AND COALESCE(NULLIF(grdx.name, ''), grd.name_eng)" . $sorguExpression . ' ';

                                break; 
                             case 'month2_name':
                                $sorguExpression = ' ILIKE \'%' . $std['value'] . '%\'  ';
                                $sorguStr.=" AND COALESCE(NULLIF(hrdx.name, ''), hrd.name_eng)" . $sorguExpression . ' ';

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

            $pdo = $this->slimApp->getServiceManager()->get('oracleConnectFactory');       
                            
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
             $ismaintenanceID =0 ;
            if (isset($params['IsmaintenanceID']) && $params['IsmaintenanceID'] != "") {
                $ismaintenanceID = $params['IsmaintenanceID'];
                $addSql ="  a.ismaintenance = " . intval($ismaintenanceID). "  AND  " ; 
            }  
            $vehicleGroupID=0 ;
            if (isset($params['VehicleGroupID']) && $params['VehicleGroupID'] != "") {
                $vehicleGroupID = $params['VehicleGroupID'];
                $addSql =" ird.vehicle_group_id = " . intval($vehicleGroupID). "  AND  " ; 
            }  
            $vehicleGroupTypeID=0 ;
            if (isset($params['VehicleGroupTypeID']) && $params['VehicleGroupTypeID'] != "") {
                $vehicleGroupTypeID = $params['VehicleGroupTypeID'];
                $addSql ="  a.vehicle_config_type_id = " . intval($vehicleGroupTypeID). "  AND  " ; 
            }  
            $warrantyParentID=0 ;
            if (isset($params['WarrantyParentID']) && $params['WarrantyParentID'] != "") {
                $warrantyParentID = $params['WarrantyParentID'];
                $addSql ="  drd.parent_id= " . intval($warrantyParentID). "  AND  " ; 
            }  
            $warrantyTypeID=0 ;
            if (isset($params['WarrantyTypeID']) && $params['WarrantyTypeID'] != "") {
                $warrantyTypeID = $params['WarrantyTypeID'];
                $addSql ="  a.warranty_types_id = " . intval($warrantyTypeID). "  AND  " ; 
            }   
            $warrantyTypeID=0 ;
            if (isset($params['WarrantyTypeID']) && $params['WarrantyTypeID'] != "") {
                $warrantyTypeID = $params['WarrantyTypeID'];
                $addSql ="  a.warranty_types_id = " . intval($warrantyTypeID). "  AND  " ; 
            }  
                $sql = "
                    SELECT  
                        a.id,  
			ird.vehicle_group_id,
			ird.name  AS vehicle_group_name, 
			a.vehicle_config_type_id, 
			krd.name  AS vehicle_config_name, 
			a.warranty_id warranty_parent_id,
                        COALESCE(NULLIF(frdx.name, ''), frd.name_eng) AS warranty_parent_name,
			a.warranty_id,
                        COALESCE(NULLIF(drdx.name, ''), drd.name_eng) AS warranty_name,
                      /*  a.name_eng, */
			a.warranty_types_id,
			COALESCE(NULLIF(erdx.name, ''), erd.name_eng) AS warranty_type_name, 
			a.months1_id,
			COALESCE(NULLIF(grdx.name, ''), grd.name_eng) AS month1_name,
			grd.mvalue, 
			a.months2_id,
			COALESCE(NULLIF(hrdx.name, ''), hrd.name_eng) AS month2_name,
			hrd.mvalue, 
			a.ismaintenance,
			COALESCE(NULLIF(sd19x.description, ''), sd19.description_eng) AS  maintenance,
                        a.act_parent_id,   
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
                    FROM sys_warranty_matrix a                    
                    INNER JOIN sys_language l ON l.id = 385 AND l.show_it =0
                    LEFT JOIN sys_language lx ON lx.id =" . intval($languageIdValue) . "  AND lx.show_it =0  
                    INNER JOIN info_users u ON u.id = a.op_user_id 
                    /*----*/   
		    INNER JOIN sys_warranties drd ON drd.act_parent_id = a.warranty_id AND drd.show_it = 0 AND drd.language_id= l.id
		    LEFT JOIN sys_warranties drdx ON (drdx.act_parent_id = drd.act_parent_id OR drdx.language_parent_id= drd.act_parent_id) AND drdx.show_it= 0 AND drdx.language_id =lx.id  
 
		    INNER JOIN sys_warranty_types erd ON erd.act_parent_id = a.warranty_id AND erd.show_it = 0 AND erd.language_id= l.id
		    LEFT JOIN sys_warranty_types erdx ON (erdx.act_parent_id = erd.act_parent_id OR erdx.language_parent_id= erd.act_parent_id) AND erdx.show_it= 0 AND erdx.language_id =lx.id  
                    
		    INNER JOIN sys_warranties frd ON frd.act_parent_id = drd.parent_id AND frd.show_it = 0 AND frd.language_id= l.id and frd.parent_id =0 
		    LEFT JOIN sys_warranties frdx ON (frdx.act_parent_id = frd.act_parent_id OR frdx.language_parent_id= frd.act_parent_id) AND frdx.active =0 AND frdx.deleted = 0 AND frdx.language_id =lx.id  
                  
		    INNER JOIN sys_months grd ON grd.act_parent_id = a.months1_id AND grd.show_it = 0 AND grd.language_id= l.id  
		    LEFT JOIN sys_months grdx ON (grdx.act_parent_id = grd.act_parent_id OR grdx.language_parent_id= grd.act_parent_id) AND grdx.active =0 AND grdx.deleted = 0 AND grdx.language_id =lx.id  
                  
		    INNER JOIN sys_months hrd ON hrd.act_parent_id = a.months2_id AND hrd.show_it = 0 AND hrd.language_id= l.id  
		    LEFT JOIN sys_months hrdx ON (hrdx.act_parent_id = hrd.act_parent_id OR hrdx.language_parent_id= hrd.act_parent_id) AND hrdx.active =0 AND hrdx.deleted = 0 AND hrdx.language_id =lx.id  
                  
		    INNER JOIN sys_vehicle_groups ird ON ird.act_parent_id = a.vehicle_group_id AND ird.show_it  = 0  
		    INNER JOIN sys_vehicle_config_types krd ON krd.act_parent_id = a.vehicle_config_type_id AND krd.show_it  = 0  

                    /*----*/   
                   /* INNER JOIN sys_specific_definitions sd15 ON sd15.main_group = 15 AND sd15.first_group= a.deleted AND sd15.deleted =0 AND sd15.active =0 AND sd15.language_parent_id =0 */
                    INNER JOIN sys_specific_definitions sd16 ON sd16.main_group = 16 AND sd16.first_group= a.active AND sd16.deleted = 0 AND sd16.active = 0 AND sd16.language_id =l.id
                    /**/
                  /*  LEFT JOIN sys_specific_definitions sd15x ON sd15x.language_id =lx.id AND (sd15x.id = sd15.id OR sd15x.language_parent_id = sd15.id) AND sd15x.deleted =0 AND sd15x.active =0  */
                    LEFT JOIN sys_specific_definitions sd16x ON sd16x.language_id = lx.id AND (sd16x.id = sd16.id OR sd16x.language_parent_id = sd16.id) AND sd16x.deleted = 0 AND sd16x.active = 0

		    INNER JOIN sys_specific_definitions sd19 ON sd19.main_group = 19 AND sd19.first_group= a.ismaintenance AND sd19.deleted = 0 AND sd19.active = 0 AND sd19.language_id =l.id
		    LEFT JOIN sys_specific_definitions sd19x ON sd19x.language_id = lx.id AND (sd19x.id = sd19.id OR sd19x.language_parent_id = sd19.id) AND sd19x.deleted = 0 AND sd19x.active = 0
                      
                    WHERE  
                        a.deleted =0 AND
                        a.show_it =0  
                     
                " . $addSql . "
                " . $sorguStr . " 
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
     * @ garanti matrix   tanımlarını grid formatında gösterilirken kaç kayıt olduğunu döndürür !! ana tablo  sys_warranty_matrix 
     * @version v 1.0  20.08.2018
     * @param array | null $args
     * @return array
     * @throws \PDOException  
     */  
    public function fillWarrantyMatrixGridxRtl($params = array()) {
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
                           case 'vehicle_group_name':
                                $sorguExpression = ' ILIKE \'%' . $std['value'] . '%\' ';
                                $sorguStr.=" AND ird.name" . $sorguExpression . ' ';
                              
                                break;
                            case 'vehicle_config_name':
                                $sorguExpression = ' ILIKE \'%' . $std['value'] . '%\'  ';
                                $sorguStr.=" AND krd.name" . $sorguExpression . ' ';

                                break; 
                             case 'warranty_parent_name':
                                $sorguExpression = ' ILIKE \'%' . $std['value'] . '%\'  ';
                                $sorguStr.=" AND COALESCE(NULLIF(frdx.name, ''), frd.name_eng)" . $sorguExpression . ' ';

                                break; 
                             case 'warranty_name':
                                $sorguExpression = ' ILIKE \'%' . $std['value'] . '%\'  ';
                                $sorguStr.=" AND COALESCE(NULLIF(drdx.name, ''), drd.name_eng)" . $sorguExpression . ' ';

                                break; 
                             case 'warranty_type_name':
                                $sorguExpression = ' ILIKE \'%' . $std['value'] . '%\'  ';
                                $sorguStr.=" AND COALESCE(NULLIF(erdx.name, ''), erd.name_eng)" . $sorguExpression . ' ';

                                break; 
                             case 'month1_name':
                                $sorguExpression = ' ILIKE \'%' . $std['value'] . '%\'  ';
                                $sorguStr.=" AND COALESCE(NULLIF(grdx.name, ''), grd.name_eng)" . $sorguExpression . ' ';

                                break; 
                             case 'month2_name':
                                $sorguExpression = ' ILIKE \'%' . $std['value'] . '%\'  ';
                                $sorguStr.=" AND COALESCE(NULLIF(hrdx.name, ''), hrd.name_eng)" . $sorguExpression . ' ';

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

            $pdo = $this->slimApp->getServiceManager()->get('oracleConnectFactory');       
                            
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
            $ismaintenanceID =0 ;
            if (isset($params['IsmaintenanceID']) && $params['IsmaintenanceID'] != "") {
                $ismaintenanceID = $params['IsmaintenanceID'];
                $addSql ="  a.ismaintenance = " . intval($ismaintenanceID). "  AND  " ; 
            }  
            $vehicleGroupID=0 ;
            if (isset($params['VehicleGroupID']) && $params['VehicleGroupID'] != "") {
                $vehicleGroupID = $params['VehicleGroupID'];
                $addSql =" ird.vehicle_group_id = " . intval($vehicleGroupID). "  AND  " ; 
            }  
            $vehicleGroupTypeID=0 ;
            if (isset($params['VehicleGroupTypeID']) && $params['VehicleGroupTypeID'] != "") {
                $vehicleGroupTypeID = $params['VehicleGroupTypeID'];
                $addSql ="  a.vehicle_config_type_id = " . intval($vehicleGroupTypeID). "  AND  " ; 
            }  
            $warrantyParentID=0 ;
            if (isset($params['WarrantyParentID']) && $params['WarrantyParentID'] != "") {
                $warrantyParentID = $params['WarrantyParentID'];
                $addSql ="  drd.parent_id= " . intval($warrantyParentID). "  AND  " ; 
            }  
            $warrantyTypeID=0 ;
            if (isset($params['WarrantyTypeID']) && $params['WarrantyTypeID'] != "") {
                $warrantyTypeID = $params['WarrantyTypeID'];
                $addSql ="  a.warranty_types_id = " . intval($warrantyTypeID). "  AND  " ; 
            }   
            $warrantyTypeID=0 ;
            if (isset($params['WarrantyTypeID']) && $params['WarrantyTypeID'] != "") {
                $warrantyTypeID = $params['WarrantyTypeID'];
                $addSql ="  a.warranty_types_id = " . intval($warrantyTypeID). "  AND  " ; 
            }  
                $sql = "
                   SELECT COUNT(asdx.id) count FROM ( 
                        SELECT  
                            a.id,  
                            a.vehicle_group_id,
                            ird.name  AS vehicle_group_name, 
                            a.vehicle_config_type_id, 
                            krd.name  AS vehicle_config_name, 
                            drd.parent_id warranty_parent_id,
                            COALESCE(NULLIF(frdx.name, ''), frd.name_eng) AS warranty_parent_name,
                            a.warranty_id,
                            COALESCE(NULLIF(drdx.name, ''), drd.name_eng) AS warranty_name, 
                            a.warranty_types_id,
                            COALESCE(NULLIF(erdx.name, ''), erd.name_eng) AS warranty_type_name, 
                            a.months1_id,
                            COALESCE(NULLIF(grdx.name, ''), grd.name_eng) AS month1_name,
                            grd.mvalue, 
                            a.months2_id,
                            COALESCE(NULLIF(hrdx.name, ''), hrd.name_eng) AS month2_name, 
                            a.ismaintenance,
                            COALESCE(NULLIF(sd19x.description, ''), sd19.description_eng) AS  maintenance, 
                            COALESCE(NULLIF(sd16x.description, ''), sd16.description_eng) AS state_active, 
                            u.username AS op_user_name 
                        FROM sys_warranty_matrix a                    
                        INNER JOIN sys_language l ON l.id = 385 AND l.show_it =0
                        LEFT JOIN sys_language lx ON lx.id =" . intval($languageIdValue) . "  AND lx.show_it =0  
                        INNER JOIN info_users u ON u.id = a.op_user_id 
                        /*----*/   
                        INNER JOIN sys_warranties drd ON drd.act_parent_id = a.warranty_id AND drd.show_it = 0 AND drd.language_id= l.id
                        LEFT JOIN sys_warranties drdx ON (drdx.act_parent_id = drd.act_parent_id OR drdx.language_parent_id= drd.act_parent_id) AND drdx.show_it= 0 AND drdx.language_id =lx.id  

                        INNER JOIN sys_warranty_types erd ON erd.act_parent_id = a.warranty_id AND erd.show_it = 0 AND erd.language_id= l.id
                        LEFT JOIN sys_warranty_types erdx ON (erdx.act_parent_id = erd.act_parent_id OR erdx.language_parent_id= erd.act_parent_id) AND erdx.show_it= 0 AND erdx.language_id =lx.id  

                        INNER JOIN sys_warranties frd ON frd.act_parent_id = drd.parent_id AND frd.show_it = 0 AND frd.language_id= l.id and frd.parent_id =0 
                        LEFT JOIN sys_warranties frdx ON (frdx.act_parent_id = frd.act_parent_id OR frdx.language_parent_id= frd.act_parent_id) AND frdx.active =0 AND frdx.deleted = 0 AND frdx.language_id =lx.id  

                        INNER JOIN sys_months grd ON grd.act_parent_id = a.months1_id AND grd.show_it = 0 AND grd.language_id= l.id  
                        LEFT JOIN sys_months grdx ON (grdx.act_parent_id = grd.act_parent_id OR grdx.language_parent_id= grd.act_parent_id) AND grdx.active =0 AND grdx.deleted = 0 AND grdx.language_id =lx.id  

                        INNER JOIN sys_months hrd ON hrd.act_parent_id = a.months2_id AND hrd.show_it = 0 AND hrd.language_id= l.id  
                        LEFT JOIN sys_months hrdx ON (hrdx.act_parent_id = hrd.act_parent_id OR hrdx.language_parent_id= hrd.act_parent_id) AND hrdx.active =0 AND hrdx.deleted = 0 AND hrdx.language_id =lx.id  

                        INNER JOIN sys_vehicle_groups ird ON ird.act_parent_id = a.vehicle_group_id AND ird.show_it  = 0  
                        INNER JOIN sys_vehicle_config_types krd ON krd.act_parent_id = a.vehicle_config_type_id AND krd.show_it  = 0  

                        /*----*/   
                       /* INNER JOIN sys_specific_definitions sd15 ON sd15.main_group = 15 AND sd15.first_group= a.deleted AND sd15.deleted =0 AND sd15.active =0 AND sd15.language_parent_id =0 */
                        INNER JOIN sys_specific_definitions sd16 ON sd16.main_group = 16 AND sd16.first_group= a.active AND sd16.deleted = 0 AND sd16.active = 0 AND sd16.language_id =l.id
                        /**/
                      /*  LEFT JOIN sys_specific_definitions sd15x ON sd15x.language_id =lx.id AND (sd15x.id = sd15.id OR sd15x.language_parent_id = sd15.id) AND sd15x.deleted =0 AND sd15x.active =0  */
                        LEFT JOIN sys_specific_definitions sd16x ON sd16x.language_id = lx.id AND (sd16x.id = sd16.id OR sd16x.language_parent_id = sd16.id) AND sd16x.deleted = 0 AND sd16x.active = 0

                        INNER JOIN sys_specific_definitions sd19 ON sd19.main_group = 19 AND sd19.first_group= a.ismaintenance AND sd19.deleted = 0 AND sd19.active = 0 AND sd19.language_id =l.id
                        LEFT JOIN sys_specific_definitions sd19x ON sd19x.language_id = lx.id AND (sd19x.id = sd19.id OR sd19x.language_parent_id = sd19.id) AND sd19x.deleted = 0 AND sd19x.active = 0

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
     * @ sys_warranty_matrix tablosundan parametre olarak  gelen id kaydını active ve show_it alanlarını 1 yapar. !!
     * @version v 1.0  24.08.2018
     * @param type $params
     * @return array
     * @throws \PDOException
     */
    public function makePassive($params = array()) {
        try {
            $pdo = $this->slimApp->getServiceManager()->get('oracleConnectFactory'); 
            $statement = $pdo->prepare(" 
                UPDATE sys_warranty_matrix
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
     * @ sys_warranty_matrix tablosundan parametre olarak  gelen id kaydın active veshow_it  alanını 1 yapar ve 
     * yeni yeni kayıt oluşturarak deleted ve active = 1  show_it =0 olarak  yeni kayıt yapar. !  
     * @version v 1.0  24.08.2018
     * @param array | null $args
     * @return array
     * @throws \PDOException
     */
    public function deletedAct($params = array()) {
        $pdo = $this->slimApp->getServiceManager()->get('oracleConnectFactory');
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
                    INSERT INTO sys_warranty_matrix (
                        warranty_id,
                        vehicle_group_id,
                        vehicle_config_type_id,
                        months1_id,
                        mileages1_id,
                        months2_id,
                        mileages2_id,
                        warranty_types_id,
                        ismaintenance,
                        unique_code,
                        price_in_euros, 
                         
                        active,
                        deleted,
                        op_user_id,
                        act_parent_id,
                        show_it
                        )
                    SELECT
                        warranty_id,
                        vehicle_group_id,
                        vehicle_config_type_id,
                        months1_id,
                        mileages1_id,
                        months2_id,
                        mileages2_id,
                        warranty_types_id,
                        ismaintenance,
                        unique_code,
                        price_in_euros, 
                         
                        1 AS active,  
                        1 AS deleted, 
                        " . intval($opUserIdValue) . " AS op_user_id, 
                        act_parent_id,
                        0 AS show_it 
                    FROM sys_warranty_matrix 
                    WHERE id  =" . intval($params['id']) . "    
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
     * @ sys_warranty_matrix tablosuna yeni bir kayıt oluşturur.  !! 
     * @version v 1.0  26.08.2018
     * @param type $params
     * @return array
     * @throws \PDOException
     */
    public function insertAct($params = array()) {
        try {
            $pdo = $this->slimApp->getServiceManager()->get('oracleConnectFactory');
            $pdo->beginTransaction();
                           
            $errorInfo[0] = "99999";
            $warrantyId = -1111;
            if ((isset($params['WarrantyId']) && $params['WarrantyId'] != "")) {
                $warrantyId = intval($params['WarrantyId']);
            } else {
                throw new \PDOException($errorInfo[0]);
            }
             $vehicleConfigTypeId= -1111;
            if ((isset($params['VehicleConfigTypeId']) && $params['VehicleConfigTypeId'] != "")) {
                $vehicleConfigTypeId = intval($params['VehicleConfigTypeId']);
            } else {
                throw new \PDOException($errorInfo[0]);
            }
            $months1Id = -1111;
            if ((isset($params['Months1Id']) && $params['Months1Id'] != "")) {
                $months1Id = intval($params['Months1Id']);
            } else {
                throw new \PDOException($errorInfo[0]);
            }
            $months2Id = -1111;
            if ((isset($params['Months2Id']) && $params['Months2Id'] != "")) {
                $months2Id = intval($params['Months2Id']);
            } else {
                throw new \PDOException($errorInfo[0]);
            }
            $mileages1Id = -1111;
            if ((isset($params['Mileages1Id']) && $params['Mileages1Id'] != "")) {
                $mileages1Id = intval($params['Mileages1Id']);
            } else {
                throw new \PDOException($errorInfo[0]);
            }
            $mileages2Id = -1111;
            if ((isset($params['Mileages2Id']) && $params['Mileages2Id'] != "")) {
                $mileages2Id = intval($params['Mileages2Id']);
            } else {
                throw new \PDOException($errorInfo[0]);
            }
            $warrantyTypesId = -1111;
            if ((isset($params['WarrantyTypesId']) && $params['WarrantyTypesId'] != "")) {
                $warrantyTypesId = intval($params['WarrantyTypesId']);
            } else {
                throw new \PDOException($errorInfo[0]);
            }
            $ismaintenance = -1111;
            if ((isset($params['Ismaintenance']) && $params['Ismaintenance'] != "")) {
                $ismaintenance = intval($params['Ismaintenance']);
            } else {
                throw new \PDOException($errorInfo[0]);
            }
            $uniqueCode = -1111;
            if ((isset($params['UniqueCode']) && $params['UniqueCode'] != "")) {
                $uniqueCode = intval($params['UniqueCode']);
            } else {
                throw new \PDOException($errorInfo[0]);
            }
            $priceInEuros = -1111;
            if ((isset($params['PriceInEuros']) && $params['PriceInEuros'] != "")) {
                $priceInEuros = intval($params['PriceInEuros']);
            } else {
                throw new \PDOException($errorInfo[0]);
            }
                           
            $opUserId = InfoUsers::getUserId(array('pk' => $params['pk']));
            if (\Utill\Dal\Helper::haveRecord($opUserId)) {
                $opUserIdValue = $opUserId ['resultSet'][0]['user_id'];

                $kontrol = $this->haveRecords(
                        array(
                            'warranty_id' => $warrantyId,
                            'vehicle_config_type_id' => $vehicleConfigTypeId,
                            'months1_id' =>  $months1Id,
                            'mileages1_id' => $mileages1Id,
                            'months2_id' => $months2Id,
                            'mileages2_id' => $mileages2Id,
                            'warranty_types_id' => $warrantyTypesId,
                            'ismaintenance' => $ismaintenance,
                            'unique_code' => $uniqueCode,
                           
                ));
                if (!\Utill\Dal\Helper::haveRecord($kontrol)) {
                    $sql = "
                    INSERT INTO sys_warranty_matrix(
                            warranty_id, 
                            vehicle_config_type_id,
                            months1_id,
                            mileages1_id,
                            months2_id,
                            mileages2_id,
                            warranty_types_id,
                            ismaintenance,
                            unique_code,
                            price_in_euros, 

                            op_user_id,
                            act_parent_id  
                            )
                    VALUES ( 
                            " . intval($warrantyId) . ",
                            " . intval($vehicleConfigTypeId) . ",
                            " . intval($months1Id) . ",
                            " . intval($mileages1Id) . ",
                            " . intval($months2Id) . ",
                            " . intval($mileages2Id) . ",
                            " . intval($warrantyTypesId) . ", 
                            " . intval($ismaintenance) . ", 
                            " . intval($priceInEuros) . ", 
                            " . intval($uniqueCode) . ",
                                                                          

                            " . intval($opUserIdValue) . ",
                           (SELECT last_value FROM sys_warranty_matrix_id_seq)
                                                 )   ";
                    $statement = $pdo->prepare($sql);
                    //   echo debugPDO($sql, $params);
                    $result = $statement->execute();
                    $errorInfo = $statement->errorInfo();
                    if ($errorInfo[0] != "00000" && $errorInfo[1] != NULL && $errorInfo[2] != NULL)
                        throw new \PDOException($errorInfo[0]);
                    $insertID = $pdo->lastInsertId('sys_warranty_matrix_id_seq');
                           
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
     * sys_warranty_matrix tablosuna parametre olarak gelen id deki kaydın bilgilerini günceller   !!
     * @version v 1.0  26.08.2018
     * @param type $params
     * @return array
     * @throws \PDOException
     */
    public function updateAct($params = array()) {
        try {
            $pdo = $this->slimApp->getServiceManager()->get('oracleConnectFactory');
            $pdo->beginTransaction();
            $errorInfo[0] = "99999";
                           
            $Id = -1111;
            if ((isset($params['Id']) && $params['Id'] != "")) {
                $Id = intval($params['Id']);
            } else {
                throw new \PDOException($errorInfo[0]);
            }
            
              $warrantyId = -1111;
            if ((isset($params['WarrantyId']) && $params['WarrantyId'] != "")) {
                $warrantyId = intval($params['WarrantyId']);
            } else {
                throw new \PDOException($errorInfo[0]);
            }
             $vehicleConfigTypeId= -1111;
            if ((isset($params['VehicleConfigTypeId']) && $params['VehicleConfigTypeId'] != "")) {
                $vehicleConfigTypeId = intval($params['VehicleConfigTypeId']);
            } else {
                throw new \PDOException($errorInfo[0]);
            }
            $months1Id = -1111;
            if ((isset($params['Months1Id']) && $params['Months1Id'] != "")) {
                $months1Id = intval($params['Months1Id']);
            } else {
                throw new \PDOException($errorInfo[0]);
            }
            $months2Id = -1111;
            if ((isset($params['Months2Id']) && $params['Months2Id'] != "")) {
                $months2Id = intval($params['Months2Id']);
            } else {
                throw new \PDOException($errorInfo[0]);
            }
            $mileages1Id = -1111;
            if ((isset($params['Mileages1Id']) && $params['Mileages1Id'] != "")) {
                $mileages1Id = intval($params['Mileages1Id']);
            } else {
                throw new \PDOException($errorInfo[0]);
            }
            $mileages2Id = -1111;
            if ((isset($params['Mileages2Id']) && $params['Mileages2Id'] != "")) {
                $mileages2Id = intval($params['Mileages2Id']);
            } else {
                throw new \PDOException($errorInfo[0]);
            }
            $warrantyTypesId = -1111;
            if ((isset($params['WarrantyTypesId']) && $params['WarrantyTypesId'] != "")) {
                $warrantyTypesId = intval($params['WarrantyTypesId']);
            } else {
                throw new \PDOException($errorInfo[0]);
            }
            $ismaintenance = -1111;
            if ((isset($params['Ismaintenance']) && $params['Ismaintenance'] != "")) {
                $ismaintenance = intval($params['Ismaintenance']);
            } else {
                throw new \PDOException($errorInfo[0]);
            }
            $uniqueCode = -1111;
            if ((isset($params['UniqueCode']) && $params['UniqueCode'] != "")) {
                $uniqueCode = intval($params['UniqueCode']);
            } else {
                throw new \PDOException($errorInfo[0]);
            }
            $priceInEuros = -1111;
            if ((isset($params['PriceInEuros']) && $params['PriceInEuros'] != "")) {
                $priceInEuros = intval($params['PriceInEuros']);
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
                            'warranty_id' => $warrantyId,
                            'vehicle_config_type_id' => $vehicleConfigTypeId,
                            'months1_id' =>  $months1Id,
                            'mileages1_id' => $mileages1Id,
                            'months2_id' => $months2Id,
                            'mileages2_id' => $mileages2Id,
                            'warranty_types_id' => $warrantyTypesId,
                            'ismaintenance' => $ismaintenance,
                            'unique_code' => $uniqueCode,
                            'id' => $Id
                ));
                if (!\Utill\Dal\Helper::haveRecord($kontrol)) {

                    $this->makePassive(array('id' => $params['id']));

                    $statementInsert = $pdo->prepare("
                INSERT INTO sys_warranty_matrix (  
                        warranty_id, 
                        vehicle_config_type_id,
                        months1_id,
                        mileages1_id,
                        months2_id,
                        mileages2_id,
                        warranty_types_id,
                        ismaintenance,
                        unique_code,
                        price_in_euros, 
                        
                        priority,
                        language_id,
                        language_parent_id,
                        op_user_id,
                        act_parent_id 
                        )  
                SELECT  
                    " . intval($warrantyId) . ",
                    " . intval($vehicleConfigTypeId) . ",
                    " . intval($months1Id) . ",
                    " . intval($mileages1Id) . ",
                    " . intval($months2Id) . ",
                    " . intval($mileages2Id) . ",
                    " . intval($warrantyTypesId) . ", 
                    " . intval($ismaintenance) . ", 
                    " . intval($priceInEuros) . ", 
                    " . intval($uniqueCode) . ",
                                                              
                    " . intval($opUserIdValue) . " AS op_user_id,  
                    act_parent_id
                FROM sys_warranty_matrix 
                WHERE 
                    language_id = 385 AND id  =" . intval($Id) . "                  
                                                ");
                    $result = $statementInsert->execute();
                    $insertID = $pdo->lastInsertId('sys_warranty_matrix_id_seq');
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
