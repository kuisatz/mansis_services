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
class SysAccBodySupp extends \DAL\DalSlim {

    /**
     * @author Okan CIRAN
     * @ sys_acc_body_supp tablosundan parametre olarak  gelen id kaydını siler. !!
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
                UPDATE sys_acc_body_supp
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
     * @ sys_acc_body_supp tablosundaki tüm kayıtları getirir.  !!
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
                FROM sys_acc_body_supp a
                INNER JOIN sys_language l ON l.id = a.language_id AND l.deleted =0 AND l.active = 0 
                LEFT JOIN sys_language lx ON lx.id = ".intval($languageIdValue)." AND lx.deleted =0 AND lx.active =0
                INNER JOIN sys_specific_definitions sd15 ON sd15.main_group = 15 AND sd15.first_group= a.deleted AND sd15.language_id = a.language_id AND sd15.deleted = 0 
                INNER JOIN sys_specific_definitions sd16 ON sd16.main_group = 16 AND sd16.first_group= a.active AND sd16.language_id = a.language_id AND sd16.deleted = 0
                INNER JOIN info_users u ON u.id = a.op_user_id    
                LEFT JOIN sys_specific_definitions sd15x ON (sd15x.id = sd15.id OR sd15x.language_parent_id = sd15.id) AND sd15x.language_id =lx.id  AND sd15x.deleted =0 AND sd15x.active =0 
                LEFT JOIN sys_specific_definitions sd16x ON (sd16x.id = sd16.id OR sd16x.language_parent_id = sd16.id)AND sd16x.language_id = lx.id  AND sd16x.deleted = 0 AND sd16x.active = 0
                LEFT JOIN sys_acc_body_supp ax ON (ax.id = a.id OR ax.language_parent_id = a.id) AND ax.deleted =0 AND ax.active =0 AND lx.id = ax.language_id
                
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
     * @ sys_acc_body_supp tablosuna yeni bir kayıt oluşturur.  !!
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
                INSERT INTO sys_acc_body_supp(
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
                    $insertID = $pdo->lastInsertId('sys_acc_body_supp_id_seq');
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
     * @ sys_acc_body_supp tablosunda property_name daha önce kaydedilmiş mi ?  
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
            FROM sys_acc_body_supp  a                          
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
     * sys_acc_body_supp tablosuna parametre olarak gelen id deki kaydın bilgilerini günceller   !!
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
                    UPDATE sys_acc_body_supp
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
     * @ Gridi doldurmak için sys_acc_body_supp tablosundan kayıtları döndürür !!
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
                FROM sys_acc_body_supp a
                INNER JOIN sys_language l ON l.id = a.language_id AND l.deleted =0 AND l.active = 0 
                LEFT JOIN sys_language lx ON lx.id = ".intval($languageIdValue)." AND lx.deleted =0 AND lx.active =0
                INNER JOIN sys_specific_definitions sd15 ON sd15.main_group = 15 AND sd15.first_group= a.deleted AND sd15.language_id = a.language_id AND sd15.deleted = 0 
                INNER JOIN sys_specific_definitions sd16 ON sd16.main_group = 16 AND sd16.first_group= a.active AND sd16.language_id = a.language_id AND sd16.deleted = 0
                INNER JOIN info_users u ON u.id = a.op_user_id    
                LEFT JOIN sys_specific_definitions sd15x ON (sd15x.id = sd15.id OR sd15x.language_parent_id = sd15.id) AND sd15x.language_id =lx.id  AND sd15x.deleted =0 AND sd15x.active =0 
                LEFT JOIN sys_specific_definitions sd16x ON (sd16x.id = sd16.id OR sd16x.language_parent_id = sd16.id)AND sd16x.language_id = lx.id  AND sd16x.deleted = 0 AND sd16x.active = 0
                LEFT JOIN sys_acc_body_supp ax ON (ax.id = a.id OR ax.language_parent_id = a.id) AND ax.deleted =0 AND ax.active =0 AND lx.id = ax.language_id
                
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
     * @ Gridi doldurmak için sys_acc_body_supp tablosundan çekilen kayıtlarının kaç tane olduğunu döndürür   !!
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
                FROM sys_acc_body_supp a
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
     * @ sys_acc_body_supp tablosundan parametre olarak  gelen id kaydın aktifliğini
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
                UPDATE sys_acc_body_supp
                SET active = (  SELECT   
                                CASE active
                                    WHEN 0 THEN 1
                                    ELSE 0
                                END activex
                                FROM sys_acc_body_supp
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
     * @ body aksesuar tanımlarını supplier bilgilerini grid formatında döndürür !! ana tablo  sys_acc_body_supp 
     * @version v 1.0  15.08.2018
     * @param array | null $args
     * @return array
     * @throws \PDOException  
     */  
     public function fillAccBodySuppGridx($params = array()) {
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
                $sort = "  a.id  ";
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
                            case 'body_name':
                                $sorguExpression = ' ILIKE \'%' . $std['value'] . '%\' ';
                                $sorguStr.=" AND COALESCE(NULLIF(bdx.name, ''), bd.name_eng)" . $sorguExpression . ' ';
                              
                                break;
                            case 'supplier_name':
                                $sorguExpression = ' ILIKE \'%' . $std['value'] . '%\'  ';
                                $sorguStr.=" AND COALESCE(NULLIF(sx.name, ''), s.name_eng)" . $sorguExpression . ' ';

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
            $accBodyDeffId =0 ;
            if (isset($params['AccBodyDeffID']) && $params['AccBodyDeffID'] != "") {
                $accBodyDeffId = $params['AccBodyDeffID'];
                $addSql .= "  a.acc_body_deff_id  = " . intval($accBodyDeffId). "  AND  " ; 
            }  
            $supplierID =0 ;
            if (isset($params['SupplierID']) && $params['SupplierID'] != "") {
                $supplierID = $params['SupplierID'];
                $addSql .= "  a.supplier_id  = " . intval($supplierID). "  AND  " ; 
            }  

                $sql = "
                    SELECT
                        a.id, 
                        a.act_parent_id as apid,  
                        a.acc_body_deff_id ,
			COALESCE(NULLIF(bdx.name, ''), bd.name_eng) AS body_name,  
			a.supplier_id ,
			COALESCE(NULLIF(sx.name, ''), s.name_eng) AS supplier_name,  
                        a.cost, 
                        a.active,
                        COALESCE(NULLIF(sd16x.description, ''), sd16.description_eng) AS state_active,
                       /* a.deleted,
                        COALESCE(NULLIF(sd15x.description, ''), sd15.description_eng) AS state_deleted,*/
                        a.op_user_id,
                        u.username AS op_user_name,  
                        a.s_date date_saved,
                        a.c_date date_modified,
                       /* bd.priority,*/
                        COALESCE(NULLIF(lx.id, NULL), 385) AS language_id, 
                        lx.language_main_code language_code, 
                        COALESCE(NULLIF(lx.language, ''), 'en') AS language_name
                    FROM sys_acc_body_supp a                    
                    INNER JOIN sys_language l ON l.id = 385 AND l.show_it =0
                    LEFT JOIN sys_language lx ON lx.id =".intval($languageIdValue)." AND lx.show_it =0   
                  
                    INNER JOIN info_users u ON u.id = a.op_user_id 
                    /*----*/
		 --   INNER JOIN sys_acc_body_supp bs ON bs.act_parent_id = a.acc_body_supp_id AND bs.show_it = 0   
                    INNER JOIN sys_acc_body_supp bd ON bd.act_parent_id = a.accessory_body_id AND bd.show_it = 0 AND bd.language_parent_id =0 AND bd.language_id= l.id
		    LEFT JOIN sys_acc_body_supp bdx ON (bdx.act_parent_id = bd.act_parent_id OR bdx.language_parent_id= bd.act_parent_id) AND bdx.show_it = 0 AND bdx.language_id =lx.id  
                    
		    INNER JOIN sys_supplier s ON s.act_parent_id = a.supplier_id AND s.deleted = 0 AND s.active = 0 AND s.language_parent_id =0 AND s.language_id= l.id
		    LEFT JOIN sys_supplier sx ON (sx.act_parent_id = bd.act_parent_id OR sx.language_parent_id= bd.act_parent_id) AND sx.show_it = 0 AND sx.language_id =lx.id  
   
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
     * @ body aksesuar tanımlarını grid formatında gösterilirken kaç kayıt olduğunu döndürür !! ana tablo  sys_acc_body_supp 
     * @version v 1.0  15.08.2018
     * @param array | null $args
     * @return array
     * @throws \PDOException  
     */  
     public function fillAccBodyDeffGridxRtl($params = array()) {
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
                            case 'body_type_name':
                                $sorguExpression = ' ILIKE \'%' . $std['value'] . '%\'  ';
                                $sorguStr.=" AND COALESCE(NULLIF(bx.name, ''), b.name_eng)" . $sorguExpression . ' ';

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
           $accBodyDeffId =0 ;
            if (isset($params['AccBodyDeffID']) && $params['AccBodyDeffID'] != "") {
                $accBodyDeffId = $params['AccBodyDeffID'];
                $addSql .= "  a.acc_body_deff_id  = " . intval($accBodyDeffId). "  AND  " ; 
            }  
            $supplierID =0 ;
            if (isset($params['SupplierID']) && $params['SupplierID'] != "") {
                $supplierID = $params['SupplierID'];
                $addSql .= "  a.supplier_id  = " . intval($supplierID). "  AND  " ; 
            }  

                $sql = "
                   SELECT COUNT(asdx.id) count FROM ( 
                        SELECT
                            a.id,   
                            a.act_parent_id,   
                            a.acc_body_deff_id ,
                            COALESCE(NULLIF(bdx.name, ''), bd.name_eng) AS body_name,  
                            a.supplier_id ,
                            COALESCE(NULLIF(sx.name, ''), s.name_eng) AS supplier_name,  
                            a.cost, 
                            a.active,
                            COALESCE(NULLIF(sd16x.description, ''), sd16.description_eng) AS state_active,
                           /* a.deleted,
                            COALESCE(NULLIF(sd15x.description, ''), sd15.description_eng) AS state_deleted,*/
                            a.op_user_id,
                            u.username AS op_user_name,  
                            a.s_date date_saved,
                            a.c_date date_modified,
                           /* bd.priority,*/
                            COALESCE(NULLIF(lx.id, NULL), 385) AS language_id, 
                            lx.language_main_code language_code, 
                            COALESCE(NULLIF(lx.language, ''), 'en') AS language_name
                        FROM sys_acc_body_supp a                    
                        INNER JOIN sys_language l ON l.id = 385 AND l.deleted =0 AND l.active =0
                        LEFT JOIN sys_language lx ON lx.id =".intval($languageIdValue)." AND lx.show_it =0   

                        INNER JOIN info_users u ON u.id = a.op_user_id 
                        /*----*/
                     --   INNER JOIN sys_acc_body_supp bs ON bs.act_parent_id = a.acc_body_supp_id AND bs.show_it = 0   
                        INNER JOIN sys_acc_body_supp bd ON bd.act_parent_id = a.accessory_body_id AND bd.show_it = 0 AND bd.language_parent_id =0 AND bd.language_id= l.id
                        LEFT JOIN sys_acc_body_supp bdx ON (bdx.act_parent_id = bd.act_parent_id OR bdx.language_parent_id= bd.act_parent_id) AND bdx.show_it = 0 AND bdx.language_id =lx.id  

                        INNER JOIN sys_supplier s ON s.act_parent_id = a.supplier_id AND s.show_it = 0 AND s.language_parent_id =0 AND s.language_id= l.id
                        LEFT JOIN sys_supplier sx ON (sx.act_parent_id = bd.act_parent_id OR sx.language_parent_id= bd.act_parent_id) AND sx.show_it = 0 AND sx.language_id =lx.id  

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
     * @ sys_acc_body_supp tablosundan parametre olarak  gelen id kaydını active ve show_it alanlarını 1 yapar. !!
     * @version v 1.0  24.08.2018
     * @param type $params
     * @return array
     * @throws \PDOException
     */
    public function makePassive($params = array()) {
        try {
            $pdo = $this->slimApp->getServiceManager()->get('pgConnectFactory'); 
            $statement = $pdo->prepare(" 
                UPDATE sys_acc_body_supp
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
     * @ sys_acc_body_supp tablosundan parametre olarak  gelen id kaydın active veshow_it  alanını 1 yapar ve 
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
                    INSERT INTO sys_acc_body_supp (
                        supplier_id,
                        acc_body_deff_id,
                        cost,
                         
                        active,
                        deleted,
                        op_user_id,
                        act_parent_id,
                        show_it
                        )
                    SELECT
                        supplier_id,
                        acc_body_deff_id,
                        cost,
                         
                        1 AS active,  
                        1 AS deleted, 
                        " . intval($opUserIdValue) . " AS op_user_id, 
                        act_parent_id,
                        0 AS show_it 
                    FROM sys_acc_body_supp 
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
     * @ sys_acc_body_supp tablosuna yeni bir kayıt oluşturur.  !!
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
            $supplierId = -1111;
            if ((isset($params['SupplierId']) && $params['SupplierId'] != "")) {
                $supplierId = intval($params['SupplierId']);
            } else {
                throw new \PDOException($errorInfo[0]);
            }
            $accBodyDeffId = -1111;
            if ((isset($params['AccBodyDeffId']) && $params['AccBodyDeffId'] != "")) {
                $accBodyDeffId = intval($params['AccBodyDeffId']);
            } else {
                throw new \PDOException($errorInfo[0]);
            }
            $cost = -1111;
            if ((isset($params['Cost']) && $params['Cost'] != "")) {
                $cost = intval($params['Cost']);
            } else {
                throw new \PDOException($errorInfo[0]);
            }
                            
            $opUserId = InfoUsers::getUserId(array('pk' => $params['pk']));
            if (\Utill\Dal\Helper::haveRecord($opUserId)) {
                $opUserIdValue = $opUserId ['resultSet'][0]['user_id'];

                $kontrol = $this->haveRecords(
                        array(
                            'supplier_id' => $supplierId,
                            'acc_body_deff_id' => $accBodyDeffId,
                            'cost' => $cost  
                ));
                if (!\Utill\Dal\Helper::haveRecord($kontrol)) {
                    $sql = "
                    INSERT INTO sys_acc_body_supp(
                            supplier_id,
                            acc_body_deff_id,
                            cost,

                            op_user_id,
                            act_parent_id  
                            )
                    VALUES (
                            " . intval($supplierId) . ",
                            " . intval($accBodyDeffId) . ",
                            " . floatval($cost) . ",

                            " . intval($opUserIdValue) . ",
                           (SELECT last_value FROM sys_acc_body_supp_id_seq)
                                                 )   ";
                    $statement = $pdo->prepare($sql);
                    //   echo debugPDO($sql, $params);
                    $result = $statement->execute();
                    $errorInfo = $statement->errorInfo();
                    if ($errorInfo[0] != "00000" && $errorInfo[1] != NULL && $errorInfo[2] != NULL)
                        throw new \PDOException($errorInfo[0]);
                    $insertID = $pdo->lastInsertId('sys_acc_body_supp_id_seq');

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
     * sys_acc_body_supp tablosuna parametre olarak gelen id deki kaydın bilgilerini günceller   !!
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
            $supplierId = -1111;
            if ((isset($params['SupplierId']) && $params['SupplierId'] != "")) {
                $supplierId = intval($params['SupplierId']);
            } else {
                throw new \PDOException($errorInfo[0]);
            }
            $accBodyDeffId = -1111;
            if ((isset($params['AccBodyDeffId']) && $params['AccBodyDeffId'] != "")) {
                $accBodyDeffId = intval($params['AccBodyDeffId']);
            } else {
                throw new \PDOException($errorInfo[0]);
            }
            $cost = -1111;
            if ((isset($params['Cost']) && $params['Cost'] != "")) {
                $cost = intval($params['Cost']);
            } else {
                throw new \PDOException($errorInfo[0]);
            }
            $Id = -1111;
            if ((isset($params['Id']) && $params['Id'] != "")) {
                $Id = intval($params['Id']);
            }else {
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
                            'supplier_id' => $supplierId,
                            'acc_body_deff_id' => $accBodyDeffId,
                            'cost' => $cost  ,
                            'id' => $Id  ,
                            
                ));
                if (!\Utill\Dal\Helper::haveRecord($kontrol)) {

                    $this->makePassive(array('id' => $params['id']));
                            
                    $statementInsert = $pdo->prepare("
                INSERT INTO sys_acc_body_supp (  
                        supplier_id,
                        acc_body_deff_id,
                        cost,
                        
                        priority,
                        language_id,
                        language_parent_id,
                        op_user_id,
                        act_parent_id 
                        )  
                SELECT  
                    " . intval($supplierId) . ",
                    " . intval($accBodyDeffId) . ",
                    " . floatval($cost) . ",
                     
                    priority,
                    language_id,
                    language_parent_id ,
                    " . intval($opUserIdValue) . " AS op_user_id,  
                    act_parent_id
                FROM sys_acc_body_supp 
                WHERE id  =" . intval($Id) . "                  
                                                ");
                    $result = $statementInsert->execute();
                    $insertID = $pdo->lastInsertId('sys_acc_body_supp_id_seq');
                    $affectedRows = $statementInsert->rowCount();
                    $errorInfo = $statementInsert->errorInfo();
                    if ($errorInfo[0] != "00000" && $errorInfo[1] != NULL && $errorInfo[2] != NULL)
                        throw new \PDOException($errorInfo[0]);

                    $pdo->commit();
                    return array("found" => true, "errorInfo" => $errorInfo, "affectedRowsCount" => $affectedRows);
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
