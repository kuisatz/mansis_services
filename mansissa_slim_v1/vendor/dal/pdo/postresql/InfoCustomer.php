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
class InfoCustomer extends \DAL\DalSlim {

    /**
     * @author Okan CIRAN    
     * @ info_customer tablosundan parametre olarak  gelen id kaydını siler. !!
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
                UPDATE info_customer
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
     * @ info_customer tablosundaki tüm kayıtları getirir.  !!
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
                FROM info_customer a
                INNER JOIN sys_language l ON l.id = a.language_id AND l.deleted =0 AND l.active = 0 
                LEFT JOIN sys_language lx ON lx.id = ".intval($languageIdValue)." AND lx.deleted =0 AND lx.active =0
                INNER JOIN sys_specific_definitions sd15 ON sd15.main_group = 15 AND sd15.first_group= a.deleted AND sd15.language_id = a.language_id AND sd15.deleted = 0 
                INNER JOIN sys_specific_definitions sd16 ON sd16.main_group = 16 AND sd16.first_group= a.active AND sd16.language_id = a.language_id AND sd16.deleted = 0
                INNER JOIN info_users u ON u.id = a.op_user_id    
                LEFT JOIN sys_specific_definitions sd15x ON (sd15x.id = sd15.id OR sd15x.language_parent_id = sd15.id) AND sd15x.language_id =lx.id  AND sd15x.deleted =0 AND sd15x.active =0 
                LEFT JOIN sys_specific_definitions sd16x ON (sd16x.id = sd16.id OR sd16x.language_parent_id = sd16.id)AND sd16x.language_id = lx.id  AND sd16x.deleted = 0 AND sd16x.active = 0
                LEFT JOIN info_customer ax ON (ax.id = a.id OR ax.language_parent_id = a.id) AND ax.deleted =0 AND ax.active =0 AND lx.id = ax.language_id
                
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
     * @ info_customer tablosuna yeni bir kayıt oluşturur.  !!
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
                INSERT INTO info_customer(
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
                    $insertID = $pdo->lastInsertId('info_customer_id_seq');
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
     * @ info_customer tablosunda property_name daha önce kaydedilmiş mi ?  
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
                a.registration_name ,
                '" . $params['registration_name'] . "' AS value, 
                LOWER(a.registration_name) = LOWER(TRIM('" . $params['registration_name'] . "')) AS control,
                CONCAT(a.registration_name, ' daha önce kayıt edilmiş. Lütfen Kontrol Ediniz !!!' ) AS message
            FROM info_customer  a                          
            WHERE 
                LOWER(REPLACE(registration_name,' ','')) = LOWER(REPLACE('" . $params['registration_name'] . "',' ','')) OR 
               ( LOWER(REPLACE(tu_emb_customer_no,' ','')) = LOWER(REPLACE('" . $params['tu_emb_customer_no'] . "',' ','')) OR  
                   LOWER(REPLACE(embrace_customer_no,' ','')) = LOWER(REPLACE('" . $params['embrace_customer_no'] . "',' ','')) OR  
                        LOWER(REPLACE(ce_emb_customer_no,' ','')) = LOWER(REPLACE('" . $params['ce_emb_customer_no'] . "',' ','')) OR  
                             LOWER(REPLACE(other_emb_customer_no,' ','')) = LOWER(REPLACE('" . $params['other_emb_customer_no'] . "',' ','')) )  
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
     * info_customer tablosuna parametre olarak gelen id deki kaydın bilgilerini günceller   !!
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
                    UPDATE info_customer
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
     * @ Gridi doldurmak için info_customer tablosundan kayıtları döndürür !!
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
                FROM info_customer a
                INNER JOIN sys_language l ON l.id = a.language_id AND l.deleted =0 AND l.active = 0 
                LEFT JOIN sys_language lx ON lx.id = ".intval($languageIdValue)." AND lx.deleted =0 AND lx.active =0
                INNER JOIN sys_specific_definitions sd15 ON sd15.main_group = 15 AND sd15.first_group= a.deleted AND sd15.language_id = a.language_id AND sd15.deleted = 0 
                INNER JOIN sys_specific_definitions sd16 ON sd16.main_group = 16 AND sd16.first_group= a.active AND sd16.language_id = a.language_id AND sd16.deleted = 0
                INNER JOIN info_users u ON u.id = a.op_user_id    
                LEFT JOIN sys_specific_definitions sd15x ON (sd15x.id = sd15.id OR sd15x.language_parent_id = sd15.id) AND sd15x.language_id =lx.id  AND sd15x.deleted =0 AND sd15x.active =0 
                LEFT JOIN sys_specific_definitions sd16x ON (sd16x.id = sd16.id OR sd16x.language_parent_id = sd16.id)AND sd16x.language_id = lx.id  AND sd16x.deleted = 0 AND sd16x.active = 0
                LEFT JOIN info_customer ax ON (ax.id = a.id OR ax.language_parent_id = a.id) AND ax.deleted =0 AND ax.active =0 AND lx.id = ax.language_id
                
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
     * @ Gridi doldurmak için info_customer tablosundan çekilen kayıtlarının kaç tane olduğunu döndürür   !!
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
                FROM info_customer a
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
     * @ info_customer tablosundan parametre olarak  gelen id kaydın aktifliğini
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
                UPDATE info_customer
                SET active = (  SELECT   
                                CASE active
                                    WHEN 0 THEN 1
                                    ELSE 0
                                END activex
                                FROM info_customer
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
     * @ back office tarafından onaylanmış müşteri tanımları dropdown ya da tree ye doldurmak için info_customer tablosundan kayıtları döndürür !!
     * @version v 1.0  11.08.2018
     * @param array | null $args
     * @return array
     * @throws \PDOException 
     */
    public function  customerConfirmDdList($params = array()) {
        try {
            $pdo = $this->slimApp->getServiceManager()->get('pgConnectFactory');         
                            
            $statement = $pdo->prepare("    
              SELECT                    
                    a.act_parent_id AS id, 	
                    a.registration_name  AS name,  
                    a.embrace_customer_no AS name_eng,
                    0 as parent_id,
                    a.active,
                    0 AS state_type   
                FROM info_customer a    
                WHERE   
                    a.deleted = 0 AND
                    a.active =0 AND 
                    is_bo_confirm = 1 
                ORDER BY  id   
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
     * @ back office tarafından onaylanmanmış müşteri tanımları dropdown ya da tree ye doldurmak için info_customer tablosundan kayıtları döndürür !!
     * @version v 1.0  11.08.2018
     * @param array | null $args
     * @return array
     * @throws \PDOException 
     */
    public function  customerNoConfirmDdList($params = array()) {
        try {
            $pdo = $this->slimApp->getServiceManager()->get('pgConnectFactory');         
                            
            $statement = $pdo->prepare("    
              SELECT                    
                    a.act_parent_id AS id, 	
                    a.registration_name  AS name,  
                    a.name_short AS name_eng,
                    0 as parent_id,
                    a.active,
                    0 AS state_type   
                FROM info_customer a    
                WHERE   
                    a.deleted = 0 AND
                    a.active =0 AND 
                    is_bo_confirm = 0 
                ORDER BY  id   
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
     * @ back office tarafından onaylanmanmış müşteri tanımları dropdown ya da tree ye doldurmak için info_customer tablosundan kayıtları döndürür !!
     * @version v 1.0  11.08.2018
     * @param array | null $args
     * @return array
     * @throws \PDOException 
     */
    public function  customerDdList($params = array()) {
        try {
            $pdo = $this->slimApp->getServiceManager()->get('pgConnectFactory');         
                            
            $statement = $pdo->prepare("    
              SELECT                    
                    a.act_parent_id AS id, 	
                    a.registration_name  AS name,  
                    a.name_short AS name_eng,
                    0 as parent_id,
                    a.active,
                    0 AS state_type   
                FROM info_customer a    
                WHERE   
                    a.deleted = 0 AND
                    a.active =0  
                ORDER BY  id   
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
     * @   garanti tanımlarını grid formatında döndürür !! ana tablo  info_customer 
     * @version v 1.0  20.08.2018
     * @param array | null $args 
     * @return array
     * @throws \PDOException  
     */  
    public function fillCustomerGridx($params = array()) {
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
                            case 'registration_name':
                                $sorguExpression = ' ILIKE \'%' . $std['value'] . '%\' ';
                                $sorguStr.=" AND COALESCE(NULLIF(ax.name, ''), a.name_eng)" . $sorguExpression . ' ';
                              
                                break;
                            case 'trading_name':
                                $sorguExpression = ' ILIKE \'%' . $std['value'] . '%\'  ';
                                $sorguStr.=" AND a.trading_name" . $sorguExpression . ' ';

                                break;  
                            case 'name_short':
                                $sorguExpression = ' ILIKE \'%' . $std['value'] . '%\'  ';
                                $sorguStr.=" AND a.name_short" . $sorguExpression . ' ';

                                break;  
                            case 'embrace_customer_no':
                                $sorguExpression = ' ILIKE \'%' . $std['value'] . '%\'  ';
                                $sorguStr.=" AND a.embrace_customer_no" . $sorguExpression . ' ';

                                break;  
                            case 'tu_emb_customer_no':
                                $sorguExpression = ' ILIKE \'%' . $std['value'] . '%\'  ';
                                $sorguStr.=" AND a.tu_emb_customer_no" . $sorguExpression . ' ';

                                break; 
                            case 'ce_emb_customer_no':
                                $sorguExpression = ' ILIKE \'%' . $std['value'] . '%\'  ';
                                $sorguStr.=" AND a.ce_emb_customer_no" . $sorguExpression . ' ';

                                break; 
                             case 'other_emb_customer_no':
                                $sorguExpression = ' ILIKE \'%' . $std['value'] . '%\'  ';
                                $sorguStr.=" AND a.other_emb_customer_no" . $sorguExpression . ' ';

                                break; 
                            case 'www':
                                $sorguExpression = ' ILIKE \'%' . $std['value'] . '%\'  ';
                                $sorguStr.=" AND a.www" . $sorguExpression . ' ';

                                break; 
                            case 'vatnumber':
                                $sorguExpression = ' ILIKE \'%' . $std['value'] . '%\'  ';
                                $sorguStr.=" AND a.vatnumber" . $sorguExpression . ' ';

                                break; 
                            case 'registration_number':
                                $sorguExpression = ' ILIKE \'%' . $std['value'] . '%\'  ';
                                $sorguStr.=" AND a.vatnumber" . $sorguExpression . ' ';

                                break; 
                            case 'customer_category_name':
                                $sorguExpression = ' ILIKE \'%' . $std['value'] . '%\'  ';
                                $sorguStr.=" AND a.customer_category_name" . $sorguExpression . ' ';

                                break; 
                            case 'reliability_name':
                                $sorguExpression = ' ILIKE \'%' . $std['value'] . '%\'  ';
                                $sorguStr.=" AND a.reliability_name" . $sorguExpression . ' ';

                                break;
                            case 'turnover_rate_name':
                                $sorguExpression = ' ILIKE \'%' . $std['value'] . '%\'  ';
                                $sorguStr.=" AND a.turnover_rate_name" . $sorguExpression . ' ';

                                break;
                            case 'sector_type_name':
                                $sorguExpression = ' ILIKE \'%' . $std['value'] . '%\'  ';
                                $sorguStr.=" AND a.sector_type_name" . $sorguExpression . ' ';

                                break;
                            case 'firm_country_name':
                                $sorguExpression = ' ILIKE \'%' . $std['value'] . '%\'  ';
                                $sorguStr.=" AND coun.name" . $sorguExpression . ' ';

                                break;
                            case 'application_type_name':
                                $sorguExpression = ' ILIKE \'%' . $std['value'] . '%\'  ';
                                $sorguStr.=" AND a.application_type_name" . $sorguExpression . ' ';

                                break;
                            case 'segment_type_name':
                                $sorguExpression = ' ILIKE \'%' . $std['value'] . '%\'  ';
                                $sorguStr.=" AND a.segment_type_name" . $sorguExpression . ' ';

                                break;  
                             case 'address1':
                                $sorguExpression = ' ILIKE \'%' . $std['value'] . '%\'  ';
                                $sorguStr.=" AND a.address1" . $sorguExpression . ' ';

                                break;  
                             case 'address2':
                                $sorguExpression = ' ILIKE \'%' . $std['value'] . '%\'  ';
                                $sorguStr.=" AND a.address2" . $sorguExpression . ' ';

                                break;  
                             case 'address3':
                                $sorguExpression = ' ILIKE \'%' . $std['value'] . '%\'  ';
                                $sorguStr.=" AND a.address3" . $sorguExpression . ' ';

                                break;  
                             case 'postalcode':
                                $sorguExpression = ' ILIKE \'%' . $std['value'] . '%\'  ';
                                $sorguStr.=" AND a.postalcode" . $sorguExpression . ' ';

                                break; 
                             case 'city_name':
                                $sorguExpression = ' ILIKE \'%' . $std['value'] . '%\'  ';
                                $sorguStr.=" AND city.name" . $sorguExpression . ' ';

                                break; 
                             case 'region':
                                $sorguExpression = ' ILIKE \'%' . $std['value'] . '%\'  ';
                                $sorguStr.=" AND region.name" . $sorguExpression . ' ';

                                break; 
                             case 'city_name':
                                $sorguExpression = ' ILIKE \'%' . $std['value'] . '%\'  ';
                                $sorguStr.=" AND city.name" . $sorguExpression . ' ';

                                break; 
                             case 'country_name':
                                $sorguExpression = ' ILIKE \'%' . $std['value'] . '%\'  ';
                                $sorguStr.=" AND coun2.name" . $sorguExpression . ' ';

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
                            
            $isboConfirm = -1 ; 
            if (isset($params['IsBoConfirm']) && $params['IsBoConfirm'] != "") {
                $isboConfirm = $params['IsBoConfirm']; 
                 $addSql =  " a.is_bo_confirm = " . intval($isboConfirm)." AND " ;
            }    
                $sql = "    

                    SELECT  
                        a.id, 
                        a.act_parent_id as apid, 
                        a.registration_name, 
                        a.trading_name,
                        a.name_short, 
                        a.embrace_customer_no , 
                        a.tu_emb_customer_no,
			a.ce_emb_customer_no,
			a.other_emb_customer_no,
                        a.www, 
                        a.vatnumber, 
                        a.registration_number, 
                        a.registration_date, 
                        a.ne_count_type_id, 
                        nre.name numberofemployees, 
                       
                        a.nv_count_type_id, 
                         nrv.name numberofvehicles,
                         
                        a.customer_category_id, 
                        cc.name customer_category_name, 
                        
                        a.reliability_id, 
                        scr.name reliability_name, 

                        a.turnover_rate_id, 
			tr.name turnover_rate_name, 
                        
                        a.sector_type_id, 
			st.name sector_type_name, 
                        
                        a.application_type_id, 
			cat.name application_type_name, 

                        a.segment_type_id,
                        cst.name segment_type_name, 

                        
                        a.is_bo_confirm, 
                        a.country2_id firm_country_id, 
                        coun.name firm_country_name,
 
			a.address1,
			a.address2,
			a.address3,
			a.postalcode,
			
			a.city_id,
			city.name as city_name, 
			city.region_id, 
			region.name as region, 
			a.country_id, 			
			coun2.name country_name,
                        
                        
                      /*  a.name_eng, */  
                        a.active,
                        COALESCE(NULLIF(sd16x.description, ''), sd16.description_eng) AS state_active,
                       /* a.deleted,
                        COALESCE(NULLIF(sd15x.description, ''), sd15.description_eng) AS state_deleted,*/
                        a.op_user_id,
                        u.username AS op_user_name,  
                        a.s_date date_saved,
                        a.c_date date_modified, 
                        COALESCE(NULLIF(lx.id, NULL), 385) AS language_id, 
                        lx.language_main_code language_code, 
                        COALESCE(NULLIF(lx.language, ''), 'en') AS language_name
                    FROM info_customer a                    
                    INNER JOIN sys_language l ON l.id = 385 AND l.show_it =0
                    LEFT JOIN sys_language lx ON lx.id =" . intval($languageIdValue) . "  AND lx.show_it =0    
                    LEFT JOIN info_customer ax ON (ax.act_parent_id =a.act_parent_id ) AND ax.show_it =0 
               
                    INNER JOIN info_users u ON u.id = a.op_user_id 
                    /*----*/   
		      
                    LEFT JOIN sys_countrys coun ON coun.id = a.country2_id AND coun.show_it = 0 
                    LEFT JOIN sys_countrys coun2 ON coun2.id = a.country_id AND coun2.show_it = 0 
		    LEFT JOIN sys_city city ON city.id = a.city_id AND city.show_it = 0 
		    LEFT JOIN sys_country_regions region ON region.id = a.city_id AND region.show_it = 0 
                    
                    LEFT JOIN sys_numerical_ranges nre ON nre.act_parent_id = a.ne_count_type_id AND nre.show_it = 0 AND nre.parent_id = 13
                    LEFT JOIN sys_numerical_ranges nrv ON nrv.act_parent_id = a.nv_count_type_id AND nrv.show_it = 0 AND nrv.parent_id = 20
                    inner join sys_customer_categories cc on cc.act_parent_id = a.customer_category_id and cc.show_it = 0 
		    inner join sys_customer_reliability scr on scr.act_parent_id = a.reliability_id and scr.show_it = 0 
		    left join sys_customer_turnover_rates tr on tr.act_parent_id = a.turnover_rate_id and tr.show_it = 0 
		    left join sys_customer_sector_types st on st.act_parent_id = a.sector_type_id and st.show_it = 0 
		    left join sys_customer_application_types cat on cat.act_parent_id = a.application_type_id and cat.show_it = 0 
                    left join sys_customer_segment_types cst on cst.act_parent_id = a.segment_type_id and cst.show_it = 0 
		     
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
     * @  garanti tanımlarını grid formatında gösterilirken kaç kayıt olduğunu döndürür !! ana tablo info_customer
     * @version v 1.0  20.08.2018
     * @param array | null $args
     * @return array
     * @throws \PDOException  
     */  
    public function fillCustomerGridxRtl($params = array()) {
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
                            case 'registration_name':
                                $sorguExpression = ' ILIKE \'%' . $std['value'] . '%\' ';
                                $sorguStr.=" AND COALESCE(NULLIF(ax.name, ''), a.name_eng)" . $sorguExpression . ' ';
                              
                                break;
                            case 'trading_name':
                                $sorguExpression = ' ILIKE \'%' . $std['value'] . '%\'  ';
                                $sorguStr.=" AND a.trading_name" . $sorguExpression . ' ';

                                break;  
                            case 'name_short':
                                $sorguExpression = ' ILIKE \'%' . $std['value'] . '%\'  ';
                                $sorguStr.=" AND a.name_short" . $sorguExpression . ' ';

                                break;  
                            case 'embrace_customer_no':
                                $sorguExpression = ' ILIKE \'%' . $std['value'] . '%\'  ';
                                $sorguStr.=" AND a.embrace_customer_no" . $sorguExpression . ' ';

                                break;  
                            case 'tu_emb_customer_no':
                                $sorguExpression = ' ILIKE \'%' . $std['value'] . '%\'  ';
                                $sorguStr.=" AND a.tu_emb_customer_no" . $sorguExpression . ' ';

                                break; 
                            case 'ce_emb_customer_no':
                                $sorguExpression = ' ILIKE \'%' . $std['value'] . '%\'  ';
                                $sorguStr.=" AND a.ce_emb_customer_no" . $sorguExpression . ' ';

                                break; 
                             case 'other_emb_customer_no':
                                $sorguExpression = ' ILIKE \'%' . $std['value'] . '%\'  ';
                                $sorguStr.=" AND a.other_emb_customer_no" . $sorguExpression . ' ';

                                break; 
                            case 'www':
                                $sorguExpression = ' ILIKE \'%' . $std['value'] . '%\'  ';
                                $sorguStr.=" AND a.www" . $sorguExpression . ' ';

                                break; 
                            case 'vatnumber':
                                $sorguExpression = ' ILIKE \'%' . $std['value'] . '%\'  ';
                                $sorguStr.=" AND a.vatnumber" . $sorguExpression . ' ';

                                break; 
                            case 'registration_number':
                                $sorguExpression = ' ILIKE \'%' . $std['value'] . '%\'  ';
                                $sorguStr.=" AND a.vatnumber" . $sorguExpression . ' ';

                                break; 
                            case 'customer_category_name':
                                $sorguExpression = ' ILIKE \'%' . $std['value'] . '%\'  ';
                                $sorguStr.=" AND a.customer_category_name" . $sorguExpression . ' ';

                                break; 
                            case 'reliability_name':
                                $sorguExpression = ' ILIKE \'%' . $std['value'] . '%\'  ';
                                $sorguStr.=" AND a.reliability_name" . $sorguExpression . ' ';

                                break;
                            case 'turnover_rate_name':
                                $sorguExpression = ' ILIKE \'%' . $std['value'] . '%\'  ';
                                $sorguStr.=" AND a.turnover_rate_name" . $sorguExpression . ' ';

                                break;
                            case 'sector_type_name':
                                $sorguExpression = ' ILIKE \'%' . $std['value'] . '%\'  ';
                                $sorguStr.=" AND a.sector_type_name" . $sorguExpression . ' ';

                                break;
                            case 'firm_country_name':
                                $sorguExpression = ' ILIKE \'%' . $std['value'] . '%\'  ';
                                $sorguStr.=" AND coun.name" . $sorguExpression . ' ';

                                break;
                            case 'application_type_name':
                                $sorguExpression = ' ILIKE \'%' . $std['value'] . '%\'  ';
                                $sorguStr.=" AND a.application_type_name" . $sorguExpression . ' ';

                                break;
                            case 'segment_type_name':
                                $sorguExpression = ' ILIKE \'%' . $std['value'] . '%\'  ';
                                $sorguStr.=" AND a.segment_type_name" . $sorguExpression . ' ';

                                break;  
                             case 'address1':
                                $sorguExpression = ' ILIKE \'%' . $std['value'] . '%\'  ';
                                $sorguStr.=" AND a.address1" . $sorguExpression . ' ';

                                break;  
                             case 'address2':
                                $sorguExpression = ' ILIKE \'%' . $std['value'] . '%\'  ';
                                $sorguStr.=" AND a.address2" . $sorguExpression . ' ';

                                break;  
                             case 'address3':
                                $sorguExpression = ' ILIKE \'%' . $std['value'] . '%\'  ';
                                $sorguStr.=" AND a.address3" . $sorguExpression . ' ';

                                break;  
                             case 'postalcode':
                                $sorguExpression = ' ILIKE \'%' . $std['value'] . '%\'  ';
                                $sorguStr.=" AND a.postalcode" . $sorguExpression . ' ';

                                break; 
                             case 'city_name':
                                $sorguExpression = ' ILIKE \'%' . $std['value'] . '%\'  ';
                                $sorguStr.=" AND city.name" . $sorguExpression . ' ';

                                break; 
                             case 'region':
                                $sorguExpression = ' ILIKE \'%' . $std['value'] . '%\'  ';
                                $sorguStr.=" AND region.name" . $sorguExpression . ' ';

                                break; 
                             case 'city_name':
                                $sorguExpression = ' ILIKE \'%' . $std['value'] . '%\'  ';
                                $sorguStr.=" AND city.name" . $sorguExpression . ' ';

                                break; 
                             case 'country_name':
                                $sorguExpression = ' ILIKE \'%' . $std['value'] . '%\'  ';
                                $sorguStr.=" AND coun2.name" . $sorguExpression . ' ';

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
            $isboConfirm = -1 ; 
            if (isset($params['IsBoConfirm']) && $params['IsBoConfirm'] != "") {
                $isboConfirm = $params['IsBoConfirm']; 
                 $addSql =  " a.is_bo_confirm = " . intval($isboConfirm)." AND " ;
            }      

                $sql = "
                   SELECT COUNT(asdx.id) count FROM ( 
                        SELECT  
                        a.id, 
                        a.act_parent_id as apid, 
                        a.registration_name, 
                        a.trading_name,
                        a.name_short, 
                        a.embrace_customer_no , 
                        a.tu_emb_customer_no,
			a.ce_emb_customer_no,
			a.other_emb_customer_no,
                        a.www, 
                        a.vatnumber, 
                        a.registration_number, 
                        a.registration_date, 
                        a.ne_count_type_id, 
                        nre.name numberofemployees, 
                       
                        a.nv_count_type_id, 
                         nrv.name numberofvehicles,
                         
                        a.customer_category_id, 
                        cc.name customer_category_name, 
                        
                        a.reliability_id, 
                        scr.name reliability_name, 

                        a.turnover_rate_id, 
			tr.name turnover_rate_name, 
                        
                        a.sector_type_id, 
			st.name sector_type_name, 
                        
                        a.application_type_id, 
			cat.name application_type_name, 

                        a.segment_type_id,
                        cst.name segment_type_name, 

                        
                        a.is_bo_confirm, 
                        a.country2_id firm_country_id, 
                        coun.name firm_country_name,
 
			a.address1,
			a.address2,
			a.address3,
			a.postalcode,
			
			a.city_id,
			city.name as city_name, 
			city.region_id, 
			region.name as region, 
			a.country_id, 			
			coun2.name country_name,
                        
                        
                      /*  a.name_eng, */  
                        a.active,
                        COALESCE(NULLIF(sd16x.description, ''), sd16.description_eng) AS state_active,
                       /* a.deleted,
                        COALESCE(NULLIF(sd15x.description, ''), sd15.description_eng) AS state_deleted,*/
                        a.op_user_id,
                        u.username AS op_user_name,  
                        a.s_date date_saved,
                        a.c_date date_modified, 
                        COALESCE(NULLIF(lx.id, NULL), 385) AS language_id, 
                        lx.language_main_code language_code, 
                        COALESCE(NULLIF(lx.language, ''), 'en') AS language_name
                    FROM info_customer a                    
                    INNER JOIN sys_language l ON l.id = 385 AND l.show_it =0
                    LEFT JOIN sys_language lx ON lx.id =" . intval($languageIdValue) . "  AND lx.show_it =0    
                    LEFT JOIN info_customer ax ON (ax.act_parent_id =a.act_parent_id ) AND ax.show_it =0 
               
                    INNER JOIN info_users u ON u.id = a.op_user_id 
                    /*----*/   
		      
                    LEFT JOIN sys_countrys coun ON coun.id = a.country2_id AND coun.show_it = 0 
                    LEFT JOIN sys_countrys coun2 ON coun2.id = a.country_id AND coun2.show_it = 0 
		    LEFT JOIN sys_city city ON city.id = a.city_id AND city.show_it = 0 
		    LEFT JOIN sys_country_regions region ON region.id = a.city_id AND region.show_it = 0 
                    
                    LEFT JOIN sys_numerical_ranges nre ON nre.act_parent_id = a.ne_count_type_id AND nre.show_it = 0 AND nre.parent_id = 13
                    LEFT JOIN sys_numerical_ranges nrv ON nrv.act_parent_id = a.nv_count_type_id AND nrv.show_it = 0 AND nrv.parent_id = 20
                    inner join sys_customer_categories cc on cc.act_parent_id = a.customer_category_id and cc.show_it = 0 
		    inner join sys_customer_reliability scr on scr.act_parent_id = a.reliability_id and scr.show_it = 0 
		    left join sys_customer_turnover_rates tr on tr.act_parent_id = a.turnover_rate_id and tr.show_it = 0 
		    left join sys_customer_sector_types st on st.act_parent_id = a.sector_type_id and st.show_it = 0 
		    left join sys_customer_application_types cat on cat.act_parent_id = a.application_type_id and cat.show_it = 0 
                    left join sys_customer_segment_types cst on cst.act_parent_id = a.segment_type_id and cst.show_it = 0 
		     
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
     * @ info_customer tablosundan parametre olarak  gelen id kaydını active ve show_it alanlarını 1 yapar. !!
     * @version v 1.0  24.08.2018
     * @param type $params
     * @return array
     * @throws \PDOException
     */
    public function makePassive($params = array()) {
        try {
            $pdo = $this->slimApp->getServiceManager()->get('pgConnectFactory'); 
            $statement = $pdo->prepare(" 
                UPDATE info_customer
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
     * @ info_customer tablosundan parametre olarak  gelen id kaydın active veshow_it  alanını 1 yapar ve 
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
                    INSERT INTO info_customer ( 
                        embrace_customer_no, 
                        tu_emb_customer_no, 
                        ce_emb_customer_no, 
                        other_emb_customer_no
                        registration_name, 
                        trading_name, 
                        name_short, 
                        www, 
                        vatnumber, 
                        registration_number, 
                        registration_date, 
                        ne_count_type_id, 
                        nv_count_type_id, 
                        customer_category_id, 
                        reliability_id, 
                        turnover_rate_id, 
                        sector_type_id, 
                        application_type_id, 
                        segment_type_id,  

                        is_bo_confirm, 
                        country2_id,
                        
                        address1,
			address2,
			address3,
			postalcode,
			
			city_id, 
			country_id,  
                         
                        active,
                        deleted,
                        op_user_id,
                        act_parent_id,
                        show_it
                        )
                    SELECT
                        embrace_customer_no, 
                        tu_emb_customer_no, 
                        ce_emb_customer_no, 
                        other_emb_customer_no
                        registration_name, 
                        trading_name, 
                        name_short, 
                        www, 
                        vatnumber, 
                        registration_number, 
                        registration_date, 
                        ne_count_type_id, 
                        nv_count_type_id, 
                        customer_category_id, 
                        reliability_id, 
                        turnover_rate_id, 
                        sector_type_id, 
                        application_type_id, 
                        segment_type_id,  

                        is_bo_confirm, 
                        country2_id,
                        
                        address1,
			address2,
			address3,
			postalcode,
			
			city_id, 
			country_id,  
                         
                        1 AS active,  
                        1 AS deleted, 
                        " . intval($opUserIdValue) . " AS op_user_id, 
                        act_parent_id,
                        0 AS show_it 
                    FROM info_customer 
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
     * @ info_customer tablosuna yeni bir kayıt oluşturur.  !! 
     * @version v 1.0  26.08.2018
     * @param type $params
     * @return array
     * @throws \PDOException
     */
    public function insertAct($params = array()) {
        try {
            $pdo = $this->slimApp->getServiceManager()->get('pgConnectFactory');
            $pdo->beginTransaction();
            $kontrol =0 ;                
            $errorInfo[0] = "99999";
            $addSQL1 =null ;    
            $addSQL2 =null ;             
            $embraceCustomerNo = null;
            if ((isset($params['EmbraceCustomerNo']) && $params['EmbraceCustomerNo'] != "")) {
                $embraceCustomerNo = $params['EmbraceCustomerNo'];
            } else {
               $kontrol=$kontrol+1 ;  
            }
            $tuEmbCustomerNo = null;
            if ((isset($params['TuEmbCustomerNo']) && $params['TuEmbCustomerNo'] != "")) {
                $tuEmbCustomerNo = $params['TuEmbCustomerNo'];
            } else {
                $kontrol=$kontrol+1 ;  
            }
            $ceEmbCustomerNo = null;
            if ((isset($params['CeEmbCustomerNo']) && $params['CeEmbCustomerNo'] != "")) {
                $ceEmbCustomerNo = $params['CeEmbCustomerNo'];
            } else {
                 $kontrol=$kontrol+1 ;  
            }
            $otherEmbCustomerNo= null;
            if ((isset($params['OtherEmbCustomerNo']) && $params['OtherEmbCustomerNo'] != "")) {
                $otherEmbCustomerNo = $params['OtherEmbCustomerNo'];
            } else {
                 $kontrol=$kontrol+1 ;  
            }
                     
            if ($kontrol> 1 ) {
                throw new \PDOException($errorInfo[0]);
            }
            $registrationName = null;
            if ((isset($params['RegistrationName']) && $params['RegistrationName'] != "")) {
                $registrationName = $params['RegistrationName'];
            } else {
                throw new \PDOException($errorInfo[0]);
            }
            
            $tradingName = null;
            if ((isset($params['TradingName']) && $params['TradingName'] != "")) {
                $tradingName = $params['TradingName'];
            }  
            $nameShort = null;
            if ((isset($params['NameShort']) && $params['NameShort'] != "")) {
                $nameShort = $params['NameShort'];
            } else {
                throw new \PDOException($errorInfo[0]);
            }
            $www = null;
            if ((isset($params['www']) && $params['www'] != "")) {
                $www = $params['www'];
            }  
            $vatnumber = null;
            if ((isset($params['Vatnumber']) && $params['Vatnumber'] != "")) {
                $vatnumber = $params['Vatnumber'];
            } 
            $registrationNumber= null;
            if ((isset($params['RegistrationNumber']) && $params['RegistrationNumber'] != "")) {
                $registrationNumber = $params['RegistrationNumber'];
            } else {
                throw new \PDOException($errorInfo[0]);
            }
            $registrationDate= null;
            if ((isset($params['RegistrationDate']) && $params['RegistrationDate'] != "")) {
                $registrationDate = $params['RegistrationDate'];
                $addSQL1 = 'registration_date,  ';
                $addSQL2 = "'". $registrationDate."'";
            }  
                            
            $neCountTypeId = 0;
            if ((isset($params['NeCountTypeId']) && $params['NeCountTypeId'] != "")) {
                $neCountTypeId = intval($params['NeCountTypeId']);
            }  
            $nvCountTypeId = 0;
            if ((isset($params['NvCountTypeId']) && $params['NvCountTypeId'] != "")) {
                $nvCountTypeId = intval($params['NvCountTypeId']);
            } 
            $customerCategoryId= 0;
            if ((isset($params['CustomerCategoryId']) && $params['CustomerCategoryId'] != "")) {
                $customerCategoryId = intval($params['CustomerCategoryId']);
            } 
            $reliabilityId = 0;
            if ((isset($params['ReliabilityId']) && $params['ReliabilityId'] != "")) {
                $reliabilityId = intval($params['ReliabilityId']);
            } 
            $turnoverRateId = 0;
            if ((isset($params['TurnoverRateId']) && $params['TurnoverRateId'] != "")) {
                $turnoverRateId = intval($params['TurnoverRateId']);
            } 
            $sectorTypeId =0;
            if ((isset($params['SectorTypeId']) && $params['SectorTypeId'] != "")) {
                $sectorTypeId = intval($params['SectorTypeId']);
            }                
            $applicationTypeId= 0;
            if ((isset($params['ApplicationTypeId']) && $params['ApplicationTypeId'] != "")) {
                $applicationTypeId = intval($params['ApplicationTypeId']);
            }  
            $segmentTypeId= 0;
            if ((isset($params['SegmentTypeId']) && $params['SegmentTypeId'] != "")) {
                $segmentTypeId = intval($params['SegmentTypeId']);
            }  
            $firmCountryId= 107;
            if ((isset($params['FirmCountryId']) && $params['FirmCountryId'] != "")) {
                $firmCountryId = intval($params['FirmCountryId']);
            }  
            $address1= null;
            if ((isset($params['Address1']) && $params['Address1'] != "")) {
                $address1 = $params['Address1'];
            }  
            $address2= null;
            if ((isset($params['Address2']) && $params['Address2'] != "")) {
                $address2 = $params['Address2'];
            }  
            $address3= null;
            if ((isset($params['Address3']) && $params['Address3'] != "")) {
                $address3 = $params['Address3'];
            }  
            $postalCode= null;
            if ((isset($params['PostalCode']) && $params['PostalCode'] != "")) {
                $postalCode = $params['PostalCode'];
            }  
            $countryId= 107;
            if ((isset($params['CountryId']) && $params['CountryId'] != "")) {
                $countryId = intval($params['CountryId']);
            }  
            $cityId= 0;
            if ((isset($params['CityId']) && $params['CityId'] != "")) {
                $cityId = intval($params['CityId']);
            }  
                            
            $opUserId = InfoUsers::getUserId(array('pk' => $params['pk']));
            if (\Utill\Dal\Helper::haveRecord($opUserId)) {
                $opUserIdValue = $opUserId ['resultSet'][0]['user_id'];

                $kontrol = $this->haveRecords(
                        array(
                            'embrace_customer_no' => $embraceCustomerNo, 
                            'tu_emb_customer_no' => $tuEmbCustomerNo,
                            'ce_emb_customer_no' => $ceEmbCustomerNo,
                            'other_emb_customer_no' => $otherEmbCustomerNo,
                            'registration_name' => $registrationName,
                         //   'registration_number' => $registrationDate,  
                ));
                if (!\Utill\Dal\Helper::haveRecord($kontrol)) {
                    $sql = "
                    INSERT INTO info_customer(
                            embrace_customer_no, 
                            tu_emb_customer_no, 
                            ce_emb_customer_no, 
                            other_emb_customer_no
                            registration_name, 
                            trading_name, 
                            name_short, 
                            www, 
                            vatnumber, 
                            registration_number, 
                            ".$addSQL1." 
                            ne_count_type_id, 
                            nv_count_type_id, 
                            customer_category_id, 
                            reliability_id, 
                            turnover_rate_id, 
                            sector_type_id, 
                            application_type_id, 
                            segment_type_id,    
                            
                            country2_id,
            
                            address1,
                            address2,
                            address3,
                            postalcode,

                            city_id, 
                            country_id,  
 
                            op_user_id,
                            act_parent_id  
                            )
                    VALUES (
                            '" . $embraceCustomerNo . "',
                            '" . $tuEmbCustomerNo . "',
                            '" . $ceEmbCustomerNo . "',
                            '" . $otherEmbCustomerNo . "',
                            '" . $registrationName . "',
                            '" . $tradingName . "',
                            '" . $nameShort . "',
                            '" . $www . "',
                            '" . $vatnumber . "',
                            '" . $registrationNumber . "',
                            ".$addSQL2." 
                            " .  intval($neCountTypeId). ",
                            " .  intval($nvCountTypeId) . ",
                            " .  intval($customerCategoryId). ",
                            " .  intval($reliabilityId) . ",
                            " .  intval($turnoverRateId). ",
                            " .  intval($sectorTypeId) . ",
                            " .  intval($applicationTypeId) . ",
                            " .  intval($segmentTypeId) . ",
                            " .  intval($firmCountryId) . ",
                            '" . $address1 . "',
                            '" . $address2 . "',
                            '" . $address3 . "',
                            '" . $postalCode. "',
                            " .  intval($cityId) . ",
                            " .  intval($countryId) . ",
                                
 
                            " . intval($opUserIdValue) . ",
                           (SELECT last_value FROM info_customer_id_seq)
                                                 )   ";
                    $statement = $pdo->prepare($sql);
                    //   echo debugPDO($sql, $params);
                    $result = $statement->execute();
                    $errorInfo = $statement->errorInfo();
                    if ($errorInfo[0] != "00000" && $errorInfo[1] != NULL && $errorInfo[2] != NULL)
                        throw new \PDOException($errorInfo[0]);
                    $insertID = $pdo->lastInsertId('info_customer_id_seq');

                            

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
     * info_customer tablosuna parametre olarak gelen id deki kaydın bilgilerini günceller   !!
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
            
            $kontrol =0 ;                
            $errorInfo[0] = "99999";
            $addSQL1 =null ;    
            $addSQL2 =null ;             
           $embraceCustomerNo = null;
            if ((isset($params['EmbraceCustomerNo']) && $params['EmbraceCustomerNo'] != "")) {
                $embraceCustomerNo = $params['EmbraceCustomerNo'];
            } else {
               $kontrol=$kontrol+1 ;  
            }
            $tuEmbCustomerNo = null;
            if ((isset($params['TuEmbCustomerNo']) && $params['TuEmbCustomerNo'] != "")) {
                $tuEmbCustomerNo = $params['TuEmbCustomerNo'];
            } else {
                $kontrol=$kontrol+1 ;  
            }
            $ceEmbCustomerNo = null;
            if ((isset($params['CeEmbCustomerNo']) && $params['CeEmbCustomerNo'] != "")) {
                $ceEmbCustomerNo = $params['CeEmbCustomerNo'];
            } else {
                 $kontrol=$kontrol+1 ;  
            }
            $otherEmbCustomerNo= null;
            if ((isset($params['OtherEmbCustomerNo']) && $params['OtherEmbCustomerNo'] != "")) {
                $otherEmbCustomerNo = $params['OtherEmbCustomerNo'];
            } else {
                 $kontrol=$kontrol+1 ;  
            }
                     
            if ($kontrol> 1 ) {
                throw new \PDOException($errorInfo[0]);
            }
            $registrationName = null;
            if ((isset($params['RegistrationName']) && $params['RegistrationName'] != "")) {
                $registrationName = $params['RegistrationName'];
            } else {
                throw new \PDOException($errorInfo[0]);
            }
            
            $tradingName = null;
            if ((isset($params['TradingName']) && $params['TradingName'] != "")) {
                $tradingName = $params['TradingName'];
            }  
            $nameShort = null;
            if ((isset($params['NameShort']) && $params['NameShort'] != "")) {
                $nameShort = $params['NameShort'];
            } else {
                throw new \PDOException($errorInfo[0]);
            }
            $www = null;
            if ((isset($params['www']) && $params['www'] != "")) {
                $www = $params['www'];
            }  
            $vatnumber = null;
            if ((isset($params['Vatnumber']) && $params['Vatnumber'] != "")) {
                $vatnumber = $params['Vatnumber'];
            } 
            $registrationNumber= null;
            if ((isset($params['RegistrationNumber']) && $params['RegistrationNumber'] != "")) {
                $registrationNumber = $params['RegistrationNumber'];
            } else {
                throw new \PDOException($errorInfo[0]);
            }
            $registrationDate= null;
            if ((isset($params['RegistrationDate']) && $params['RegistrationDate'] != "")) {
                $registrationDate = $params['RegistrationDate'];
                $addSQL1 = 'registration_date,  ';
                $addSQL2 = "'". $registrationDate."'";
            }  
                            
            $neCountTypeId = 0;
            if ((isset($params['NeCountTypeId']) && $params['NeCountTypeId'] != "")) {
                $neCountTypeId = intval($params['NeCountTypeId']);
            }  
            $nvCountTypeId = 0;
            if ((isset($params['NvCountTypeId']) && $params['NvCountTypeId'] != "")) {
                $nvCountTypeId = intval($params['NvCountTypeId']);
            } 
            $customerCategoryId= 0;
            if ((isset($params['CustomerCategoryId']) && $params['CustomerCategoryId'] != "")) {
                $customerCategoryId = intval($params['CustomerCategoryId']);
            } 
            $reliabilityId = 0;
            if ((isset($params['ReliabilityId']) && $params['ReliabilityId'] != "")) {
                $reliabilityId = intval($params['ReliabilityId']);
            } 
            $turnoverRateId = 0;
            if ((isset($params['TurnoverRateId']) && $params['TurnoverRateId'] != "")) {
                $turnoverRateId = intval($params['TurnoverRateId']);
            } 
            $sectorTypeId =0;
            if ((isset($params['SectorTypeId']) && $params['SectorTypeId'] != "")) {
                $sectorTypeId = intval($params['SectorTypeId']);
            }                
            $applicationTypeId= 0;
            if ((isset($params['ApplicationTypeId']) && $params['ApplicationTypeId'] != "")) {
                $applicationTypeId = intval($params['ApplicationTypeId']);
            }  
            $segmentTypeId= 0;
            if ((isset($params['SegmentTypeId']) && $params['SegmentTypeId'] != "")) {
                $segmentTypeId = intval($params['SegmentTypeId']);
            }  
            $firmCountryId= 107;
            if ((isset($params['FirmCountryId']) && $params['FirmCountryId'] != "")) {
                $firmCountryId = intval($params['FirmCountryId']);
            }  
            $address1= null;
            if ((isset($params['Address1']) && $params['Address1'] != "")) {
                $address1 = $params['Address1'];
            }  
            $address2= null;
            if ((isset($params['Address2']) && $params['Address2'] != "")) {
                $address2 = $params['Address2'];
            }  
            $address3= null;
            if ((isset($params['Address3']) && $params['Address3'] != "")) {
                $address3 = $params['Address3'];
            }  
            $postalCode= null;
            if ((isset($params['PostalCode']) && $params['PostalCode'] != "")) {
                $postalCode = $params['PostalCode'];
            }  
            $countryId= 107;
            if ((isset($params['CountryId']) && $params['CountryId'] != "")) {
                $countryId = intval($params['CountryId']);
            }  
             $cityId= 0;
            if ((isset($params['CityId']) && $params['CityId'] != "")) {
                $cityId = intval($params['CityId']);
            }  
                  
                            
            $opUserIdParams = array('pk' => $params['pk'],);
            $opUserIdArray = $this->slimApp->getBLLManager()->get('opUserIdBLL');
            $opUserId = $opUserIdArray->getUserId($opUserIdParams);
            if (\Utill\Dal\Helper::haveRecord($opUserId)) {
                $opUserIdValue = $opUserId ['resultSet'][0]['user_id'];
                $opUserRoleIdValue = $opUserId ['resultSet'][0]['role_id'];

                $kontrol = $this->haveRecords(
                        array(
                            'embrace_customer_no' => $embraceCustomerNo, 
                            'tu_emb_customer_no' => $tuEmbCustomerNo,
                            'ce_emb_customer_no' => $ceEmbCustomerNo,
                            'other_emb_customer_no' => $otherEmbCustomerNo,
                            'registration_name' => $registrationName,
                         //   'registration_number' => $registrationDate,  
                            'id' => $Id
                ));
                if (!\Utill\Dal\Helper::haveRecord($kontrol)) {

                    $this->makePassive(array('id' => $params['Id']));

                    $statementInsert = $pdo->prepare("
                INSERT INTO info_customer (  
                        embrace_customer_no, 
                        tu_emb_customer_no, 
                        ce_emb_customer_no, 
                        other_emb_customer_no
                        registration_name, 
                        trading_name, 
                        name_short, 
                        www, 
                        vatnumber, 
                        registration_number, 
                        ".$addSQL1." 
                        ne_count_type_id, 
                        nv_count_type_id, 
                        customer_category_id, 
                        reliability_id, 
                        turnover_rate_id, 
                        sector_type_id, 
                        application_type_id, 
                        segment_type_id,    

                        country2_id,

                        address1,
                        address2,
                        address3,
                        postalcode,

                        city_id, 
                        country_id,  

                        op_user_id,
                        act_parent_id  
                        )  
                SELECT  
                    '" . $embraceCustomerNo . "',
                    '" . $tuEmbCustomerNo . "',
                    '" . $ceEmbCustomerNo . "',
                    '" . $otherEmbCustomerNo . "',
                    '" . $registrationName . "',
                    '" . $tradingName . "',
                    '" . $nameShort . "',
                    '" . $www . "',
                    '" . $vatnumber . "',
                    '" . $registrationNumber . "',
                    ".$addSQL2." 
                    " .  intval($neCountTypeId). ",
                    " .  intval($nvCountTypeId) . ",
                    " .  intval($customerCategoryId). ",
                    " .  intval($reliabilityId) . ",
                    " .  intval($turnoverRateId). ",
                    " .  intval($sectorTypeId) . ",
                    " .  intval($applicationTypeId) . ",
                    " .  intval($segmentTypeId) . ",
                    " .  intval($firmCountryId) . ",
                    '" . $address1 . "',
                    '" . $address2 . "',
                    '" . $address3 . "',
                    '" . $postalCode. "',
                    " .  intval($cityId) . ",
                    " .  intval($countryId) . ",
                                 
                    " . intval($opUserIdValue) . " AS op_user_id,  
                    act_parent_id
                FROM info_customer 
                WHERE 
                    id  =" . intval($Id) . "                  
                                                ");
                    $result = $statementInsert->execute();  
                    $errorInfo = $statementInsert->errorInfo();
                    if ($errorInfo[0] != "00000" && $errorInfo[1] != NULL && $errorInfo[2] != NULL)
                        throw new \PDOException($errorInfo[0]);
                            
                     $affectedRows = $statementInsert->rowCount();
                    if ($affectedRows> 0 ){
                    $insertID = $pdo->lastInsertId('info_customer_id_seq');}
                    else $insertID =0 ;   
                            
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
