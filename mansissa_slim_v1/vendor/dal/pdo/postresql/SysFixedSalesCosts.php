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
class SysFixedSalesCosts extends \DAL\DalSlim {

    /**
     * @author Okan CIRAN
     * @ sys_fixed_sales_costs tablosundan parametre olarak  gelen id kaydını siler. !!
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
                UPDATE sys_fixed_sales_costs
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
     * @ sys_fixed_sales_costs tablosundaki tüm kayıtları getirir.  !!
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
                FROM sys_fixed_sales_costs a
                INNER JOIN sys_language l ON l.id = a.language_id AND l.deleted =0 AND l.active = 0 
                LEFT JOIN sys_language lx ON lx.id = ".intval($languageIdValue)." AND lx.deleted =0 AND lx.active =0
                INNER JOIN sys_specific_definitions sd15 ON sd15.main_group = 15 AND sd15.first_group= a.deleted AND sd15.language_id = a.language_id AND sd15.deleted = 0 
                INNER JOIN sys_specific_definitions sd16 ON sd16.main_group = 16 AND sd16.first_group= a.active AND sd16.language_id = a.language_id AND sd16.deleted = 0
                INNER JOIN info_users u ON u.id = a.op_user_id    
                LEFT JOIN sys_specific_definitions sd15x ON (sd15x.id = sd15.id OR sd15x.language_parent_id = sd15.id) AND sd15x.language_id =lx.id  AND sd15x.deleted =0 AND sd15x.active =0 
                LEFT JOIN sys_specific_definitions sd16x ON (sd16x.id = sd16.id OR sd16x.language_parent_id = sd16.id)AND sd16x.language_id = lx.id  AND sd16x.deleted = 0 AND sd16x.active = 0
                LEFT JOIN sys_fixed_sales_costs ax ON (ax.id = a.id OR ax.language_parent_id = a.id) AND ax.deleted =0 AND ax.active =0 AND lx.id = ax.language_id
                
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
     * @ sys_fixed_sales_costs tablosuna yeni bir kayıt oluşturur.  !!
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
                INSERT INTO sys_fixed_sales_costs(
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
                    $insertID = $pdo->lastInsertId('sys_fixed_sales_costs_id_seq');
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
     * @ sys_fixed_sales_costs tablosunda property_name daha önce kaydedilmiş mi ?  
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
            FROM sys_fixed_sales_costs  a                          
            WHERE 
                LOWER(REPLACE(name,' ','')) = LOWER(REPLACE('" . $params['name'] . "',' ','')) AND 
                a.currency_type_id   = " . intval($params['currency_type_id']) . " 
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
     * sys_fixed_sales_costs tablosuna parametre olarak gelen id deki kaydın bilgilerini günceller   !!
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
                    UPDATE sys_fixed_sales_costs
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
     * @ Gridi doldurmak için sys_fixed_sales_costs tablosundan kayıtları döndürür !!
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
                FROM sys_fixed_sales_costs a
                INNER JOIN sys_language l ON l.id = a.language_id AND l.deleted =0 AND l.active = 0 
                LEFT JOIN sys_language lx ON lx.id = ".intval($languageIdValue)." AND lx.deleted =0 AND lx.active =0
                INNER JOIN sys_specific_definitions sd15 ON sd15.main_group = 15 AND sd15.first_group= a.deleted AND sd15.language_id = a.language_id AND sd15.deleted = 0 
                INNER JOIN sys_specific_definitions sd16 ON sd16.main_group = 16 AND sd16.first_group= a.active AND sd16.language_id = a.language_id AND sd16.deleted = 0
                INNER JOIN info_users u ON u.id = a.op_user_id    
                LEFT JOIN sys_specific_definitions sd15x ON (sd15x.id = sd15.id OR sd15x.language_parent_id = sd15.id) AND sd15x.language_id =lx.id  AND sd15x.deleted =0 AND sd15x.active =0 
                LEFT JOIN sys_specific_definitions sd16x ON (sd16x.id = sd16.id OR sd16x.language_parent_id = sd16.id)AND sd16x.language_id = lx.id  AND sd16x.deleted = 0 AND sd16x.active = 0
                LEFT JOIN sys_fixed_sales_costs ax ON (ax.id = a.id OR ax.language_parent_id = a.id) AND ax.deleted =0 AND ax.active =0 AND lx.id = ax.language_id
                
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
     * @ Gridi doldurmak için sys_fixed_sales_costs tablosundan çekilen kayıtlarının kaç tane olduğunu döndürür   !!
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
                FROM sys_fixed_sales_costs a
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
     * @ sys_fixed_sales_costs tablosundan parametre olarak  gelen id kaydın aktifliğini
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
                UPDATE sys_fixed_sales_costs
                SET active = (  SELECT   
                                CASE active
                                    WHEN 0 THEN 1
                                    ELSE 0
                                END activex
                                FROM sys_fixed_sales_costs
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
     * @ Sabit gider tanımları dropdown ya da tree ye doldurmak için sys_fixed_sales_costs tablosundan kayıtları döndürür !!
     * @version v 1.0  11.08.2018
     * @param array | null $args
     * @return array
     * @throws \PDOException 
     */
    public function fixedSalesCostsDdList($params = array()) {
        try {
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
              
            $statement = $pdo->prepare("       

            SELECT * FROM (  

                SELECT                    
                   0 AS id, 	
                    COALESCE(NULLIF(sd.description, ''), a.description_eng) AS name,  
                    a.description_eng AS name_eng,
                    0 as parent_id,
                    a.active,
                    0 AS state_type   
                FROM sys_specific_definitions a    
                INNER JOIN sys_language l ON l.id = a.language_id AND l.deleted =0 AND l.active =0  
		LEFT JOIN sys_language lx ON lx.id = " . intval($languageIdValue). "  AND lx.deleted =0 AND lx.active =0                      		
                LEFT JOIN sys_specific_definitions sd ON (sd.id =a.id OR sd.language_parent_id = a.id) AND sd.deleted =0 AND sd.active =0 AND lx.id = sd.language_id                   
                WHERE                     
                    a.main_group = 31 AND   
                    a.first_group = 1 AND                   
                    a.deleted = 0 AND
                    a.active =0 AND
                    a.language_parent_id =0 
                 
                UNION 

                SELECT                    
                    a.act_parent_id AS id, 	
                    COALESCE(NULLIF(sd.name, ''), a.name_eng) AS name,  
                    a.name_eng AS name_eng,
                     0 as parent_id,
                    a.active,
                    0 AS state_type   
                FROM sys_fixed_sales_costs a    
                INNER JOIN sys_language l ON l.id = a.language_id AND l.deleted =0 AND l.active =0  
		LEFT JOIN sys_language lx ON lx.id = " . intval($languageIdValue). "  AND lx.deleted =0 AND lx.active =0                      		
                LEFT JOIN sys_fixed_sales_costs sd ON (sd.act_parent_id =a.act_parent_id OR sd.language_parent_id = a.act_parent_id) AND sd.deleted =0 AND sd.active =0 AND lx.id = sd.language_id   
                WHERE   
                    a.deleted = 0 AND
                    a.active =0 AND
                    a.language_parent_id =0 
                    ) asd 
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
     * @ Sabit gider tanımlarını grid formatında döndürür !! ana tablo  sys_fixed_sales_costs 
     * @version v 1.0  20.08.2018
     * @param array | null $args
     * @return array
     * @throws \PDOException  
     */  
    public function fixedSalesCostsGridx($params = array()) {
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
                            case 'name':
                                $sorguExpression = ' ILIKE \'%' . $std['value'] . '%\' ';
                                $sorguStr.=" AND COALESCE(NULLIF(ax.name, ''), a.name_eng)" . $sorguExpression . ' ';
                              
                                break; 
                            case 'currency_name':
                                $sorguExpression = ' ILIKE \'%' . $std['value'] . '%\' ';
                                $sorguStr.=" AND COALESCE(NULLIF(ctx.name, ''), ct.name_eng)" . $sorguExpression . ' ';
                              
                                break; 
                            case 'name_eng':
                                $sorguExpression = ' ILIKE \'%' . $std['value'] . '%\'  ';
                                $sorguStr.=" AND a.name_eng" . $sorguExpression . ' ';

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
            $currencyTypeID =0 ;
            if (isset($params['CurrencyTypeID']) && $params['CurrencyTypeID'] != "") {
                $currencyTypeID = $params['CurrencyTypeID'];
                $addSql ="  a.currency_type_id  = " . intval($currencyTypeID). "  AND  " ; 
            }  

                $sql = "
                    SELECT  
                        a.id, 
                        a.act_parent_id as apid,  
                        COALESCE(NULLIF(ax.name, ''), a.name_eng) AS name, 
			a.vvalue,
			a.currency_type_id,
			COALESCE(NULLIF(ctx.name, ''), ct.name_eng) AS currency_name,
 
                        a.vehicle_gruop_id,
                        vg.name AS vehicle_gruop_name  , 
                        a.vehicle_second_group_id,
                        vge.model_description,
                        a.vvalue,
                        a.currency_type_id,
                        a.start_date,
                        a.is_all_vehicle ,
                        a.warranty_matrix_id,
                        wm.unique_code AS warranty_matrix_name, 
                       
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
                    FROM sys_fixed_sales_costs a                    
                    INNER JOIN sys_language l ON l.id = a.language_id AND l.show_it =0
                    LEFT JOIN sys_language lx ON lx.id =" . intval($languageIdValue) . "   AND lx.show_it =0   
                    LEFT JOIN sys_fixed_sales_costs ax ON (ax.act_parent_id = a.act_parent_id OR ax.language_parent_id = a.act_parent_id) AND ax.deleted =0 AND ax.active = 0 AND ax.language_id = lx.id
                    INNER JOIN info_users u ON u.id = a.op_user_id 
                    /*----*/   

		   LEFT JOIN sys_vehicle_groups vg ON vg.act_parent_id = a.vehicle_gruop_id  AND vg.show_it =0 
		   LEFT JOIN sys_vehicle_gt_models vge ON vge.act_parent_id = a.vehicle_second_group_id  AND vge.show_it =0 
		   LEFT JOIN sys_vehicle_group_types vgt ON vgt.act_parent_id = vge.vehicle_group_types_id  AND vgt.show_it =0 

		   LEFT JOIN sys_warranty_matrix wm ON wm.act_parent_id = a.warranty_matrix_id  AND wm.show_it =0 

		    
		    INNER JOIN sys_currency_types ct ON ct.act_parent_id = a.currency_type_id AND ct.show_it = 0 AND ct.language_id= l.id
		    LEFT JOIN sys_currency_types ctx ON (ctx.act_parent_id = ct.act_parent_id OR ctx.language_parent_id= ct.act_parent_id) AND ctx.show_it= 0 AND ctx.language_id =lx.id  
                    /*----*/   
			 
                   /* INNER JOIN sys_specific_definitions sd15 ON sd15.main_group = 15 AND sd15.first_group= a.deleted AND sd15.deleted =0 AND sd15.active =0 AND sd15.language_parent_id =0 */
                    INNER JOIN sys_specific_definitions sd16 ON sd16.main_group = 16 AND sd16.first_group= a.active AND sd16.deleted = 0 AND sd16.active = 0 AND sd16.language_id =l.id
                    /**/
                  /*  LEFT JOIN sys_specific_definitions sd15x ON sd15x.language_id =lx.id AND (sd15x.id = sd15.id OR sd15x.language_parent_id = sd15.id) AND sd15x.deleted =0 AND sd15x.active =0  */
                    LEFT JOIN sys_specific_definitions sd16x ON sd16x.language_id = lx.id AND (sd16x.id = sd16.id OR sd16x.language_parent_id = sd16.id) AND sd16x.deleted = 0 AND sd16x.active = 0
                    
                    WHERE  
                      " . $addSql . "
                        a.deleted =0 AND
                        a.show_it =0 AND                        
                        a.language_parent_id =0  
                     
              
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
     * @ Sabit gider tanımlarını grid formatında gösterilirken kaç kayıt olduğunu döndürür !! ana tablo  sys_fixed_sales_costs 
     * @version v 1.0  20.08.2018
     * @param array | null $args
     * @return array
     * @throws \PDOException  
     */  
    public function fixedSalesCostsGridxRtl($params = array()) {
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
                            case 'currency_name':
                                $sorguExpression = ' ILIKE \'%' . $std['value'] . '%\' ';
                                $sorguStr.=" AND COALESCE(NULLIF(ctx.name, ''), ct.name_eng)" . $sorguExpression . ' ';
                              
                                break; 
                            case 'name_eng':
                                $sorguExpression = ' ILIKE \'%' . $std['value'] . '%\'  ';
                                $sorguStr.=" AND a.name_eng" . $sorguExpression . ' ';

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
            $currencyTypeID =0 ;
            if (isset($params['CurrencyTypeID']) && $params['CurrencyTypeID'] != "") {
                $currencyTypeID = $params['CurrencyTypeID'];
                $addSql ="  a.currency_type_id  = " . intval($currencyTypeID). "  AND  " ; 
            }  

                $sql = "
                   SELECT COUNT(asdx.id) count FROM ( 
                         SELECT  
                        a.id, 
                        a.act_parent_id as apid,  
                        COALESCE(NULLIF(ax.name, ''), a.name_eng) AS name, 
			a.vvalue,
			a.currency_type_id,
			COALESCE(NULLIF(ctx.name, ''), ct.name_eng) AS currency_name,
 
                         a.vehicle_gruop_id,
                        vg.name AS vehicle_gruop_name  , 
                        a.vehicle_second_group_id,
                        vge.model_description,
                        a.vvalue,
                        a.currency_type_id,
                        a.start_date,
                        a.is_all_vehicle ,
                        a.warranty_matrix_id,
                        wm.unique_code AS warranty_matrix_name, 
                       
                        COALESCE(NULLIF(sd16x.description, ''), sd16.description_eng) AS state_active,  
                        u.username AS op_user_name 
                    FROM sys_fixed_sales_costs a                    
                    INNER JOIN sys_language l ON l.id = a.language_id AND l.show_it =0
                    LEFT JOIN sys_language lx ON lx.id =" . intval($languageIdValue) . "   AND lx.show_it =0   
                    LEFT JOIN sys_fixed_sales_costs ax ON (ax.act_parent_id = a.act_parent_id OR ax.language_parent_id = a.act_parent_id) AND ax.deleted =0 AND ax.active = 0 AND ax.language_id = lx.id
                    INNER JOIN info_users u ON u.id = a.op_user_id 
                    /*----*/   

		   LEFT JOIN sys_vehicle_groups vg ON vg.act_parent_id = a.vehicle_gruop_id  AND vg.show_it =0 
		   LEFT JOIN sys_vehicle_gt_models vge ON vge.act_parent_id = a.vehicle_second_group_id  AND vge.show_it =0 
		   LEFT JOIN sys_vehicle_group_types vgt ON vgt.act_parent_id = vge.vehicle_group_types_id  AND vgt.show_it =0 

		   LEFT JOIN sys_warranty_matrix wm ON wm.act_parent_id = a.warranty_matrix_id  AND wm.show_it =0 

		   
                    
		    INNER JOIN sys_currency_types ct ON ct.act_parent_id = a.currency_type_id AND ct.show_it = 0 AND ct.language_id= l.id
		    LEFT JOIN sys_currency_types ctx ON (ctx.act_parent_id = ct.act_parent_id OR ctx.language_parent_id= ct.act_parent_id) AND ctx.show_it= 0 AND ctx.language_id =lx.id  
                    /*----*/   
			 
                   /* INNER JOIN sys_specific_definitions sd15 ON sd15.main_group = 15 AND sd15.first_group= a.deleted AND sd15.deleted =0 AND sd15.active =0 AND sd15.language_parent_id =0 */
                    INNER JOIN sys_specific_definitions sd16 ON sd16.main_group = 16 AND sd16.first_group= a.active AND sd16.deleted = 0 AND sd16.active = 0 AND sd16.language_id =l.id
                    /**/
                  /*  LEFT JOIN sys_specific_definitions sd15x ON sd15x.language_id =lx.id AND (sd15x.id = sd15.id OR sd15x.language_parent_id = sd15.id) AND sd15x.deleted =0 AND sd15x.active =0  */
                    LEFT JOIN sys_specific_definitions sd16x ON sd16x.language_id = lx.id AND (sd16x.id = sd16.id OR sd16x.language_parent_id = sd16.id) AND sd16x.deleted = 0 AND sd16x.active = 0
                    
                    WHERE  
                        " . $addSql . "
                        a.deleted =0 AND
                        a.show_it =0  AND 
                        a.language_parent_id =0  
                       
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
     * @ sys_fixed_sales_costs tablosundan parametre olarak  gelen id kaydını active ve show_it alanlarını 1 yapar. !!
     * @version v 1.0  24.08.2018
     * @param type $params
     * @return array
     * @throws \PDOException
     */
    public function makePassive($params = array()) {
        try {
            $pdo = $this->slimApp->getServiceManager()->get('pgConnectFactory'); 
            $statement = $pdo->prepare(" 
                UPDATE sys_fixed_sales_costs
                SET                         
                    c_date =  timezone('Europe/Istanbul'::text, ('now'::text)::timestamp(0) with time zone) ,                     
                    active = 1 ,
                     deleted = 1 ,
                    show_it =1 
                WHERE id = :id ");
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
     * @ sys_fixed_sales_costs tablosundan parametre olarak  gelen id kaydın active veshow_it  alanını 1 yapar ve 
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
                    INSERT INTO sys_fixed_sales_costs (
                        name,
                        name_eng,
                        abbrevation,
                        vehicle_gruop_id,
                        vehicle_second_group_id,
                        vvalue,
                        currency_type_id,
                        start_date,
                        is_all_vehicle, 
                         warranty_matrix_id,
                        
                        language_id,
                        language_parent_id,
                        active,
                        deleted,
                        op_user_id,
                        act_parent_id,
                        show_it
                        )
                    SELECT
                        name,
                        name_eng,
                        abbrevation,
                        vehicle_gruop_id,
                        vehicle_second_group_id,
                        vvalue,
                        currency_type_id,
                        start_date,
                        is_all_vehicle, 
                        warranty_matrix_id,
                        
                        language_id,
                        language_parent_id, 
                        1 AS active,  
                        1 AS deleted, 
                        " . intval($opUserIdValue) . " AS op_user_id, 
                        act_parent_id,
                        0 AS show_it 
                    FROM sys_fixed_sales_costs 
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
     * @ sys_fixed_sales_costs tablosuna yeni bir kayıt oluşturur.  !! 
     * @version v 1.0  26.08.2018
     * @param type $params
     * @return array
     * @throws \PDOException
     */
    public function insertAct($params = array()) {
        try {
            $pdo = $this->slimApp->getServiceManager()->get('pgConnectFactory');
            $pdo->beginTransaction();
            ////*********/////  1 
            $languageIdValue = 385;
           /* if (isset($params['language_code']) && $params['language_code'] != "") { 
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
            * 
            */
            ////*********///// 1                  
            $errorInfo[0] = "99999";
            $addSQL1 =NULL;
            $addSQL2 =NULL;
            $nameTemp = null;
            $name = null;
            if ((isset($params['Name']) && $params['Name'] != "")) {
                $name = $params['Name'];
            } else {
                throw new \PDOException($errorInfo[0]);
            }
            $nameEng = null;
          /*  if ((isset($params['NameEng']) && $params['NameEng'] != "")) {
                $nameEng = $params['NameEng'];
            } else {
                  if ($languageIdValue != 385 )   {  throw new \PDOException($errorInfo[0]);}
            }
           * 
           */
            $currencyTypeId = -1111;
            if ((isset($params['CurrencyTypeId']) && $params['CurrencyTypeId'] != "")) {
                $currencyTypeId = intval($params['CurrencyTypeId']);
            } else {
                throw new \PDOException($errorInfo[0]);
            }
            $value = -1111;
            if ((isset($params['Value']) && $params['Value'] != "")) {
                $value = intval($params['Value']);
            } else {
                throw new \PDOException($errorInfo[0]);
            }
            $VehicleGruopId = -1111;
            if ((isset($params['VehicleGruopId']) && $params['VehicleGruopId'] != "")) {
                $VehicleGruopId = intval($params['VehicleGruopId']);
            }    
            $VehicleSecondGroupId = -1111;
            if ((isset($params['VehicleSecondGroupId']) && $params['VehicleSecondGroupId'] != "")) {
                $VehicleSecondGroupId = intval($params['VehicleSecondGroupId']);
            }    
             $StartDate= null;
            if ((isset($params['StartDate']) && $params['StartDate'] != "")) {
                $StartDate = $params['StartDate'];
                $addSQL1 .= 'start_date,  ';
                $addSQL2 .= "'". $StartDate."',";
            } 
             $IsAllVehicle = -1111;
            if ((isset($params['IsAllVehicle']) && $params['IsAllVehicle'] != "")) {
                $IsAllVehicle = intval($params['IsAllVehicle']);
            }   
            $WarrantyMatrixId = -1111;
            if ((isset($params['WarrantyMatrixId']) && $params['WarrantyMatrixId'] != "")) {
                $WarrantyMatrixId = intval($params['WarrantyMatrixId']);
            }    
                ////*********///// 2    
            if ($languageIdValue != 385 )  
                {$nameTemp = $name;  }   else  {$nameEng = $name; } 
                ////*********///// 2          

            $opUserId = InfoUsers::getUserId(array('pk' => $params['pk']));
            if (\Utill\Dal\Helper::haveRecord($opUserId)) {
                $opUserIdValue = $opUserId ['resultSet'][0]['user_id'];

                $kontrol = $this->haveRecords(
                        array(
                            'name' => $name, 
                            'currency_type_id' => $currencyTypeId,
                            'language_id' => $languageIdValue 
                        
                ));
                if (!\Utill\Dal\Helper::haveRecord($kontrol)) {
                    $sql = "
                    INSERT INTO sys_fixed_sales_costs(
                            name,
                            name_eng, 
                            vehicle_gruop_id,
                            vehicle_second_group_id,
                            vvalue,
                            currency_type_id,
                            ".$addSQL1." 
                            is_all_vehicle, 
                            warranty_matrix_id,

                            op_user_id,
                            act_parent_id  
                            )
                    VALUES (
                            '" . $name . "',
                            '" . $nameEng . "',
                            " . intval($VehicleGruopId) . ", 
                            " . intval($VehicleSecondGroupId) . ", 
                            " . intval($value) . ", 
                            " . intval($currencyTypeId) . ", 
                            ".$addSQL2."
                            " . intval($IsAllVehicle) . ",  
                            " . intval($WarrantyMatrixId) . ",  

                            " . intval($opUserIdValue) . ",
                           (SELECT last_value FROM sys_fixed_sales_costs_id_seq)
                                                 )   ";
                    $statement = $pdo->prepare($sql);
                     //  echo debugPDO($sql, $params);
                    $result = $statement->execute();
                    $errorInfo = $statement->errorInfo();
                    if ($errorInfo[0] != "00000" && $errorInfo[1] != NULL && $errorInfo[2] != NULL)
                        throw new \PDOException($errorInfo[0]);
                    $insertID = $pdo->lastInsertId('sys_fixed_sales_costs_id_seq');

                           
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
     * @ sys_fixed_sales_costs tablosuna aktif olan diller için ,tek bir kaydın tabloda olmayan diğer dillerdeki kayıtlarını oluşturur   !!
     * @version v 1.0  26.08.2018
     * @todo Su an için aktif değil SQl in değişmesi lazım. 
     * @return array
     * @throws \PDOException
     */
    public function insertLanguageTemplate($params = array()) {
        try {
            $pdo = $this->slimApp->getServiceManager()->get('pgConnectFactory');
            //  $pdo->beginTransaction();
            /**
             * table names and column names will be changed for specific use
             */
            $statement = $pdo->prepare(" 
                
                INSERT INTO sys_fixed_sales_costs(
                    name,
                    name_eng, 
                    vvalue,
                    currency_type_id,

                    language_id,
                    language_parent_id, 
                    act_parent_id,
                    op_user_id)
                    
                  SELECT    
                    name,
                    name_eng, 
                    vvalue,
                    currency_type_id,
                     
                    language_id,
                    language_parent_id, 
                     ROW_NUMBER () OVER (ORDER BY act_parent_id)+ act_parent_id act_parent_id, 
                    op_user_id
                FROM ( 
                    SELECT  
                        c.vvalue,
                        c.currency_type_id,
                        
			case when l.id = 385 then c.name_eng   
			    when " . intval($params['language_id']) . " = l.id then '" .($params['nameTemp']). "'  
                            else '' end as name,  
                        COALESCE(NULLIF(c.name_eng,''), c.name) AS name_eng, 
                        l.id as language_id,  
			case l.id when 385 then 0 else c.id  end as language_parent_id ,   
			case l.id when 385 then c.id else (SELECT last_value FROM sys_fixed_sales_costs_id_seq) end as act_parent_id,  
                        c.op_user_id
                    FROM sys_fixed_sales_costs c
                    LEFT JOIN sys_language l ON l.deleted =0 AND l.active =0 
                    WHERE c.id = " . intval($params['id']) . "  
                    ) AS xy   
                    WHERE xy.language_id NOT IN 
                        (SELECT DISTINCT language_id 
                        FROM sys_fixed_sales_costs cx 
                        WHERE 
                            (/* cx.language_parent_id = " . intval($params['id']) . " OR  */
                            cx.id = " . intval($params['id']) . "  ) /* AND  
                            cx.deleted =0 AND 
                            cx.active =0 */ )
                    ");

            $result = $statement->execute();
            $insertID = $pdo->lastInsertId('info_users_addresses_id_seq');
            $errorInfo = $statement->errorInfo();
            if ($errorInfo[0] != "00000" && $errorInfo[1] != NULL && $errorInfo[2] != NULL)
                throw new \PDOException($errorInfo[0]);
            //   $pdo->commit();

            return array("found" => true, "errorInfo" => $errorInfo, "lastInsertId" => $insertID);
        } catch (\PDOException $e /* Exception $e */) {
            //  $pdo->rollback();
            return array("found" => false, "errorInfo" => $e->getMessage());
        }
    }
         
    /**
     * @author Okan CIRAN
     * sys_fixed_sales_costs tablosuna parametre olarak gelen id deki kaydın bilgilerini günceller   !!
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
            $addSQL1 =NULL;
            $addSQL2 =NULL;
            $languageIdValue = 385;
            $Id = -1111;
            if ((isset($params['Id']) && $params['Id'] != "")) {
                $Id = intval($params['Id']);
            } else {    
                throw new \PDOException($errorInfo[0]);
            }            
            $name = null;
            if ((isset($params['Name']) && $params['Name'] != "")) {
                $name = $params['Name'];
            } else {
                throw new \PDOException($errorInfo[0]);
            }
            $nameEng = null;
          /*  if ((isset($params['NameEng']) && $params['NameEng'] != "")) {
                $nameEng = $params['NameEng'];
            } else {
                  if ($languageIdValue != 385 )   {  throw new \PDOException($errorInfo[0]);}
            }
           * 
           */
            $currencyTypeId = -1111;
            if ((isset($params['CurrencyTypeId']) && $params['CurrencyTypeId'] != "")) {
                $currencyTypeId = intval($params['CurrencyTypeId']);
            } else {
                throw new \PDOException($errorInfo[0]);
            }
            $value = -1111;
            if ((isset($params['Value']) && $params['Value'] != "")) {
                $value = intval($params['Value']);
            } else {
                throw new \PDOException($errorInfo[0]);
            }
            $VehicleGruopId = -1111;
            if ((isset($params['VehicleGruopId']) && $params['VehicleGruopId'] != "")) {
                $VehicleGruopId = intval($params['VehicleGruopId']);
            }    
            $VehicleSecondGroupId = -1111;
            if ((isset($params['VehicleSecondGroupId']) && $params['VehicleSecondGroupId'] != "")) {
                $VehicleSecondGroupId = intval($params['VehicleSecondGroupId']);
            }    
             $StartDate= null;
            if ((isset($params['StartDate']) && $params['StartDate'] != "")) {
                $StartDate = $params['StartDate'];
                $addSQL1 .= 'start_date,  ';
                $addSQL2 .= "'". $StartDate."',";
            } 
             $IsAllVehicle = -1111;
            if ((isset($params['IsAllVehicle']) && $params['IsAllVehicle'] != "")) {
                $IsAllVehicle = intval($params['IsAllVehicle']);
            }   
            $WarrantyMatrixId = -1111;
            if ((isset($params['WarrantyMatrixId']) && $params['WarrantyMatrixId'] != "")) {
                $WarrantyMatrixId = intval($params['WarrantyMatrixId']);
            }    
            
            $opUserIdParams = array('pk' => $params['pk'],);
            $opUserIdArray = $this->slimApp->getBLLManager()->get('opUserIdBLL');
            $opUserId = $opUserIdArray->getUserId($opUserIdParams);
            if (\Utill\Dal\Helper::haveRecord($opUserId)) {
                $opUserIdValue = $opUserId ['resultSet'][0]['user_id'];
                $opUserRoleIdValue = $opUserId ['resultSet'][0]['role_id'];

                $kontrol = $this->haveRecords(
                        array(
                            'name' => $name, 
                            'currency_type_id' => $currencyTypeId,
                            'language_id' => $languageIdValue ,
                            'id' => $Id
                ));
                if (!\Utill\Dal\Helper::haveRecord($kontrol)) {

                    $this->makePassive(array('id' => $params['Id']));

               $sql = "
                INSERT INTO sys_acc_body_deff (  
                         name,
                        name_eng, 
                        vehicle_gruop_id,
                        vehicle_second_group_id,
                        vvalue,
                        currency_type_id,
                        ".$addSQL1." 
                        is_all_vehicle, 
                        warranty_matrix_id,

                        
                        priority,
                        language_id,
                        language_parent_id,
                        op_user_id,
                        act_parent_id 
                        )  
                SELECT  
                    '" . $name . "',
                    '" . $nameEng . "',
                    " . intval($VehicleGruopId) . ", 
                    " . intval($VehicleSecondGroupId) . ", 
                    " . intval($value) . ", 
                    " . intval($currencyTypeId) . ", 
                    ".$addSQL2."
                    " . intval($IsAllVehicle) . ",  
                    " . intval($WarrantyMatrixId) . ",  

                     
                    priority,
                    language_id,
                    language_parent_id ,
                    " . intval($opUserIdValue) . " AS op_user_id,  
                    act_parent_id
                FROM sys_acc_body_deff 
                WHERE 
                    language_id = 385 AND id  =" . intval($Id) . "                  
                                                ";
                    $statementInsert = $pdo->prepare($sql);
                  //    echo debugPDO($sql, $params);
                    $result = $statementInsert->execute(); 
                    $errorInfo = $statementInsert->errorInfo();
                    if ($errorInfo[0] != "00000" && $errorInfo[1] != NULL && $errorInfo[2] != NULL)
                        throw new \PDOException($errorInfo[0]);

                     
                    $affectedRows = $statementInsert->rowCount();
                    if ($affectedRows> 0 ){
                    $insertID = $pdo->lastInsertId('sys_acc_body_deff_id_seq');}
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
