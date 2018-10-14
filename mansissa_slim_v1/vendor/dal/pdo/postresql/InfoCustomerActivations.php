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
class InfoCustomerActivations extends \DAL\DalSlim {

    /**
     * @author Okan CIRAN    
     * @ info_customer_activations tablosundan parametre olarak  gelen id kaydını siler. !!
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
                UPDATE info_customer_activations
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
     * @ info_customer_activations tablosundaki tüm kayıtları getirir.  !!
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
                FROM info_customer_activations a
                INNER JOIN sys_language l ON l.id = a.language_id AND l.deleted =0 AND l.active = 0 
                LEFT JOIN sys_language lx ON lx.id = ".intval($languageIdValue)." AND lx.deleted =0 AND lx.active =0
                INNER JOIN sys_specific_definitions sd15 ON sd15.main_group = 15 AND sd15.first_group= a.deleted AND sd15.language_id = a.language_id AND sd15.deleted = 0 
                INNER JOIN sys_specific_definitions sd16 ON sd16.main_group = 16 AND sd16.first_group= a.active AND sd16.language_id = a.language_id AND sd16.deleted = 0
                INNER JOIN info_users u ON u.id = a.op_user_id    
                LEFT JOIN sys_specific_definitions sd15x ON (sd15x.id = sd15.id OR sd15x.language_parent_id = sd15.id) AND sd15x.language_id =lx.id  AND sd15x.deleted =0 AND sd15x.active =0 
                LEFT JOIN sys_specific_definitions sd16x ON (sd16x.id = sd16.id OR sd16x.language_parent_id = sd16.id)AND sd16x.language_id = lx.id  AND sd16x.deleted = 0 AND sd16x.active = 0
                LEFT JOIN info_customer_activations ax ON (ax.id = a.id OR ax.language_parent_id = a.id) AND ax.deleted =0 AND ax.active =0 AND lx.id = ax.language_id
                
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
     * @ info_customer_activations tablosuna yeni bir kayıt oluşturur.  !!
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
                INSERT INTO info_customer_activations(
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
                    $insertID = $pdo->lastInsertId('info_customer_activations_id_seq');
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
     * @ info_customer_activations tablosunda property_name daha önce kaydedilmiş mi ?  
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
                '' as name ,
                '' AS value, 
               true AS control,
                CONCAT( ' daha önce kayıt edilmiş. Lütfen Kontrol Ediniz !!!' ) AS message
            FROM info_customer_activations  a                          
            WHERE 
                a.customer_id = " . intval($params['customer_id']) . " AND 
                a.act_date = '" .  ($params['act_date']) . "' AND 
                a.contact_person_id = " . intval($params['contact_person_id']) . "   
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
     * info_customer_activations tablosuna parametre olarak gelen id deki kaydın bilgilerini günceller   !!
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
                    UPDATE info_customer_activations
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
     * @ Gridi doldurmak için info_customer_activations tablosundan kayıtları döndürür !!
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
                FROM info_customer_activations a
                INNER JOIN sys_language l ON l.id = a.language_id AND l.deleted =0 AND l.active = 0 
                LEFT JOIN sys_language lx ON lx.id = ".intval($languageIdValue)." AND lx.deleted =0 AND lx.active =0
                INNER JOIN sys_specific_definitions sd15 ON sd15.main_group = 15 AND sd15.first_group= a.deleted AND sd15.language_id = a.language_id AND sd15.deleted = 0 
                INNER JOIN sys_specific_definitions sd16 ON sd16.main_group = 16 AND sd16.first_group= a.active AND sd16.language_id = a.language_id AND sd16.deleted = 0
                INNER JOIN info_users u ON u.id = a.op_user_id    
                LEFT JOIN sys_specific_definitions sd15x ON (sd15x.id = sd15.id OR sd15x.language_parent_id = sd15.id) AND sd15x.language_id =lx.id  AND sd15x.deleted =0 AND sd15x.active =0 
                LEFT JOIN sys_specific_definitions sd16x ON (sd16x.id = sd16.id OR sd16x.language_parent_id = sd16.id)AND sd16x.language_id = lx.id  AND sd16x.deleted = 0 AND sd16x.active = 0
                LEFT JOIN info_customer_activations ax ON (ax.id = a.id OR ax.language_parent_id = a.id) AND ax.deleted =0 AND ax.active =0 AND lx.id = ax.language_id
                
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
     * @ Gridi doldurmak için info_customer_activations tablosundan çekilen kayıtlarının kaç tane olduğunu döndürür   !!
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
                FROM info_customer_activations a
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
     * @ info_customer_activations tablosundan parametre olarak  gelen id kaydın aktifliğini
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
                UPDATE info_customer_activations
                SET active = (  SELECT   
                                CASE active
                                    WHEN 0 THEN 1
                                    ELSE 0
                                END activex
                                FROM info_customer_activations
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
     * @ back office tarafından onaylanmış müşteri tanımları dropdown ya da tree ye doldurmak için info_customer_activations tablosundan kayıtları döndürür !!
     * @version v 1.0  11.08.2018
     * @param array | null $args
     * @return array
     * @throws \PDOException 
     */
    public function  customeractivAtionsDdList($params = array()) {
        try {
            $pdo = $this->slimApp->getServiceManager()->get('pgConnectFactory');         
            $addSQL = " 1=2  AND";
            $CustomerId=-1111 ;               
            if ((isset($params['CustomerId']) && $params['CustomerId'] != "")) {
                $CustomerId = intval($params['CustomerId']);
                $addSQL = " a.customer_id = ". intval($CustomerId) . " AND ";
            }                 
            $statement = $pdo->prepare("    
              SELECT      
                    a.act_parent_id AS id, 	
                    concat(b.registration_name ,' - ' ,  cast(act_date as character varying(20) ) )   AS name,  
                    b.embrace_customer_no AS name_eng,
                    0 as parent_id,
                    a.active,
                    0 AS state_type   
                FROM info_customer_activations a    
                inner join info_customer b on b.act_parent_id = a.customer_id AND b.show_it =0 
                WHERE   
                    ".$addSQL."
                    a.deleted = 0 AND
                    a.active =0  
                ORDER BY  a.id   
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
     * @   musteri aktivasyon tanımlarını grid formatında döndürür !! ana tablo  info_customer_activations 
     * @version v 1.0  20.08.2018
     * @param array | null $args 
     * @return array
     * @throws \PDOException  
     */  
    public function fillCustomeractivationsGridx($params = array()) {
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
                        cs.registration_name, 
                        cs.trading_name, 
			a.cs_activation_type_id,
			cat.name activation_type_name, 
			a.act_date,
			a.cs_statu_types_id,
                        acs.name as statu_types_name,
			a.cs_act_statutype_id,
                        cas.name as cs_act_statutype_name,
			a.project_id,
			a.customer_segment_type_id, 
                        cst.name segment_type_name, 
			a.vehicle_model_id,
			vg.name as vehicle_model_name,
			a.description , 
                        a.manager_description,
 			cp.name,
			cp.surname,
			cp.email,
			cp.cep,
			cp.tel,
			cp.fax, 
                        cs.embrace_customer_no , 
                        cs.tu_emb_customer_no,
			cs.ce_emb_customer_no,
			cs.other_emb_customer_no,
                        cs.www, 
                        cs.vatnumber, 
                        cs.registration_number, 
                        cs.registration_date,  
                        cs.is_bo_confirm,  
                        a.active,
                        COALESCE(NULLIF(sd16x.description, ''), sd16.description_eng) AS state_active, 
                        a.op_user_id,
                        u.username AS op_user_name,  
                        a.s_date date_saved,
                        a.c_date date_modified, 
                        COALESCE(NULLIF(lx.id, NULL), 385) AS language_id, 
                        lx.language_main_code language_code, 
                        COALESCE(NULLIF(lx.language, ''), 'en') AS language_name
                    FROM info_customer_activations a                    
                    INNER JOIN sys_language l ON l.id = 385 AND l.show_it =0
                    LEFT JOIN sys_language lx ON lx.id = " . intval($languageIdValue) . "   AND lx.show_it =0  
                    LEFT JOIN info_customer_activations ax ON (ax.act_parent_id =a.act_parent_id ) AND ax.show_it =0 
               
                    INNER JOIN info_users u ON u.id = a.op_user_id 
                    /*----*/   
		    inner join info_customer cs on cs.act_parent_id = a.customer_id AND cs.show_it =0 
                    
                    LEFT JOIN info_customer_contact_persons cp ON cp.act_parent_id = cs.customer_id AND cp.show_it = 0 
                    left join sys_customer_segment_types cst on cst.act_parent_id = a.customer_segment_type_id and cst.show_it = 0 
                    left join sys_cs_activation_types cat on cat.act_parent_id = a.cs_activation_type_id and cat.show_it = 0 
		    left join sys_vehicle_groups vg on vg.act_parent_id = a.vehicle_model_id and vg.show_it = 0 
                    left join sys_cs_statu_types acs on acs.act_parent_id = a.cs_statu_types_id and acs.show_it = 0 
                    left join sys_cs_act_statutypes  cas on cas.act_parent_id = a.cs_statu_types_id and cas.show_it = 0 
                     
                    /*----*/   
                   /* INNER JOIN sys_specific_definitions sd15 ON sd15.main_group = 15 AND sd15.first_group= a.deleted AND sd15.deleted =0 AND sd15.active =0 AND sd15.language_parent_id =0 */
                    INNER JOIN sys_specific_definitions sd16 ON sd16.main_group = 16 AND sd16.first_group= a.active AND sd16.deleted = 0 AND sd16.active = 0 AND sd16.language_id =l.id
                    /**/
                  /*  LEFT JOIN sys_specific_definitions sd15x ON sd15x.language_id =lx.id AND (sd15x.id = sd15.id OR sd15x.language_parent_id = sd15.id) AND sd15x.deleted =0 AND sd15x.active =0  */
                    LEFT JOIN sys_specific_definitions sd16x ON sd16x.language_id = lx.id AND (sd16x.id = sd16.id OR sd16x.language_parent_id = sd16.id) AND sd16x.deleted = 0 AND sd16x.active = 0
                    
                    WHERE  
                         " . $addSql . "
                        a.deleted =0 AND
                        a.show_it =0   
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
     * @  garanti tanımlarını grid formatında gösterilirken kaç kayıt olduğunu döndürür !! ana tablo info_customer_activations
     * @version v 1.0  20.08.2018
     * @param array | null $args
     * @return array
     * @throws \PDOException  
     */  
    public function fillCustomeractivationsGridxRtl($params = array()) {
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
                            
                            case 'vatnumber':
                                $sorguExpression = ' ILIKE \'%' . $std['value'] . '%\'  ';
                                $sorguStr.=" AND a.vatnumber" . $sorguExpression . ' ';

                                break; 
                            case 'registration_number':
                                $sorguExpression = ' ILIKE \'%' . $std['value'] . '%\'  ';
                                $sorguStr.=" AND a.vatnumber" . $sorguExpression . ' ';

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
                        cs.registration_name, 
                        cs.trading_name, 
			a.cs_activation_type_id,
			cat.name activation_type_name, 
			a.act_date,
			a.cs_statu_types_id,
                        acs.name as statu_types_name,
			a.cs_act_statutype_id,
                        cas.name as cs_act_statutype_name,
			a.project_id,
			a.customer_segment_type_id, 
                        cst.name segment_type_name, 
			a.vehicle_model_id,
			vg.name as vehicle_model_name,
			a.description , 
                        a.manager_description,
 			cp.name,
			cp.surname,
			cp.email,
			cp.cep,
			cp.tel,
			cp.fax, 
                        cs.embrace_customer_no , 
                        cs.tu_emb_customer_no,
			cs.ce_emb_customer_no,
			cs.other_emb_customer_no,
                        cs.www, 
                        cs.vatnumber, 
                        cs.registration_number, 
                        cs.registration_date,  
                        cs.is_bo_confirm,  
                        a.active,
                        COALESCE(NULLIF(sd16x.description, ''), sd16.description_eng) AS state_active, 
                        a.op_user_id,
                        u.username AS op_user_name,  
                        a.s_date date_saved,
                        a.c_date date_modified, 
                        COALESCE(NULLIF(lx.id, NULL), 385) AS language_id, 
                        lx.language_main_code language_code, 
                        COALESCE(NULLIF(lx.language, ''), 'en') AS language_name
                    FROM info_customer_activations a                    
                    INNER JOIN sys_language l ON l.id = 385 AND l.show_it =0
                    LEFT JOIN sys_language lx ON lx.id = " . intval($languageIdValue) . "   AND lx.show_it =0  
                    LEFT JOIN info_customer_activations ax ON (ax.act_parent_id =a.act_parent_id ) AND ax.show_it =0 
               
                    INNER JOIN info_users u ON u.id = a.op_user_id 
                    /*----*/   
		    inner join info_customer cs on cs.act_parent_id = a.customer_id AND cs.show_it =0 
                    
                    LEFT JOIN info_customer_contact_persons cp ON cp.act_parent_id = cs.customer_id AND cp.show_it = 0 
                    left join sys_customer_segment_types cst on cst.act_parent_id = a.customer_segment_type_id and cst.show_it = 0 
                    left join sys_cs_activation_types cat on cat.act_parent_id = a.cs_activation_type_id and cat.show_it = 0 
		    left join sys_vehicle_groups vg on vg.act_parent_id = a.vehicle_model_id and vg.show_it = 0 
                    left join sys_cs_statu_types acs on acs.act_parent_id = a.cs_statu_types_id and acs.show_it = 0 
                    left join sys_cs_act_statutypes  cas on cas.act_parent_id = a.cs_statu_types_id and cas.show_it = 0 
                     
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
     * @ info_customer_activations tablosundan parametre olarak  gelen id kaydını active ve show_it alanlarını 1 yapar. !!
     * @version v 1.0  24.08.2018
     * @param type $params
     * @return array
     * @throws \PDOException
     */
    public function makePassive($params = array()) {
        try {
            $pdo = $this->slimApp->getServiceManager()->get('pgConnectFactory'); 
            $statement = $pdo->prepare(" 
                UPDATE info_customer_activations
                SET                         
                    c_date =  timezone('Europe/Istanbul'::text, ('now'::text)::timestamp(0) with time zone) ,                     
                    active = 1 ,
                    deleted= 1,
                    show_it =1 
               WHERE id = :id  ");
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
     * @ info_customer_activations tablosundan parametre olarak  gelen id kaydın active veshow_it  alanını 1 yapar ve 
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
                    INSERT INTO info_customer_activations ( 
                        customer_id,
                        contact_person_id,
                        cs_activation_type_id,
                        act_date,
                        cs_statu_types_id,
                        cs_act_statutype_id,
                        project_id,
                        customer_segment_type_id,
                        vehicle_model_id,
                        description,
                        manager_description,
  
                        active,
                        deleted,
                        op_user_id,
                        act_parent_id,
                        show_it
                        )
                    SELECT
                        customer_id,
                        contact_person_id,
                        cs_activation_type_id,
                        act_date,
                        cs_statu_types_id,
                        cs_act_statutype_id,
                        project_id,
                        customer_segment_type_id,
                        vehicle_model_id,
                        description,
                        manager_description,
                         
                        1 AS active,  
                        1 AS deleted, 
                        " . intval($opUserIdValue) . " AS op_user_id, 
                        act_parent_id,
                        0 AS show_it 
                    FROM info_customer_activations 
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
     * @ info_customer_activations tablosuna yeni bir kayıt oluşturur.  !! 
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
            $CustomerId=-1111 ;               
            if ((isset($params['CustomerId']) && $params['CustomerId'] != "")) {
                $CustomerId = intval($params['CustomerId']);
            }  else {
                throw new \PDOException($errorInfo[0]);
            }  
            $ContactPersonId=-1111 ;  
            if ((isset($params['ContactPersonId']) && $params['ContactPersonId'] != "")) {
                $ContactPersonId = intval($params['ContactPersonId']);
            }  else {
                throw new \PDOException($errorInfo[0]);
            }
            $CsActivationTypeId=-1111 ;  
            if ((isset($params['CsActivationTypeId']) && $params['CsActivationTypeId'] != "")) {
                $CsActivationTypeId = intval($params['CsActivationTypeId']);
            }  else {
                throw new \PDOException($errorInfo[0]);
            }
            $ActDate= null;
            if ((isset($params['ActDate']) && $params['ActDate'] != "")) {
                $ActDate = $params['ActDate'];
                $addSQL1 = 'act_date,  ';
                $addSQL2 = "'". $ActDate."',";
            }  
            $CsStatuTypesId =-1111 ;  
            if ((isset($params['CsStatuTypesId']) && $params['CsStatuTypesId'] != "")) {
                $CsStatuTypesId = intval($params['CsStatuTypesId']);
            }  else {
                throw new \PDOException($errorInfo[0]);
            }
            $CsActStatutypeId =-1111 ;  
            if ((isset($params['CsActStatutypeId']) && $params['CsActStatutypeId'] != "")) {
                $CsActStatutypeId = intval($params['CsActStatutypeId']);
            }  else {
                throw new \PDOException($errorInfo[0]);
            }
            $ProjectId =-1111 ;  
            if ((isset($params['ProjectId']) && $params['ProjectId'] != "")) {
                $ProjectId = intval($params['ProjectId']);
            }   
            $CustomerSegmentTypeId= 0;
            if ((isset($params['CustomerSegmentTypeId']) && $params['CustomerSegmentTypeId'] != "")) {
                $CustomerSegmentTypeId = intval($params['CustomerSegmentTypeId']);
            }  
            $VehicleModelId = 0;
            if ((isset($params['VehicleModelId']) && $params['VehicleModelId'] != "")) {
                $VehicleModelId = intval($params['VehicleModelId']);
            }            
            $Description = null;
            if ((isset($params['Description']) && $params['Description'] != "")) {
                $Description = $params['Description'];
            }             
            $ManagerDescription= null;
            if ((isset($params['ManagerDescription']) && $params['ManagerDescription'] != "")) {
                $ManagerDescription = $params['ManagerDescription'];
            }              
                            
            $opUserId = InfoUsers::getUserId(array('pk' => $params['pk']));
            if (\Utill\Dal\Helper::haveRecord($opUserId)) {
                $opUserIdValue = $opUserId ['resultSet'][0]['user_id'];

                $kontrol = $this->haveRecords(
                        array(
                            'customer_id' => $CustomerId, 
                            'act_date' => $ActDate,
                            'contact_person_id' => $ContactPersonId, 
                            
                ));
                if (!\Utill\Dal\Helper::haveRecord($kontrol)) {
                    $sql = "
                    INSERT INTO info_customer_activations(
                            customer_id,
                            contact_person_id,
                            cs_activation_type_id,
                            ".$addSQL1."  
                            cs_statu_types_id,
                            cs_act_statutype_id,
                            project_id,
                            customer_segment_type_id,
                            vehicle_model_id,
                            description,
                            manager_description,
 
                            op_user_id,
                            act_parent_id  
                            )
                    VALUES ( 
                            " .  intval($CustomerId). ",
                            " .  intval($ContactPersonId) . ",
                            " .  intval($CsActivationTypeId). ",
                            ".$addSQL2." 
                            " .  intval($CsStatuTypesId). ",
                            " .  intval($CsActStatutypeId) . ",
                            " .  intval($ProjectId) . ",
                            " .  intval($CustomerSegmentTypeId) . ",
                            " .  intval($VehicleModelId) . ",
                            '" . $Description . "',
                            '" . $ManagerDescription . "', 
 
                            " . intval($opUserIdValue) . ",
                           (SELECT last_value FROM info_customer_activations_id_seq)
                                                 )   ";
                    $statement = $pdo->prepare($sql);
               //  echo debugPDO($sql, $params);
                    $result = $statement->execute();
                    $errorInfo = $statement->errorInfo();
                    if ($errorInfo[0] != "00000" && $errorInfo[1] != NULL && $errorInfo[2] != NULL)
                        throw new \PDOException($errorInfo[0]);
                    $insertID = $pdo->lastInsertId('info_customer_activations_id_seq');
                            
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
     * info_customer_activations tablosuna parametre olarak gelen id deki kaydın bilgilerini günceller   !!
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
          $CustomerId=-1111 ;               
            if ((isset($params['CustomerId']) && $params['CustomerId'] != "")) {
                $CustomerId = intval($params['CustomerId']);
            }  else {
                throw new \PDOException($errorInfo[0]);
            }  
            $ContactPersonId=-1111 ;  
            if ((isset($params['ContactPersonId']) && $params['ContactPersonId'] != "")) {
                $ContactPersonId = intval($params['ContactPersonId']);
            }  else {
                throw new \PDOException($errorInfo[0]);
            }
            $CsActivationTypeId=-1111 ;  
            if ((isset($params['CsActivationTypeId']) && $params['CsActivationTypeId'] != "")) {
                $CsActivationTypeId = intval($params['CsActivationTypeId']);
            }  else {
                throw new \PDOException($errorInfo[0]);
            }
            $ActDate= null;
            if ((isset($params['ActDate']) && $params['ActDate'] != "")) {
                $ActDate = $params['ActDate'];
                $addSQL1 = 'act_date,  ';
                $addSQL2 = "'". $ActDate."',";
            }  
            $CsStatuTypesId =-1111 ;  
            if ((isset($params['CsStatuTypesId']) && $params['CsStatuTypesId'] != "")) {
                $CsStatuTypesId = intval($params['CsStatuTypesId']);
            }  else {
                throw new \PDOException($errorInfo[0]);
            }
            $CsActStatutypeId =-1111 ;  
            if ((isset($params['CsActStatutypeId']) && $params['CsActStatutypeId'] != "")) {
                $CsActStatutypeId = intval($params['CsActStatutypeId']);
            }  else {
                throw new \PDOException($errorInfo[0]);
            }
            $ProjectId =-1111 ;  
            if ((isset($params['ProjectId']) && $params['ProjectId'] != "")) {
                $ProjectId = intval($params['ProjectId']);
            }   
            $CustomerSegmentTypeId= 0;
            if ((isset($params['CustomerSegmentTypeId']) && $params['CustomerSegmentTypeId'] != "")) {
                $CustomerSegmentTypeId = intval($params['CustomerSegmentTypeId']);
            }  
            $VehicleModelId = 0;
            if ((isset($params['VehicleModelId']) && $params['VehicleModelId'] != "")) {
                $VehicleModelId = intval($params['VehicleModelId']);
            }            
            $Description = null;
            if ((isset($params['Description']) && $params['Description'] != "")) {
                $Description = $params['Description'];
            }             
            $ManagerDescription= null;
            if ((isset($params['ManagerDescription']) && $params['ManagerDescription'] != "")) {
                $ManagerDescription = $params['ManagerDescription'];
            }              
                            
            $opUserIdParams = array('pk' => $params['pk'],);
            $opUserIdArray = $this->slimApp->getBLLManager()->get('opUserIdBLL');
            $opUserId = $opUserIdArray->getUserId($opUserIdParams);
            if (\Utill\Dal\Helper::haveRecord($opUserId)) {
                $opUserIdValue = $opUserId ['resultSet'][0]['user_id'];
                $opUserRoleIdValue = $opUserId ['resultSet'][0]['role_id'];

                $kontrol = $this->haveRecords(
                        array(
                            'customer_id' => $CustomerId, 
                            'act_date' => $ActDate,
                            'contact_person_id' => $ContactPersonId, 
                            'id' => $Id
                ));
                if (!\Utill\Dal\Helper::haveRecord($kontrol)) {

                    $this->makePassive(array('id' => $params['Id']));

                  $sql = "
                INSERT INTO info_customer_activations (  
                        customer_id,
                        contact_person_id,
                        cs_activation_type_id,
                        ".$addSQL1."  
                        cs_statu_types_id,
                        cs_act_statutype_id,
                        project_id,
                        customer_segment_type_id,
                        vehicle_model_id,
                        description,
                        manager_description,
                        
                        op_user_id,
                        act_parent_id  
                        )  
                SELECT  
                    " .  intval($CustomerId). ",
                    " .  intval($ContactPersonId) . ",
                    " .  intval($CsActivationTypeId). ",
                    ".$addSQL2." 
                    " .  intval($CsStatuTypesId). ",
                    " .  intval($CsActStatutypeId) . ",
                    " .  intval($ProjectId) . ",
                    " .  intval($CustomerSegmentTypeId) . ",
                    " .  intval($VehicleModelId) . ",
                    '" . $Description . "',
                    '" . $ManagerDescription . "', 
                                 
                    " . intval($opUserIdValue) . " AS op_user_id,  
                    act_parent_id
                FROM info_customer_activations 
                WHERE 
                    id  =" . intval($Id) . "                  
                                                " ;
                    $statementInsert = $pdo->prepare($sql);
                // echo debugPDO($sql, $params);
                    $result = $statementInsert->execute();  
                    $errorInfo = $statementInsert->errorInfo();
                    if ($errorInfo[0] != "00000" && $errorInfo[1] != NULL && $errorInfo[2] != NULL)
                        throw new \PDOException($errorInfo[0]);
                            
                     $affectedRows = $statementInsert->rowCount();
                    if ($affectedRows> 0 ){
                    $insertID = $pdo->lastInsertId('info_customer_activations_id_seq');}
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
