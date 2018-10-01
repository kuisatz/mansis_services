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
 * @since 30.07.2018                         
 */ 
class SysVehicles extends \DAL\DalSlim {

    /**
     * @author Okan CIRAN    
     * @ sys_vehicles tablosundan parametre olarak  gelen id kaydını siler. !!
     * @version v 1.0  30.07.2018
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
                UPDATE sys_vehicles
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
     * @ sys_vehicles tablosundaki tüm kayıtları getirir.  !!
     * @version v 1.0  30.07.2018  
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
                FROM sys_vehicles a
                INNER JOIN sys_language l ON l.id = a.language_id AND l.deleted =0 AND l.active = 0 
                LEFT JOIN sys_language lx ON lx.id = ".intval($languageIdValue)." AND lx.deleted =0 AND lx.active =0
                INNER JOIN sys_specific_definitions sd15 ON sd15.main_group = 15 AND sd15.first_group= a.deleted AND sd15.language_id = a.language_id AND sd15.deleted = 0 
                INNER JOIN sys_specific_definitions sd16 ON sd16.main_group = 16 AND sd16.first_group= a.active AND sd16.language_id = a.language_id AND sd16.deleted = 0
                INNER JOIN info_users u ON u.id = a.op_user_id    
                LEFT JOIN sys_specific_definitions sd15x ON (sd15x.id = sd15.id OR sd15x.language_parent_id = sd15.id) AND sd15x.language_id =lx.id  AND sd15x.deleted =0 AND sd15x.active =0 
                LEFT JOIN sys_specific_definitions sd16x ON (sd16x.id = sd16.id OR sd16x.language_parent_id = sd16.id)AND sd16x.language_id = lx.id  AND sd16x.deleted = 0 AND sd16x.active = 0
                LEFT JOIN sys_vehicles ax ON (ax.id = a.id OR ax.language_parent_id = a.id) AND ax.deleted =0 AND ax.active =0 AND lx.id = ax.language_id
                
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
     * @ sys_vehicles tablosuna yeni bir kayıt oluşturur.  !!
     * @version v 1.0  30.07.2018
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
                INSERT INTO sys_vehicles(
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
                    $insertID = $pdo->lastInsertId('sys_vehicles_id_seq');
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
     * @ sys_vehicles tablosunda property_name daha önce kaydedilmiş mi ?  
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
                a.description ,
                '" . $params['description'] . "' AS value, 
                LOWER(a.description) = LOWER(TRIM('" . $params['description'] . "')) AS control,
                CONCAT(a.description, ' daha önce kayıt edilmiş. Lütfen Kontrol Ediniz !!!' ) AS message
            FROM sys_vehicles  a                          
            WHERE 
               a.ckdcbu_type_id = ".  $params['ckdcbu_type_id'] ."  AND 
                   a.vehicle_gt_model_id = ".  $params['vehicle_gt_model_id'] ."  AND 
                    a.model_variant_id = ".  $params['model_variant_id'] ."  AND 
                    a.config_type_id = ".  $params['config_type_id'] ."  AND 
                    a.cap_type_id = ".  $params['cap_type_id'] ."  AND 
                    a.vehicle_app_type_id = ".  $params['vehicle_app_type_id'] ."  AND 
                    a.kpnumber_id = ".  $params['kpnumber_id'] ."  AND   
                    a.btsbto_type_id = ".  $params['btsbto_type_id'] ."  AND   
                    a.roadtype_id = ".  $params['roadtype_id'] ."      

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
     * sys_vehicles tablosuna parametre olarak gelen id deki kaydın bilgilerini günceller   !!
     * @version v 1.0  30.07.2018
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
                    UPDATE sys_vehicles
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
     * @ Gridi doldurmak için sys_vehicles tablosundan kayıtları döndürür !!
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
                FROM sys_vehicles a
                INNER JOIN sys_language l ON l.id = a.language_id AND l.deleted =0 AND l.active = 0 
                LEFT JOIN sys_language lx ON lx.id = ".intval($languageIdValue)." AND lx.deleted =0 AND lx.active =0
                INNER JOIN sys_specific_definitions sd15 ON sd15.main_group = 15 AND sd15.first_group= a.deleted AND sd15.language_id = a.language_id AND sd15.deleted = 0 
                INNER JOIN sys_specific_definitions sd16 ON sd16.main_group = 16 AND sd16.first_group= a.active AND sd16.language_id = a.language_id AND sd16.deleted = 0
                INNER JOIN info_users u ON u.id = a.op_user_id    
                LEFT JOIN sys_specific_definitions sd15x ON (sd15x.id = sd15.id OR sd15x.language_parent_id = sd15.id) AND sd15x.language_id =lx.id  AND sd15x.deleted =0 AND sd15x.active =0 
                LEFT JOIN sys_specific_definitions sd16x ON (sd16x.id = sd16.id OR sd16x.language_parent_id = sd16.id)AND sd16x.language_id = lx.id  AND sd16x.deleted = 0 AND sd16x.active = 0
                LEFT JOIN sys_vehicles ax ON (ax.id = a.id OR ax.language_parent_id = a.id) AND ax.deleted =0 AND ax.active =0 AND lx.id = ax.language_id
                
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
     * @ Gridi doldurmak için sys_vehicles tablosundan çekilen kayıtlarının kaç tane olduğunu döndürür   !!
     * @version v 1.0  30.07.2018
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
                FROM sys_vehicles a
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
     * @ sys_vehicles tablosundan parametre olarak  gelen id kaydın aktifliğini
     *  0(aktif) ise 1 , 1 (pasif) ise 0  yapar. !!
      * @version v 1.0  30.07.2018
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
                UPDATE sys_vehicles
                SET active = (  SELECT   
                                CASE active
                                    WHEN 0 THEN 1
                                    ELSE 0
                                END activex
                                FROM sys_vehicles
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
     * @ body aksesuar tanımlarını grid formatında döndürür !! ana tablo  sys_vehicles 
     * @version v 1.0  15.08.2018
     * @param array | null $args
     * @return array
     * @throws \PDOException  
     */  
    public function fillVehiclesGridx($params = array()) {
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
                            case 'cbuckd_name':
                                $sorguExpression = ' ILIKE \'%' . $std['value'] . '%\' ';
                                $sorguStr.=" AND c.name" . $sorguExpression . ' ';
                              
                                break;
                            case 'gt_model_name':
                                $sorguExpression = ' ILIKE \'%' . $std['value'] . '%\'  ';
                                $sorguStr.=" AND b.name" . $sorguExpression . ' ';

                                break;  
                             case 'variant_name':
                                $sorguExpression = ' ILIKE \'%' . $std['value'] . '%\' ';
                                $sorguStr.=" AND d.name" . $sorguExpression . ' ';
                              
                                break;
                            case 'config_type_name':
                                $sorguExpression = ' ILIKE \'%' . $std['value'] . '%\'  ';
                                $sorguStr.=" AND e.name" . $sorguExpression . ' ';

                                break;   
                             case 'cap_type_name':
                                $sorguExpression = ' ILIKE \'%' . $std['value'] . '%\' ';
                                $sorguStr.=" AND f.name" . $sorguExpression . ' ';
                              
                                break;
                            case 'app_type_name':
                                $sorguExpression = ' ILIKE \'%' . $std['value'] . '%\' ';
                                $sorguStr.=" AND h.name" . $sorguExpression . ' ';
                              
                                break;
                            case 'btobts_name':
                                $sorguExpression = ' ILIKE \'%' . $std['value'] . '%\'  ';
                                $sorguStr.=" AND i.name" . $sorguExpression . ' ';

                                break; 
                             case 'kp_name':
                                $sorguExpression = ' ILIKE \'%' . $std['value'] . '%\' ';
                                $sorguStr.=" AND g.name" . $sorguExpression . ' ';
                              
                                break; 
                            case 'road_type_name':
                                $sorguExpression = ' ILIKE \'%' . $std['value'] . '%\'  ';
                                $sorguStr.=" AND COALESCE(NULLIF(kx.name, ''), k.name_eng)" . $sorguExpression . ' ';

                                break;  
                             case 'gfz':
                                $sorguExpression = ' ILIKE \'%' . $std['value'] . '%\' ';
                                $sorguStr.=" AND a.gfz" . $sorguExpression . ' ';
                              
                                break;
                            case 'factorymodel_name':
                                $sorguExpression = ' ILIKE \'%' . $std['value'] . '%\'  ';
                                $sorguStr.=" AND a.factorymodel_name" . $sorguExpression . ' ';

                                break; 
                             case 'description':
                                $sorguExpression = ' ILIKE \'%' . $std['value'] . '%\' ';
                                $sorguStr.=" AND a.description" . $sorguExpression . ' ';
                              
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
            $ckdCbuTypeID =0 ;
            if (isset($params['CkdCbuTypeID']) && $params['CkdCbuTypeID'] != "") {
                $ckdCbuTypeID = $params['CkdCbuTypeID'];
                $addSql .="  a.ckdcbu_type_id  = " . intval($ckdCbuTypeID). "  AND  " ; 
            }  
            $vehicleGtModelID =0 ;
            if (isset($params['VehicleGtModelID']) && $params['VehicleGtModelID'] != "") {
                $vehicleGtModelID = $params['VehicleGtModelID'];
                $addSql .="  a.vehicle_gt_model_id  = " . intval($vehicleGtModelID). "  AND  " ; 
            }  
            $modelVariantID =0 ;
            if (isset($params['ModelVariantID']) && $params['ModelVariantID'] != "") {
                $modelVariantID = $params['ModelVariantID'];
                $addSql .="  a.model_variant_id  = " . intval($modelVariantID). "  AND  " ; 
            }  
            $configTypeID =0 ;
            if (isset($params['ConfigTypeID']) && $params['ConfigTypeID'] != "") {
                $configTypeID = $params['ConfigTypeID'];
                $addSql .="  a.config_type_id  = " . intval($configTypeID). "  AND  " ; 
            }  
            $capTypeID =0 ;
            if (isset($params['CapTypeID']) && $params['CapTypeID'] != "") {
                $capTypeID = $params['CapTypeID'];
                $addSql .="  a.cap_type_id  = " . intval($capTypeID). "  AND  " ; 
            }  
            $vehicleAppTypeID =0 ;
            if (isset($params['VehicleAppTypeID']) && $params['VehicleAppTypeID'] != "") {
                $vehicleAppTypeID = $params['VehicleAppTypeID'];
                $addSql .="  a.vehicle_app_type_id  = " . intval($vehicleAppTypeID). "  AND  " ; 
            }  
            $kpnumberID =0 ;
            if (isset($params['KpnumberID']) && $params['KpnumberID'] != "") {
                $kpnumberID = $params['KpnumberID'];
                $addSql .="  a.kpnumber_id  = " . intval($kpnumberID). "  AND  " ; 
            }  
            $btsBtoTypeID =0 ;
            if (isset($params['BtsBtoTypeID']) && $params['BtsBtoTypeID'] != "") {
                $btsBtoTypeID = $params['BtsBtoTypeID'];
                $addSql .="  a.btsbto_type_id  = " . intval($btsBtoTypeID). "  AND  " ; 
            }  
            $roadTypeID =0 ;
            if (isset($params['RoadTypeID']) && $params['RoadTypeID'] != "") {
                $roadTypeID = $params['RoadTypeID'];
                $addSql .="  a.roadtype_id  = " . intval($roadTypeID). "  AND  " ; 
            }  

                $sql = "
                    SELECT 
                        a.id, 
                        a.act_parent_id as apid,  
			a.ckdcbu_type_id,
			c.name cbuckd_name,
			a.vehicle_gt_model_id,
			b.name gt_model_name,
			a.model_variant_id,
			d.name as variant_name, 
			a.config_type_id,
			e.name AS config_type_name,
			a.cap_type_id,
			f.name AS cap_type_name,
			a.vehicle_app_type_id,
			h.name app_type_name, 
			a.kpnumber_id,
			g.name as kp_name,
			a.btsbto_type_id, 
			i.name as btobts_name,
			a.roadtype_id,
			COALESCE(NULLIF(kx.name, ''), k.name_eng) AS road_type_name, 
			a.gfz,
			a.factorymodel_name,
			a.description, 
                        a.active,
                        COALESCE(NULLIF(sd16x.description, ''), sd16.description_eng) AS state_active, 
                        a.op_user_id,
                        u.username AS op_user_name,  
                        a.s_date date_saved,
                        a.c_date date_modified, 
                        COALESCE(NULLIF(lx.id, NULL), 385) AS language_id, 
                        lx.language_main_code language_code, 
                        COALESCE(NULLIF(lx.language, ''), 'en') AS language_name
                    FROM sys_vehicles a                    
                    INNER JOIN sys_language l ON l.id = 385 AND l.show_it =0
                    LEFT JOIN sys_language lx ON lx.id =" . intval($languageIdValue) . " AND lx.show_it =0  
                    LEFT JOIN sys_vehicles ax ON (ax.act_parent_id = a.act_parent_id OR ax.language_parent_id = a.act_parent_id) AND ax.show_it = 0 AND ax.language_id = lx.id
                    INNER JOIN info_users u ON u.id = a.op_user_id 
                    /*----*/ 
		    INNER JOIN sys_vehicle_gt_models b ON b.act_parent_id = a.vehicle_gt_model_id AND b.show_it = 0 
		    INNER JOIN sys_vehicle_ckdcbu c ON c.act_parent_id = a.ckdcbu_type_id AND c.show_it = 0   
		    INNER JOIN sys_vehicle_model_variants d ON d.act_parent_id = a.ckdcbu_type_id AND d.show_it = 0  
		    INNER JOIN sys_vehicle_config_types e ON e.act_parent_id = a.config_type_id AND e.show_it = 0  
		    INNER JOIN sys_vehicle_cap_types f ON f.act_parent_id = a.cap_type_id AND f.show_it = 0  
		    INNER JOIN sys_vehicle_app_types h ON h.act_parent_id = a.cap_type_id AND h.show_it = 0  
		    INNER JOIN sys_kpnumbers g ON g.act_parent_id = a.kpnumber_id AND g.show_it = 0  
		    INNER JOIN sys_vehicle_btobts i ON i.act_parent_id = a.kpnumber_id AND i.show_it = 0   
		    INNER JOIN sys_roadtypes k ON k.act_parent_id = a.roadtype_id AND k.show_it = 0 AND k.language_id= l.id
                    LEFT JOIN sys_roadtypes kx ON (kx.act_parent_id = k.act_parent_id OR kx.language_parent_id= k.act_parent_id) AND kx.show_it = 0 AND kx.language_id =lx.id  
                    
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
     * @ body aksesuar tanımlarını grid formatında gösterilirken kaç kayıt olduğunu döndürür !! ana tablo  sys_vehicles 
     * @version v 1.0  15.08.2018
     * @param array | null $args
     * @return array
     * @throws \PDOException  
     */  
    public function fillVehiclesGridxRtl($params = array()) {
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
                            case 'cbuckd_name':
                                $sorguExpression = ' ILIKE \'%' . $std['value'] . '%\' ';
                                $sorguStr.=" AND c.name" . $sorguExpression . ' ';
                              
                                break;
                            case 'gt_model_name':
                                $sorguExpression = ' ILIKE \'%' . $std['value'] . '%\'  ';
                                $sorguStr.=" AND b.name" . $sorguExpression . ' ';

                                break;  
                             case 'variant_name':
                                $sorguExpression = ' ILIKE \'%' . $std['value'] . '%\' ';
                                $sorguStr.=" AND d.name" . $sorguExpression . ' ';
                              
                                break;
                            case 'config_type_name':
                                $sorguExpression = ' ILIKE \'%' . $std['value'] . '%\'  ';
                                $sorguStr.=" AND e.name" . $sorguExpression . ' ';

                                break;   
                             case 'cap_type_name':
                                $sorguExpression = ' ILIKE \'%' . $std['value'] . '%\' ';
                                $sorguStr.=" AND f.name" . $sorguExpression . ' ';
                              
                                break;
                            case 'app_type_name':
                                $sorguExpression = ' ILIKE \'%' . $std['value'] . '%\' ';
                                $sorguStr.=" AND h.name" . $sorguExpression . ' ';
                              
                                break;
                            case 'btobts_name':
                                $sorguExpression = ' ILIKE \'%' . $std['value'] . '%\'  ';
                                $sorguStr.=" AND i.name" . $sorguExpression . ' ';

                                break; 
                             case 'kp_name':
                                $sorguExpression = ' ILIKE \'%' . $std['value'] . '%\' ';
                                $sorguStr.=" AND g.name" . $sorguExpression . ' ';
                              
                                break; 
                            case 'road_type_name':
                                $sorguExpression = ' ILIKE \'%' . $std['value'] . '%\'  ';
                                $sorguStr.=" AND COALESCE(NULLIF(kx.name, ''), k.name_eng)" . $sorguExpression . ' ';

                                break;  
                             case 'gfz':
                                $sorguExpression = ' ILIKE \'%' . $std['value'] . '%\' ';
                                $sorguStr.=" AND a.gfz" . $sorguExpression . ' ';
                              
                                break;
                            case 'factorymodel_name':
                                $sorguExpression = ' ILIKE \'%' . $std['value'] . '%\'  ';
                                $sorguStr.=" AND a.factorymodel_name" . $sorguExpression . ' ';

                                break; 
                             case 'description':
                                $sorguExpression = ' ILIKE \'%' . $std['value'] . '%\' ';
                                $sorguStr.=" AND a.description" . $sorguExpression . ' ';
                              
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
             $ckdCbuTypeID =0 ;
            if (isset($params['CkdCbuTypeID']) && $params['CkdCbuTypeID'] != "") {
                $ckdCbuTypeID = $params['CkdCbuTypeID'];
                $addSql .="  a.ckdcbu_type_id  = " . intval($ckdCbuTypeID). "  AND  " ; 
            }  
            $vehicleGtModelID =0 ;
            if (isset($params['VehicleGtModelID']) && $params['VehicleGtModelID'] != "") {
                $vehicleGtModelID = $params['VehicleGtModelID'];
                $addSql .="  a.vehicle_gt_model_id  = " . intval($vehicleGtModelID). "  AND  " ; 
            }  
            $modelVariantID =0 ;
            if (isset($params['ModelVariantID']) && $params['ModelVariantID'] != "") {
                $modelVariantID = $params['ModelVariantID'];
                $addSql .="  a.model_variant_id  = " . intval($modelVariantID). "  AND  " ; 
            }  
            $configTypeID =0 ;
            if (isset($params['ConfigTypeID']) && $params['ConfigTypeID'] != "") {
                $configTypeID = $params['ConfigTypeID'];
                $addSql .="  a.config_type_id  = " . intval($configTypeID). "  AND  " ; 
            }  
            $capTypeID =0 ;
            if (isset($params['CapTypeID']) && $params['CapTypeID'] != "") {
                $capTypeID = $params['CapTypeID'];
                $addSql .="  a.cap_type_id  = " . intval($capTypeID). "  AND  " ; 
            }  
            $vehicleAppTypeID =0 ;
            if (isset($params['VehicleAppTypeID']) && $params['VehicleAppTypeID'] != "") {
                $vehicleAppTypeID = $params['VehicleAppTypeID'];
                $addSql .="  a.vehicle_app_type_id  = " . intval($vehicleAppTypeID). "  AND  " ; 
            }  
            $kpnumberID =0 ;
            if (isset($params['KpnumberID']) && $params['KpnumberID'] != "") {
                $kpnumberID = $params['KpnumberID'];
                $addSql .="  a.kpnumber_id  = " . intval($kpnumberID). "  AND  " ; 
            }  
            $btsBtoTypeID =0 ;
            if (isset($params['BtsBtoTypeID']) && $params['BtsBtoTypeID'] != "") {
                $btsBtoTypeID = $params['BtsBtoTypeID'];
                $addSql .="  a.btsbto_type_id  = " . intval($btsBtoTypeID). "  AND  " ; 
            }  
            $roadTypeID =0 ;
            if (isset($params['RoadTypeID']) && $params['RoadTypeID'] != "") {
                $roadTypeID = $params['RoadTypeID'];
                $addSql .="  a.roadtype_id  = " . intval($roadTypeID). "  AND  " ; 
            }   

                $sql = "
                   SELECT COUNT(asdx.id) count FROM ( 
                         SELECT 
                            a.id,  
                            a.ckdcbu_type_id,
                            c.name cbuckd_name,
                            a.vehicle_gt_model_id,
                            b.name gt_model_name,
                            a.model_variant_id,
                            d.name as variant_name, 
                            a.config_type_id,
                            e.name AS config_type_name,
                            a.cap_type_id,
                            f.name AS cap_type_name,
                            a.vehicle_app_type_id,
                            h.name app_type_name, 
                            a.kpnumber_id,
                            g.name as kp_name,
                            a.btsbto_type_id, 
                            i.name as btobts_name,
                            a.roadtype_id,
                            COALESCE(NULLIF(kx.name, ''), k.name_eng) AS road_type_name, 
                            a.gfz,
                            a.factorymodel_name,
                            a.description, 
                            a.act_parent_id,   
                            a.active,
                            COALESCE(NULLIF(sd16x.description, ''), sd16.description_eng) AS state_active, 
                            a.op_user_id,
                            u.username AS op_user_name,  
                            a.s_date date_saved,
                            a.c_date date_modified, 
                            COALESCE(NULLIF(lx.id, NULL), 385) AS language_id, 
                            lx.language_main_code language_code, 
                            COALESCE(NULLIF(lx.language, ''), 'en') AS language_name
                        FROM sys_vehicles a                    
                        INNER JOIN sys_language l ON l.id = 385 AND l.show_it =0
                        LEFT JOIN sys_language lx ON lx.id =" . intval($languageIdValue) . " AND lx.show_it =0  
                        LEFT JOIN sys_vehicles ax ON (ax.act_parent_id = a.act_parent_id OR ax.language_parent_id = a.act_parent_id) AND ax.show_it = 0 AND ax.language_id = lx.id
                        INNER JOIN info_users u ON u.id = a.op_user_id 
                        /*----*/ 
                        INNER JOIN sys_vehicle_gt_models b ON b.act_parent_id = a.vehicle_gt_model_id AND b.show_it = 0 
                        INNER JOIN sys_vehicle_ckdcbu c ON c.act_parent_id = a.ckdcbu_type_id AND c.show_it = 0   
                        INNER JOIN sys_vehicle_model_variants d ON d.act_parent_id = a.ckdcbu_type_id AND d.show_it = 0  
                        INNER JOIN sys_vehicle_config_types e ON e.act_parent_id = a.config_type_id AND e.show_it = 0  
                        INNER JOIN sys_vehicle_cap_types f ON f.act_parent_id = a.cap_type_id AND f.show_it = 0  
                        INNER JOIN sys_vehicle_app_types h ON h.act_parent_id = a.cap_type_id AND h.show_it = 0  
                        INNER JOIN sys_kpnumbers g ON g.act_parent_id = a.kpnumber_id AND g.show_it = 0  
                        INNER JOIN sys_vehicle_btobts i ON i.act_parent_id = a.kpnumber_id AND i.show_it = 0   
                        INNER JOIN sys_roadtypes k ON k.act_parent_id = a.roadtype_id AND k.show_it = 0 AND k.language_id= l.id
                        LEFT JOIN sys_roadtypes kx ON (kx.act_parent_id = k.act_parent_id OR kx.language_parent_id= k.act_parent_id) AND kx.show_it = 0 AND kx.language_id =lx.id  

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
     * @ sys_vehicles tablosundan parametre olarak  gelen id kaydını active ve show_it alanlarını 1 yapar. !!
     * @version v 1.0  24.08.2018
     * @param type $params
     * @return array
     * @throws \PDOException
     */
    public function makePassive($params = array()) {
        try {
            $pdo = $this->slimApp->getServiceManager()->get('pgConnectFactory'); 
            $statement = $pdo->prepare(" 
                UPDATE sys_vehicles
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
     * @ sys_vehicles tablosundan parametre olarak  gelen id kaydın active veshow_it  alanını 1 yapar ve 
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
                    INSERT INTO sys_vehicles (
                        ckdcbu_type_id,
                        vehicle_gt_model_id,
                        model_variant_id,
                        description,
                        config_type_id,
                        cap_type_id,
                        vehicle_app_type_id,
                        gfz,
                        kpnumber_id,
                        btsbto_type_id,
                        factorymodel_name,
                        roadtype_id,
                         
                        active,
                        deleted,
                        op_user_id,
                        act_parent_id,
                        show_it
                        )
                    SELECT
                        ckdcbu_type_id,
                        vehicle_gt_model_id,
                        model_variant_id,
                        description,
                        config_type_id,
                        cap_type_id,
                        vehicle_app_type_id,
                        gfz,
                        kpnumber_id,
                        btsbto_type_id,
                        factorymodel_name,
                        roadtype_id,
                         
                        1 AS active,  
                        1 AS deleted, 
                        " . intval($opUserIdValue) . " AS op_user_id, 
                        act_parent_id,
                        0 AS show_it 
                    FROM sys_vehicles 
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
     * @ sys_vehicles tablosuna yeni bir kayıt oluşturur.  !! 
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
            $gfz = null;
            if ((isset($params['Gfz']) && $params['Gfz'] != "")) {
                $gfz = $params['Gfz'];
            } else {
                throw new \PDOException($errorInfo[0]);
            }
            $factorymodelName = null;
            if ((isset($params['FactorymodelName']) && $params['FactorymodelName'] != "")) {
                $factorymodelName = $params['FactorymodelName'];
            }  
            $description = null;
            if ((isset($params['Description']) && $params['Description'] != "")) {
                $description = $params['Description'];
            } 
            $ckdcbuTypeId = -1111;
            if ((isset($params['CkdcbuTypeId']) && $params['CkdcbuTypeId'] != "")) {
                $ckdcbuTypeId = intval($params['CkdcbuTypeId']);
            } else {
                throw new \PDOException($errorInfo[0]);
            }
            $vehicleGtModelId = -1111;
            if ((isset($params['VehicleGtModelId']) && $params['VehicleGtModelId'] != "")) {
                $vehicleGtModelId = intval($params['VehicleGtModelId']);
            } else {
                throw new \PDOException($errorInfo[0]);
            }
             $modelVariantId = -1111;
            if ((isset($params['ModelVariantId']) && $params['ModelVariantId'] != "")) {
                $modelVariantId = intval($params['ModelVariantId']);
            } else {
                throw new \PDOException($errorInfo[0]);
            }
             $configTypeId = -1111;
            if ((isset($params['ConfigTypeId']) && $params['ConfigTypeId'] != "")) {
                $configTypeId = intval($params['ConfigTypeId']);
            } else {
                throw new \PDOException($errorInfo[0]);
            }
            $capTypeId = -1111;
            if ((isset($params['CapTypeId']) && $params['CapTypeId'] != "")) {
                $capTypeId = intval($params['CapTypeId']);
            } else {
                throw new \PDOException($errorInfo[0]);
            }
            $vehicleAppTypeId = -1111;
            if ((isset($params['VehicleAppTypeId']) && $params['VehicleAppTypeId'] != "")) {
                $vehicleAppTypeId = intval($params['VehicleAppTypeId']);
            } else {
                throw new \PDOException($errorInfo[0]);
            }
            $kpnumberId = -1111;
            if ((isset($params['KpnumberId']) && $params['KpnumberId'] != "")) {
                $kpnumberId = intval($params['KpnumberId']);
            } else {
                throw new \PDOException($errorInfo[0]);
            }
            $btsbtoTypeId = -1111;
            if ((isset($params['BtsbtoTypeId']) && $params['BtsbtoTypeId'] != "")) {
                $btsbtoTypeId = intval($params['BtsbtoTypeId']);
            } else {
                throw new \PDOException($errorInfo[0]);
            }
            $roadTypeId = -1111;
            if ((isset($params['RoadTypeId']) && $params['RoadTypeId'] != "")) {
                $roadTypeId = intval($params['RoadTypeId']);
            } else {
                throw new \PDOException($errorInfo[0]);
            }
                            
            $opUserId = InfoUsers::getUserId(array('pk' => $params['pk']));
            if (\Utill\Dal\Helper::haveRecord($opUserId)) {
                $opUserIdValue = $opUserId ['resultSet'][0]['user_id'];

                $kontrol = $this->haveRecords(
                        array(
                            'ckdcbu_type_id' => $ckdcbuTypeId,
                            'vehicle_gt_model_id' => $vehicleGtModelId,
                            'model_variant_id' => $modelVariantId, 
                            'config_type_id' => $configTypeId,
                            'cap_type_id' => $capTypeId,
                            'vehicle_app_type_id' => $vehicleAppTypeId,
                            'kpnumber_id' => $kpnumberId,
                            'btsbto_type_id' => $btsbtoTypeId,                            
                            'roadtype_id' => $roadTypeId,
                            'description' =>   $description , 
                         //   'gfz' => $gfz,
                ));
                if (!\Utill\Dal\Helper::haveRecord($kontrol)) {
                    $sql = "
                    INSERT INTO sys_vehicles(
                            ckdcbu_type_id,
                            vehicle_gt_model_id,
                            model_variant_id,
                           
                            config_type_id,
                            cap_type_id,
                            vehicle_app_type_id,
                           
                            kpnumber_id,
                            btsbto_type_id, 
                            roadtype_id,
                            
                            gfz,
                            factorymodel_name,
                            description,
                            
                            op_user_id,
                            act_parent_id  
                            )
                    VALUES (
                            " . intval($ckdcbuTypeId) . ",
                            " . intval($vehicleGtModelId) . ",
                            " . intval($modelVariantId) . ",
                            " . intval($configTypeId) . ",
                            " . intval($capTypeId) . ",
                            " . intval($vehicleAppTypeId) . ",
                            " . intval($kpnumberId) . ", 
                            " . intval($btsbtoTypeId) . ",
                            " . intval($roadTypeId) . ",
                           
                                
                            '" . $gfz . "',
                            '" . $factorymodelName . "',
                            '" . $description . "', 
                                
                            " . intval($opUserIdValue) . ", 
                           (SELECT last_value FROM sys_vehicles_id_seq)
                                                 )   ";
                    $statement = $pdo->prepare($sql);
                    //   echo debugPDO($sql, $params);
                    $result = $statement->execute();
                    $errorInfo = $statement->errorInfo();
                    if ($errorInfo[0] != "00000" && $errorInfo[1] != NULL && $errorInfo[2] != NULL)
                        throw new \PDOException($errorInfo[0]);
                    $insertID = $pdo->lastInsertId('sys_vehicles_id_seq');
                            
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
     * sys_vehicles tablosuna parametre olarak gelen id deki kaydın bilgilerini günceller   !!
     * @version v 1.0  26.08.2018
     * @param type $params
     * @return array
     * @throws \PDOException
     */
    public function updateAct($params = array()) {
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
            print_r("1111");
             $gfz = null;
            if ((isset($params['Gfz']) && $params['Gfz'] != "")) {
                $gfz = $params['Gfz'];
            } else {
                throw new \PDOException($errorInfo[0]);
            }
            print_r("2222");
            $factorymodelName = null;
            if ((isset($params['FactorymodelName']) && $params['FactorymodelName'] != "")) {
                $factorymodelName = $params['FactorymodelName'];
            }  
            print_r("3333");
            $description = null;
            if ((isset($params['Description']) && $params['Description'] != "")) {
                $description = $params['Description'];
            } 
            print_r("4444");
            $ckdcbuTypeId = -1111;
            if ((isset($params['CkdcbuTypeId']) && $params['CkdcbuTypeId'] != "")) {
                $ckdcbuTypeId = intval($params['CkdcbuTypeId']);
            } else {
                throw new \PDOException($errorInfo[0]);
            }
            print_r("5555");
            $vehicleGtModelId = -1111;
            if ((isset($params['VehicleGtModelId']) && $params['VehicleGtModelId'] != "")) {
                $vehicleGtModelId = intval($params['VehicleGtModelId']);
            } else {
                throw new \PDOException($errorInfo[0]);
            }
            print_r("6666");
             $modelVariantId = -1111;
            if ((isset($params['ModelVariantId']) && $params['ModelVariantId'] != "")) {
                $modelVariantId = intval($params['ModelVariantId']);
            } else {
                throw new \PDOException($errorInfo[0]);
            }
            print_r("7777");
             $configTypeId = -1111;
            if ((isset($params['ConfigTypeId']) && $params['ConfigTypeId'] != "")) {
                $configTypeId = intval($params['ConfigTypeId']);
            } else {
                throw new \PDOException($errorInfo[0]);
            }
            print_r("8888");
            $capTypeId = -1111;
            if ((isset($params['CapTypeId']) && $params['CapTypeId'] != "")) {
                $capTypeId = intval($params['CapTypeId']);
            } else {
                throw new \PDOException($errorInfo[0]);
            }
            print_r("9999");
            $vehicleAppTypeId = -1111;
            if ((isset($params['VehicleAppTypeId']) && $params['VehicleAppTypeId'] != "")) {
                $vehicleAppTypeId = intval($params['VehicleAppTypeId']);
            } else {
                throw new \PDOException($errorInfo[0]);
            }
            print_r("1000");
            $kpnumberId = -1111;
            if ((isset($params['KpnumberId']) && $params['KpnumberId'] != "")) {
                $kpnumberId = intval($params['KpnumberId']);
            } else {
                throw new \PDOException($errorInfo[0]);
            }
            $btsbtoTypeId = -1111;
            if ((isset($params['BtsbtoTypeId']) && $params['BtsbtoTypeId'] != "")) {
                $btsbtoTypeId = intval($params['BtsbtoTypeId']);
            } else {
                throw new \PDOException($errorInfo[0]);
            }
            print_r("1111*");
            $roadTypeId = -1111;
            if ((isset($params['RoadTypeId']) && $params['RoadTypeId'] != "")) {
                $roadTypeId = intval($params['RoadTypeId']);
            } else {
                throw new \PDOException($errorInfo[0]);
            }
            print_r("12222* ");
            $opUserIdParams = array('pk' => $params['pk'],);
            $opUserIdArray = $this->slimApp->getBLLManager()->get('opUserIdBLL');
            $opUserId = $opUserIdArray->getUserId($opUserIdParams);
            if (\Utill\Dal\Helper::haveRecord($opUserId)) {
                $opUserIdValue = $opUserId ['resultSet'][0]['user_id'];
                $opUserRoleIdValue = $opUserId ['resultSet'][0]['role_id'];

                $kontrol = $this->haveRecords(
                        array(
                            'ckdcbu_type_id' => $ckdcbuTypeId,
                            'vehicle_gt_model_id' => $vehicleGtModelId,
                            'model_variant_id' => $modelVariantId, 
                            'config_type_id' => $configTypeId,
                            'cap_type_id' => $capTypeId,
                            'vehicle_app_type_id' => $vehicleAppTypeId,
                            'kpnumber_id' => $kpnumberId,
                            'btsbto_type_id' => $btsbtoTypeId,                            
                            'roadtype_id' => $roadTypeId,
                            'description' =>   $description , 
                            'id' => $Id
                ));
                if (!\Utill\Dal\Helper::haveRecord($kontrol)) {

                    $this->makePassive(array('id' => $params['id']));

                    $statementInsert = $pdo->prepare("
                INSERT INTO sys_vehicles (  
                        ckdcbu_type_id,
                        vehicle_gt_model_id,
                        model_variant_id,

                        config_type_id,
                        cap_type_id,
                        vehicle_app_type_id,

                        kpnumber_id,
                        btsbto_type_id, 
                        roadtype_id,

                        gfz,
                        factorymodel_name,
                        description,

                        op_user_id,
                        act_parent_id   
                        )  
                SELECT  
                    " . intval($ckdcbuTypeId) . ",
                    " . intval($vehicleGtModelId) . ",
                    " . intval($modelVariantId) . ",
                    " . intval($configTypeId) . ",
                    " . intval($capTypeId) . ",
                    " . intval($vehicleAppTypeId) . ",
                    " . intval($kpnumberId) . ", 
                    " . intval($btsbtoTypeId) . ",
                    " . intval($roadTypeId) . ",


                    '" . $gfz . "',
                    '" . $factorymodelName . "',
                    '" . $description . "', 
                    " . intval($opUserIdValue) . " AS op_user_id,  
                    act_parent_id
                FROM sys_vehicles 
                WHERE 
                    language_id = 385 AND id  =" . intval($Id) . "                  
                                                ");
                    $result = $statementInsert->execute();
                    $insertID = $pdo->lastInsertId('sys_vehicles_id_seq');
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
