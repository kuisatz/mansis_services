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
class InfoProjectWarranties extends \DAL\DalSlim {

    /**
     * @author Okan CIRAN    
     * @ info_project_warranties tablosundan parametre olarak  gelen id kaydını siler. !!
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
                UPDATE info_project_warranties
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
     * @ info_project_warranties tablosundaki tüm kayıtları getirir.  !!
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
                FROM info_project_warranties a
                INNER JOIN sys_language l ON l.id = a.language_id AND l.deleted =0 AND l.active = 0 
                LEFT JOIN sys_language lx ON lx.id = ".intval($languageIdValue)." AND lx.deleted =0 AND lx.active =0
                INNER JOIN sys_specific_definitions sd15 ON sd15.main_group = 15 AND sd15.first_group= a.deleted AND sd15.language_id = a.language_id AND sd15.deleted = 0 
                INNER JOIN sys_specific_definitions sd16 ON sd16.main_group = 16 AND sd16.first_group= a.active AND sd16.language_id = a.language_id AND sd16.deleted = 0
                INNER JOIN info_users u ON u.id = a.op_user_id    
                LEFT JOIN sys_specific_definitions sd15x ON (sd15x.id = sd15.id OR sd15x.language_parent_id = sd15.id) AND sd15x.language_id =lx.id  AND sd15x.deleted =0 AND sd15x.active =0 
                LEFT JOIN sys_specific_definitions sd16x ON (sd16x.id = sd16.id OR sd16x.language_parent_id = sd16.id)AND sd16x.language_id = lx.id  AND sd16x.deleted = 0 AND sd16x.active = 0
                LEFT JOIN info_project_warranties ax ON (ax.id = a.id OR ax.language_parent_id = a.id) AND ax.deleted =0 AND ax.active =0 AND lx.id = ax.language_id
                
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
     * @ info_project_warranties tablosuna yeni bir kayıt oluşturur.  !!
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
                INSERT INTO info_project_warranties(
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
                    $insertID = $pdo->lastInsertId('info_project_warranties_id_seq');
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
     * @ info_project_warranties tablosunda property_name daha önce kaydedilmiş mi ?  
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
                '' AS name  ,
                '' AS value, 
                true AS control,
                CONCAT(  ' daha önce kayıt edilmiş. Lütfen Kontrol Ediniz !!!' ) AS message
            FROM info_project_warranties  a                          
            WHERE 
                a.project_id = " . intval($params['project_id']) . " AND  
                a.vehicles_endgroup_id = " . intval($params['vehicles_endgroup_id']) . " AND   
                a.vehicle_group_id = " . intval($params['vehicle_group_id']) . " AND  
                a.monthsx_id = " . intval($params['monthsx_id']) . " AND  
                a.mileages_id = " . intval($params['mileages_id']) . " AND  
                a.warranty_matrix_id = " . intval($params['warranty_matrix_id']) . " AND  
                a.warranty_type_id = " . intval($params['warranty_type_id']) . "  
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
     * info_project_warranties tablosuna parametre olarak gelen id deki kaydın bilgilerini günceller   !!
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
                    UPDATE info_project_warranties
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
     * @ Gridi doldurmak için info_project_warranties tablosundan kayıtları döndürür !!
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
                FROM info_project_warranties a
                INNER JOIN sys_language l ON l.id = a.language_id AND l.deleted =0 AND l.active = 0 
                LEFT JOIN sys_language lx ON lx.id = ".intval($languageIdValue)." AND lx.deleted =0 AND lx.active =0
                INNER JOIN sys_specific_definitions sd15 ON sd15.main_group = 15 AND sd15.first_group= a.deleted AND sd15.language_id = a.language_id AND sd15.deleted = 0 
                INNER JOIN sys_specific_definitions sd16 ON sd16.main_group = 16 AND sd16.first_group= a.active AND sd16.language_id = a.language_id AND sd16.deleted = 0
                INNER JOIN info_users u ON u.id = a.op_user_id    
                LEFT JOIN sys_specific_definitions sd15x ON (sd15x.id = sd15.id OR sd15x.language_parent_id = sd15.id) AND sd15x.language_id =lx.id  AND sd15x.deleted =0 AND sd15x.active =0 
                LEFT JOIN sys_specific_definitions sd16x ON (sd16x.id = sd16.id OR sd16x.language_parent_id = sd16.id)AND sd16x.language_id = lx.id  AND sd16x.deleted = 0 AND sd16x.active = 0
                LEFT JOIN info_project_warranties ax ON (ax.id = a.id OR ax.language_parent_id = a.id) AND ax.deleted =0 AND ax.active =0 AND lx.id = ax.language_id
                
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
     * @ Gridi doldurmak için info_project_warranties tablosundan çekilen kayıtlarının kaç tane olduğunu döndürür   !!
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
                FROM info_project_warranties a
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
     * @ info_project_warranties tablosundan parametre olarak  gelen id kaydın aktifliğini
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
                UPDATE info_project_warranties
                SET active = (  SELECT   
                                CASE active
                                    WHEN 0 THEN 1
                                    ELSE 0
                                END activex
                                FROM info_project_warranties
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
     * @ deal a eklenen Warranties aracları dropdown ya da tree ye doldurmak için info_project_warranties tablosundan kayıtları döndürür !!
     * @version v 1.0  11.08.2018
     * @param array | null $args
     * @return array
     * @throws \PDOException 
     */
    public function projectVehicleWarrantiesDdList($params = array()) {
        try {
            $pdo = $this->slimApp->getServiceManager()->get('pgConnectFactory');         
            $errorInfo[0] = "99999";         
            $addSQL =  " 1=2 AND ";
            $ProjectId=-1 ;
            if (isset($params['ProjectId']) && $params['ProjectId'] != "") {
                $ProjectId = $params['ProjectId'];
                $addSQL =   " pvm.project_id   = " . intval($ProjectId). "  AND  " ;
            }   
                            
            $sql =  "    
                SELECT                    
                    a.act_parent_id AS id, 	
                    concat(sv.name,' - ' , svgt.name ,' - ' , a.name)   AS name, 
                    concat(sv.name,' - ' , svgt.name) AS name_eng,
                    0 as parent_id,
                    a.active,
                    0 AS state_type   
                FROM sys_vehicle_gt_models a  
                inner join info_project_warranties pvm on pvm.vehicles_endgroup_id = a.act_parent_id AND pvm.active =0 AND pvm.deleted =0  
                inner join sys_vehicle_group_types svgt ON svgt.id = a.vehicle_group_types_id AND svgt.active =0 AND svgt.deleted =0 
                inner join sys_vehicle_groups sv ON sv.id =svgt.vehicle_groups_id AND sv.deleted =0 AND sv.active =0  
                WHERE  
                    ".$addSQL."
                    a.deleted = 0 AND
                    a.active =0   
                ORDER BY  svgt.vehicle_groups_id , concat(sv.name,' - ' , svgt.name)  , a.name  
 
                                 " ;
             $statement = $pdo->prepare($sql);
        //   echo debugPDO($sql, $params);
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
     * @ deal aracların Warranties tanımlarını grid formatında döndürür !! ana tablo  info_project_warranties 
     * @version v 1.0  20.08.2018
     * @param array | null $args 
     * @return array
     * @throws \PDOException  
     */  
    public function fillProjectWarrantiesGridx($params = array()) {
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
                            case 'deal_sis_key':
                                $sorguExpression = ' ILIKE \'%' . $std['value'] . '%\' ';
                                $sorguStr.=" AND pp.deal_sis_key" . $sorguExpression . ' ';
                              
                                break;
                            case 'vehicle_gt_model_name':
                                $sorguExpression = ' ILIKE \'%' . $std['value'] . '%\'  ';
                                $sorguStr.=" AND concat(sv.name,' - ' , svgt.name ,' - ' , gt.name) " . $sorguExpression . ' ';

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
            $addSQL =null ;       
                            
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
            $ProjectId =-1 ;
            if (isset($params['ProjectId']) && $params['ProjectId'] != "") {
                $ProjectId = $params['ProjectId']; 
            }   
              $addSQL =   " a.project_id  = " . intval($ProjectId). "  AND  " ;
                            
                $sql = "  
                     SELECT  
                        a.id, 
                        a.act_parent_id as apid,  
			a.project_id,
                        pp.deal_sis_key, 
			a.vehicles_endgroup_id,
			concat(sv.name,' - ' , svgt.name ,' - ' , gt.name) vehicle_gt_model_name, 
                  	  
			a.vehicle_group_id,
		        sv.name AS vehicle_description,
			  
			a.monthsx_id,
			mm.mvalue as month_value,
			a.mileages_id, 
			mi.mileages1 , 
			a.warranty_matrix_id,
			bbm.unique_code as waranty_code,
			bbm.price_in_euros, 
			a.quantity,
			a.warranty_type_id,
			wt.name as warranty_type_name,
			a.new_price,
			a.stock_vehicle_id,
			a.isbo_confirm,
			a.ishos_confirm,
			a.sa_description,
			a.bo_description,
			a.is_other,
			a.other_month ,
			a.other_km,
			a.other_desc ,
 
                        a.active,
                        COALESCE(NULLIF(sd16x.description, ''), sd16.description_eng) AS state_active, 
                        a.op_user_id,
                        u.username AS op_user_name,  
                        a.s_date date_saved,
                        a.c_date date_modified, 
                        COALESCE(NULLIF(lx.id, NULL), 385) AS language_id, 
                        lx.language_main_code language_code, 
                        COALESCE(NULLIF(lx.language, ''), 'en') AS language_name
                    FROM info_project_warranties a                    
                    INNER JOIN sys_language l ON l.id = 385 AND l.show_it =0
                    LEFT JOIN sys_language lx ON lx.id = " . intval($languageIdValue) . "  AND lx.show_it =0  
                    INNER JOIN info_users u ON u.id = a.op_user_id 
                    /*----*/   
		    inner join info_project pp on pp.act_parent_id = a.project_id 
                    inner join sys_vehicle_gt_models gt on gt.act_parent_id = a.vehicles_endgroup_id 
                    inner join sys_vehicle_group_types svgt ON svgt.act_parent_id = gt.vehicle_group_types_id AND svgt.show_it =0 
                    inner join sys_vehicle_groups sv ON sv.act_parent_id =a.vehicle_group_id AND sv.show_it =0  
		    left join sys_monthsx mm on mm.act_parent_id = a.monthsx_id AND mm.show_it =0 
		    left join sys_mileagesx mi on mi.act_parent_id = a.mileages_id AND mi.show_it =0 
		    
		      
		    INNER JOIN sys_warranty_matrix bbm ON bbm.act_parent_id = a.warranty_matrix_id AND bbm.show_it = 0  
		    left join sys_warranty_types wt ON wt.act_parent_id = a.warranty_type_id AND wt.show_it =0 
 
                    /*----*/   
                   /* INNER JOIN sys_specific_definitions sd15 ON sd15.main_group = 15 AND sd15.first_group= a.deleted AND sd15.deleted =0 AND sd15.active =0 AND sd15.language_parent_id =0 */
                    INNER JOIN sys_specific_definitions sd16 ON sd16.main_group = 16 AND sd16.first_group= a.active AND sd16.deleted = 0 AND sd16.active = 0 AND sd16.language_id =l.id
                    /**/
                  /*  LEFT JOIN sys_specific_definitions sd15x ON sd15x.language_id =lx.id AND (sd15x.id = sd15.id OR sd15x.language_parent_id = sd15.id) AND sd15x.deleted =0 AND sd15x.active =0  */
                    LEFT JOIN sys_specific_definitions sd16x ON sd16x.language_id = lx.id AND (sd16x.id = sd16.id OR sd16x.language_parent_id = sd16.id) AND sd16x.deleted = 0 AND sd16x.active = 0

		    
                    WHERE  
                  " . $addSQL . "
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
               echo debugPDO($sql, $params);               
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
     * @  deal aracların Warranties tanımlarını grid formatında gösterilirken kaç kayıt olduğunu döndürür !! ana tablo info_project_warranties
     * @version v 1.0  20.08.2018
     * @param array | null $args
     * @return array
     * @throws \PDOException  
     */  
    public function fillProjectWarrantiesGridxRtl($params = array()) {
        try {             
            $sorguStr = null;    
            $addSQL = null;
            if (isset($params['filterRules'])) {
                $filterRules = trim($params['filterRules']);
                $jsonFilter = json_decode($filterRules, true);
              
                $sorguExpression = null;
                foreach ($jsonFilter as $std) {
                    if ($std['value'] != null) {
                        switch (trim($std['field'])) {
                           case 'deal_sis_key':
                                $sorguExpression = ' ILIKE \'%' . $std['value'] . '%\' ';
                                $sorguStr.=" AND pp.deal_sis_key" . $sorguExpression . ' ';
                              
                                break;
                            case 'vehicle_gt_model_name':
                                $sorguExpression = ' ILIKE \'%' . $std['value'] . '%\'  ';
                                $sorguStr.=" AND concat(sv.name,' - ' , svgt.name ,' - ' , gt.name) " . $sorguExpression . ' ';

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
            $ProjectId =-1 ;
            if (isset($params['ProjectId']) && $params['ProjectId'] != "") {
                $ProjectId = $params['ProjectId']; 
            }   
              $addSQL .=   " a.project_id  = " . intval($ProjectId). "  AND  " ;  

                $sql = "
                   SELECT COUNT(asdx.id) count FROM ( 
                         SELECT  
                        a.id, 
                        a.act_parent_id as apid,  
			a.project_id,
                        pp.deal_sis_key, 
			a.vehicles_endgroup_id,
			concat(sv.name,' - ' , svgt.name ,' - ' , gt.name) vehicle_gt_model_name, 
                  	  
			a.vehicle_group_id,
		        sv.name AS vehicle_description,
			  
			a.monthsx_id,
			mm.mvalue as month_value,
			a.mileages_id, 
			mi.mileages1 , 
			a.warranty_matrix_id,
			bbm.unique_code as waranty_code,
			bbm.price_in_euros, 
			a.quantity,
			a.warranty_type_id,
			wt.name as warranty_type_name,
			a.new_price,
			a.stock_vehicle_id,
			a.isbo_confirm,
			a.ishos_confirm,
			a.sa_description,
			a.bo_description,
			a.is_other,
			a.other_month ,
			a.other_km,
			a.other_desc ,
 
                        a.active,
                        COALESCE(NULLIF(sd16x.description, ''), sd16.description_eng) AS state_active, 
                        a.op_user_id,
                        u.username AS op_user_name,  
                        a.s_date date_saved,
                        a.c_date date_modified, 
                        COALESCE(NULLIF(lx.id, NULL), 385) AS language_id, 
                        lx.language_main_code language_code, 
                        COALESCE(NULLIF(lx.language, ''), 'en') AS language_name
                    FROM info_project_warranties a                    
                    INNER JOIN sys_language l ON l.id = 385 AND l.show_it =0
                    LEFT JOIN sys_language lx ON lx.id = " . intval($languageIdValue) . "  AND lx.show_it =0  
                    INNER JOIN info_users u ON u.id = a.op_user_id 
                    /*----*/   
		    inner join info_project pp on pp.act_parent_id = a.project_id 
                    inner join sys_vehicle_gt_models gt on gt.act_parent_id = a.vehicles_endgroup_id 
                    inner join sys_vehicle_group_types svgt ON svgt.act_parent_id = gt.vehicle_group_types_id AND svgt.show_it =0 
                    inner join sys_vehicle_groups sv ON sv.act_parent_id =a.vehicle_group_id AND sv.show_it =0  
		    left join sys_monthsx mm on mm.act_parent_id = a.monthsx_id AND mm.show_it =0 
		    left join sys_mileagesx mi on mi.act_parent_id = a.mileages_id AND mi.show_it =0 
		    
		      
		    INNER JOIN sys_warranty_matrix bbm ON bbm.act_parent_id = a.warranty_matrix_id AND bbm.show_it = 0  
		    left join sys_warranty_types wt ON wt.act_parent_id = a.warranty_type_id AND wt.show_it =0 
 
                    /*----*/   
                   /* INNER JOIN sys_specific_definitions sd15 ON sd15.main_group = 15 AND sd15.first_group= a.deleted AND sd15.deleted =0 AND sd15.active =0 AND sd15.language_parent_id =0 */
                    INNER JOIN sys_specific_definitions sd16 ON sd16.main_group = 16 AND sd16.first_group= a.active AND sd16.deleted = 0 AND sd16.active = 0 AND sd16.language_id =l.id
                    /**/
                  /*  LEFT JOIN sys_specific_definitions sd15x ON sd15x.language_id =lx.id AND (sd15x.id = sd15.id OR sd15x.language_parent_id = sd15.id) AND sd15x.deleted =0 AND sd15x.active =0  */
                    LEFT JOIN sys_specific_definitions sd16x ON sd16x.language_id = lx.id AND (sd16x.id = sd16.id OR sd16x.language_parent_id = sd16.id) AND sd16x.deleted = 0 AND sd16x.active = 0

		    
                    WHERE  
                  " . $addSQL . "
                        a.deleted =0 AND
                        a.show_it =0   
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
     * @ info_project_warranties tablosundan parametre olarak  gelen id kaydını active ve show_it alanlarını 1 yapar. !!
     * @version v 1.0  24.08.2018
     * @param type $params
     * @return array
     * @throws \PDOException
     */
    public function makePassive($params = array()) {
        try {
            $pdo = $this->slimApp->getServiceManager()->get('pgConnectFactory'); 
            $statement = $pdo->prepare(" 
                UPDATE info_project_warranties
                SET                         
                    c_date =  timezone('Europe/Istanbul'::text, ('now'::text)::timestamp(0) with time zone) ,                     
                    active = 1 ,
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
     * @ info_project_warranties tablosundan parametre olarak  gelen id kaydın active veshow_it  alanını 1 yapar ve 
     * yeni yeni kayıt oluşturarak deleted ve active = 1  show_it =0 olarak  yeni kayıt yapar. !  
     * @version v 1.0  24.08.2018
     * @param array | null $args
     * @return array
     * @throws \PDOException
     */
    public function deletedAct($params = array()) { 
        try { 
             $pdo = $this->slimApp->getServiceManager()->get('pgConnectFactory');
            $pdo->beginTransaction();
            $opUserIdParams = array('pk' => $params['pk'],);
            $opUserIdArray = $this->slimApp->getBLLManager()->get('opUserIdBLL');
            $opUserId = $opUserIdArray->getUserId($opUserIdParams);
            if (\Utill\Dal\Helper::haveRecord($opUserId)) {
                $opUserIdValue = $opUserId ['resultSet'][0]['user_id'];
                $opUserRoleIdValue = $opUserId ['resultSet'][0]['role_id'];

                $this->makePassive(array('id' => $params['id']));

                 $sql = "
                    INSERT INTO info_project_warranties (  
                        project_id,
                        vehicle_group_id,
                        vehicles_endgroup_id,
                        monthsx_id,
                        warranty_matrix_id,
                        quantity,
                        warranty_type_id,
                        new_price,
                        stock_vehicle_id,
                        isbo_confirm,
                        ishos_confirm,
                        sa_description,
                        bo_description,
                        is_other,
                        other_month,
                        other_km,
                        other_desc,
                        delivery_date,
                        signed_contract,
                        is_pto_fhm,
                        pto_fhm_start_date,
                        pto_fhm_end_date,
                        mileages_id,

                        active,
                        deleted,
                        op_user_id,
                        act_parent_id,
                        show_it
                        )
                    SELECT
                        project_id,
                        vehicle_group_id,
                        vehicles_endgroup_id,
                        monthsx_id,
                        warranty_matrix_id,
                        quantity,
                        warranty_type_id,
                        new_price,
                        stock_vehicle_id,
                        isbo_confirm,
                        ishos_confirm,
                        sa_description,
                        bo_description,
                        is_other,
                        other_month,
                        other_km,
                        other_desc,
                        delivery_date,
                        signed_contract,
                        is_pto_fhm,
                        pto_fhm_start_date,
                        pto_fhm_end_date,
                        mileages_id,
                         
                        1 AS active,  
                        1 AS deleted, 
                        " . intval($opUserIdValue) . " AS op_user_id, 
                        act_parent_id,
                        0 AS show_it 
                    FROM info_project_warranties 
                    WHERE id  =" . intval($params['id']) . "   
                    " ;
                $statementInsert = $pdo->prepare($sql);
                
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
           // $pdo->rollback();
            return array("found" => false, "errorInfo" => $e->getMessage());
        }
    }

    /**
     * @author Okan CIRAN
     * @ info_project_warranties tablosuna yeni bir kayıt oluşturur.  !! 
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
                           
            $ProjectId = null;
            if ((isset($params['ProjectId']) && $params['ProjectId'] != "")) {
                $ProjectId = $params['ProjectId'];
            } else {
                throw new \PDOException($errorInfo[0]);
            }  
            $VehicleGroupId = null;
            if ((isset($params['VehicleGroupId']) && $params['VehicleGroupId'] != "")) {
                $VehicleGroupId = $params['VehicleGroupId'];
            } else {
                throw new \PDOException($errorInfo[0]);
            }   
            $vehiclesEndgroupId = null;
            if ((isset($params['VehiclesEndgroupId']) && $params['VehiclesEndgroupId'] != "")) {
                $vehiclesEndgroupId = $params['VehiclesEndgroupId'];
            } else {
                throw new \PDOException($errorInfo[0]);
            }  
             $Quantity = null;
            if ((isset($params['Quantity']) && $params['Quantity'] != "")) {
                $Quantity = $params['Quantity'];
            }  else {
                throw new \PDOException($errorInfo[0]);
            }  
            $IsBoConfirm = null;
            if ((isset($params['IsBoConfirm']) && $params['IsBoConfirm'] != "")) {
                $IsBoConfirm = $params['IsBoConfirm'];
            } 
            $IsHosConfirm = null;
            if ((isset($params['IsHosConfirm']) && $params['IsHosConfirm'] != "")) {
                $IsHosConfirm = $params['IsHosConfirm'];
            } 
            $IsOther = null;
            if ((isset($params['IsOther']) && $params['IsOther'] != "")) {
                $IsOther = $params['IsOther'];
            } 
            $OtherMmonth = null;
            if ((isset($params['OtherMmonth']) && $params['OtherMmonth'] != "")) {
                $OtherMmonth = $params['OtherMmonth'];
            } 
            $OtherKm = null;
            if ((isset($params['OtherKm']) && $params['OtherKm'] != "")) {
                $OtherKm = $params['OtherKm'];
            } 
            $OtherDescription = null;
            if ((isset($params['OtherDesc']) && $params['OtherDesc'] != "")) {
                $OtherDescription = $params['OtherDesc'];
            } 
            $WarrantyMatrixId = null;
            if ((isset($params['WarrantyMatrixId']) && $params['WarrantyMatrixId'] != "")) {
                $WarrantyMatrixId = $params['WarrantyMatrixId'];
            } 
            $WarrantyTypeId = null;
            if ((isset($params['WarrantyTypeId']) && $params['WarrantyTypeId'] != "")) {
                $WarrantyTypeId = $params['WarrantyTypeId'];
            } 
            $MileagesId = null;
            if ((isset($params['MileagesId']) && $params['MileagesId'] != "")) {
                $MileagesId = $params['MileagesId'];
            } 
            $MonthsxId = null;
            if ((isset($params['MonthsxId']) && $params['MonthsxId'] != "")) {
                $MonthsxId = $params['MonthsxId'];
            } 
                           
                           
            $NewPrice = null;
            if ((isset($params['NewPrice']) && $params['NewPrice'] != "")) {
                $NewPrice =  floatval($params['NewPrice']);
            } 
         
                           
            $opUserId = InfoUsers::getUserId(array('pk' => $params['pk']));
            if (\Utill\Dal\Helper::haveRecord($opUserId)) {
                $opUserIdValue = $opUserId ['resultSet'][0]['user_id'];

                $kontrol = $this->haveRecords(
                        array(
                            'project_id' => $ProjectId,  
                            'vehicle_group_id' => $VehicleGroupId,  
                            'vehicles_endgroup_id' =>  $vehiclesEndgroupId, 
                            'monthsx_id' => $MonthsxId,  
                            'mileages_id' =>  $MileagesId,  
                            'warranty_matrix_id' => $WarrantyMatrixId, 
                            'warranty_type_id' =>  $WarrantyTypeId,   
                ));
                if (!\Utill\Dal\Helper::haveRecord($kontrol)) {
                    $sql = "
                    INSERT INTO info_project_warranties(
                            project_id,
                            vehicle_group_id,
                            vehicles_endgroup_id,
                            monthsx_id,
                            mileages_id,
                            warranty_matrix_id,
                            quantity,
                            warranty_type_id,
                            new_price,  
                          
                            is_other,
                            other_month,
                            other_km,
                            other_desc,
                          
                         
                            op_user_id,
                            act_parent_id  
                            )
                    VALUES ( 
                            " .  intval($ProjectId). ",
                            " .  intval($VehicleGroupId). ",
                            " .  intval($vehiclesEndgroupId) . ",
                            " .  intval($MonthsxId). ",
                            " .  intval($MileagesId). ",
                            " .  intval($WarrantyMatrixId) . ",
                            " .  intval($Quantity). ",
                            " .  intval($WarrantyTypeId). ", 
                            " .  floatval($NewPrice). ", 
                           
                          
                            " .  intval($IsOther). ",
                            " .  intval($OtherMmonth). ",
                            " .  intval($OtherKm). ",
                            " .  intval($OtherDescription). ", 
                  
                            " . intval($opUserIdValue) . ",
                           (SELECT last_value FROM info_project_warranties_id_seq)
                                                 )   ";
                    $statement = $pdo->prepare($sql);
          //   echo debugPDO($sql, $params);
                    $result = $statement->execute();
                    $errorInfo = $statement->errorInfo();
                    if ($errorInfo[0] != "00000" && $errorInfo[1] != NULL && $errorInfo[2] != NULL)
                        throw new \PDOException($errorInfo[0]);
                    $insertID = $pdo->lastInsertId('info_project_warranties_id_seq');
                           
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
     * info_project_warranties tablosuna parametre olarak gelen id deki kaydın bilgilerini günceller   !!
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
            
             $ProjectId = null;
            if ((isset($params['ProjectId']) && $params['ProjectId'] != "")) {
                $ProjectId = $params['ProjectId'];
            } else {
                throw new \PDOException($errorInfo[0]);
            }  
            $VehicleGroupId = null;
            if ((isset($params['VehicleGroupId']) && $params['VehicleGroupId'] != "")) {
                $VehicleGroupId = $params['VehicleGroupId'];
            } else {
                throw new \PDOException($errorInfo[0]);
            }   
            $vehiclesEndgroupId = null;
            if ((isset($params['VehiclesEndgroupId']) && $params['VehiclesEndgroupId'] != "")) {
                $vehiclesEndgroupId = $params['VehiclesEndgroupId'];
            } else {
                throw new \PDOException($errorInfo[0]);
            }  
             $Quantity = null;
            if ((isset($params['Quantity']) && $params['Quantity'] != "")) {
                $Quantity = $params['Quantity'];
            }  else {
                throw new \PDOException($errorInfo[0]);
            }  
            $IsBoConfirm = null;
            if ((isset($params['IsBoConfirm']) && $params['IsBoConfirm'] != "")) {
                $IsBoConfirm = $params['IsBoConfirm'];
            } 
            $IsHosConfirm = null;
            if ((isset($params['IsHosConfirm']) && $params['IsHosConfirm'] != "")) {
                $IsHosConfirm = $params['IsHosConfirm'];
            } 
            $IsOther = null;
            if ((isset($params['IsOther']) && $params['IsOther'] != "")) {
                $IsOther = $params['IsOther'];
            } 
            $OtherMmonth = null;
            if ((isset($params['OtherMmonth']) && $params['OtherMmonth'] != "")) {
                $OtherMmonth = $params['OtherMmonth'];
            } 
            $OtherKm = null;
            if ((isset($params['OtherKm']) && $params['OtherKm'] != "")) {
                $OtherKm = $params['OtherKm'];
            } 
            $OtherDescription = null;
            if ((isset($params['OtherDesc']) && $params['OtherDesc'] != "")) {
                $OtherDescription = $params['OtherDesc'];
            } 
            $WarrantyMatrixId = null;
            if ((isset($params['WarrantyMatrixId']) && $params['WarrantyMatrixId'] != "")) {
                $WarrantyMatrixId = $params['WarrantyMatrixId'];
            } 
            $WarrantyTypeId = null;
            if ((isset($params['WarrantyTypeId']) && $params['WarrantyTypeId'] != "")) {
                $WarrantyTypeId = $params['WarrantyTypeId'];
            } 
            $MileagesId = null;
            if ((isset($params['MileagesId']) && $params['MileagesId'] != "")) {
                $MileagesId = $params['MileagesId'];
            } 
            $MonthsxId = null;
            if ((isset($params['MonthsxId']) && $params['MonthsxId'] != "")) {
                $MonthsxId = $params['MonthsxId'];
            } 
                           
            $NewPrice = null;
            if ((isset($params['NewPrice']) && $params['NewPrice'] != "")) {
                $NewPrice =  floatval($params['NewPrice']);
            } 
         
                            
            $opUserIdParams = array('pk' => $params['pk'],);
            $opUserIdArray = $this->slimApp->getBLLManager()->get('opUserIdBLL');
            $opUserId = $opUserIdArray->getUserId($opUserIdParams);
            if (\Utill\Dal\Helper::haveRecord($opUserId)) {
                $opUserIdValue = $opUserId ['resultSet'][0]['user_id'];
                $opUserRoleIdValue = $opUserId ['resultSet'][0]['role_id'];

                $kontrol = $this->haveRecords(
                        array(
                            'project_id' => $ProjectId,  
                            'vehicle_group_id' => $VehicleGroupId,  
                            'vehicles_endgroup_id' =>  $vehiclesEndgroupId, 
                            'monthsx_id' => $MonthsxId,  
                            'mileages_id' =>  $MileagesId,  
                            'warranty_matrix_id' => $WarrantyMatrixId, 
                            'warranty_type_id' =>  $WarrantyTypeId,   
                            'id' => $Id
                ));
                if (!\Utill\Dal\Helper::haveRecord($kontrol)) {

                    $this->makePassive(array('id' => $params['Id']));

                  $sql = "
                INSERT INTO info_project_warranties (  
                        project_id,
                        vehicle_group_id,
                        vehicles_endgroup_id,
                        monthsx_id,
                        mileages_id,
                        warranty_matrix_id,
                        quantity,
                        warranty_type_id,
                        new_price,  

                        is_other,
                        other_month,
                        other_km,
                        other_desc,
                          
                        op_user_id,
                        act_parent_id  
                        )  
                SELECT  
                    " .  intval($ProjectId). ",
                    " .  intval($VehicleGroupId). ",
                    " .  intval($vehiclesEndgroupId) . ",
                    " .  intval($MonthsxId). ",
                    " .  intval($MileagesId). ",
                    " .  intval($WarrantyMatrixId) . ",
                    " .  intval($Quantity). ",
                    " .  intval($WarrantyTypeId). ", 
                    " .  floatval($NewPrice). ", 


                    " .  intval($IsOther). ",
                    " .  intval($OtherMmonth). ",
                    " .  intval($OtherKm). ",
                    " .  intval($OtherDescription). ", 
                  
                                 
                    " . intval($opUserIdValue) . " AS op_user_id,  
                    act_parent_id
                FROM info_project_warranties 
                WHERE 
                    id  =" . intval($Id) . "                  
                                                " ;
                    $statementInsert = $pdo->prepare($sql);
                    $result = $statementInsert->execute();  
                    $errorInfo = $statementInsert->errorInfo();
                           
                    if ($errorInfo[0] != "00000" && $errorInfo[1] != NULL && $errorInfo[2] != NULL)
                        throw new \PDOException($errorInfo[0]);
                            
                     $affectedRows = $statementInsert->rowCount();
                    if ($affectedRows> 0 ){
                    $insertID = $pdo->lastInsertId('info_project_warranties_id_seq');}
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
    
    /** 
     * @author Okan CIRAN
     * @ deal aracların tradeback tanımlarını grid formatında döndürür !! ana tablo  info_project_warranties 
     * @version v 1.0  20.08.2018
     * @param array | null $args 
     * @return array
     * @throws \PDOException  
     */  
    public function fillProjectTBSpecialGridx($params = array()) {
        try {
                           
                           
            $sorguStr = null;
            if (isset($params['filterRules'])) {
                $filterRules = trim($params['filterRules']);
                $jsonFilter = json_decode($filterRules, true);

                $sorguExpression = null;
                foreach ($jsonFilter as $std) {
                    if ($std['value'] != null) {
                        switch (trim($std['field'])) {

                            case 'state_active':
                                $sorguExpression = ' ILIKE \'%' . $std['value'] . '%\'  ';
                                $sorguStr .= " AND COALESCE(NULLIF(sd16x.description, ''), sd16.description_eng)" . $sorguExpression . ' ';

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
            $errorInfo[0] = "99999";
            $TerrainId = -1;
            if (isset($params['TerrainId']) && $params['TerrainId'] != "") {
                $TerrainId = $params['TerrainId'];
            }else {  
                throw new \PDOException($errorInfo[0]);
            }
            $ContractTypeId = -1;
            if (isset($params['ContractTypeId']) && $params['ContractTypeId'] != "") {
                $ContractTypeId = $params['ContractTypeId'];
            }else {  
                throw new \PDOException($errorInfo[0]);
            }
            $ModelId = -1;
            if (isset($params['ModelId']) && $params['ModelId'] != "") {
                $ModelId = $params['ModelId'];
            }else {  
                throw new \PDOException($errorInfo[0]);
            }
            $CustomerTypeId = -1;
            if (isset($params['CustomerTypeId']) && $params['CustomerTypeId'] != "") {
                $CustomerTypeId = $params['CustomerTypeId'];
            }else {  
                throw new \PDOException($errorInfo[0]);
            }

            $sql = '   
                    SELECT 
                        a.id, 
                        a.act_parent_id as apid,  
                        a.mvalue  AS mvalue,
                        
                        (  select bb11.price from sys_buyback_matrix bb11 where bb11.month_id = a.act_parent_id AND bb11.show_it =0 AND bb11.terrain_id = bb1x.terrain_id AND bb11.mileage_id = 37 AND bb11.contract_type_id= bb1x.contract_type_id AND bb11.model_id =bb1x.model_id AND bb11.customer_type_id= bb1x.customer_type_id) as "37",
                        (  select bb12.price from sys_buyback_matrix bb12 where bb12.month_id = a.act_parent_id AND bb12.show_it =0 AND bb12.terrain_id = bb1x.terrain_id AND bb12.mileage_id = 38 AND bb12.contract_type_id= bb1x.contract_type_id AND bb12.model_id =bb1x.model_id AND bb12.customer_type_id= bb1x.customer_type_id) as "38",                   
                        (  select bb13.price from sys_buyback_matrix bb13 where bb13.month_id = a.act_parent_id AND bb13.show_it =0 AND bb13.terrain_id = bb1x.terrain_id AND bb13.mileage_id = 39 AND bb13.contract_type_id= bb1x.contract_type_id AND bb13.model_id =bb1x.model_id AND bb13.customer_type_id= bb1x.customer_type_id) as "39",                   
                        (  select bb14.price from sys_buyback_matrix bb14 where bb14.month_id = a.act_parent_id AND bb14.show_it =0 AND bb14.terrain_id = bb1x.terrain_id AND bb14.mileage_id = 40 AND bb14.contract_type_id= bb1x.contract_type_id AND bb14.model_id =bb1x.model_id AND bb14.customer_type_id= bb1x.customer_type_id) as "40",                   
                        (  select bb15.price from sys_buyback_matrix bb15 where bb15.month_id = a.act_parent_id AND bb15.show_it =0 AND bb15.terrain_id = bb1x.terrain_id AND bb15.mileage_id = 41 AND bb15.contract_type_id= bb1x.contract_type_id AND bb15.model_id =bb1x.model_id AND bb15.customer_type_id= bb1x.customer_type_id) as "41",                   
                        (  select bb16.price from sys_buyback_matrix bb16 where bb16.month_id = a.act_parent_id AND bb16.show_it =0 AND bb16.terrain_id = bb1x.terrain_id AND bb16.mileage_id = 42 AND bb16.contract_type_id= bb1x.contract_type_id AND bb16.model_id =bb1x.model_id AND bb16.customer_type_id= bb1x.customer_type_id) as "42",                   
                        (  select bb17.price from sys_buyback_matrix bb17 where bb17.month_id = a.act_parent_id AND bb17.show_it =0 AND bb17.terrain_id = bb1x.terrain_id AND bb17.mileage_id = 43 AND bb17.contract_type_id= bb1x.contract_type_id AND bb17.model_id =bb1x.model_id AND bb17.customer_type_id= bb1x.customer_type_id) as "43",                   
                        (  select bb18.price from sys_buyback_matrix bb18 where bb18.month_id = a.act_parent_id AND bb18.show_it =0 AND bb18.terrain_id = bb1x.terrain_id AND bb18.mileage_id = 44 AND bb18.contract_type_id= bb1x.contract_type_id AND bb18.model_id =bb1x.model_id AND bb18.customer_type_id= bb1x.customer_type_id) as "44",
                        (  select bb19.price from sys_buyback_matrix bb19 where bb19.month_id = a.act_parent_id AND bb19.show_it =0 AND bb19.terrain_id = bb1x.terrain_id AND bb19.mileage_id = 45 AND bb19.contract_type_id= bb1x.contract_type_id AND bb19.model_id =bb1x.model_id AND bb19.customer_type_id= bb1x.customer_type_id) as "45",                   
                        (  select bb110.price from sys_buyback_matrix bb110 where bb110.month_id = a.act_parent_id AND bb110.show_it =0 AND bb110.terrain_id = bb1x.terrain_id AND bb110.mileage_id = 46 AND bb110.contract_type_id= bb1x.contract_type_id AND bb110.model_id =bb1x.model_id AND bb110.customer_type_id= bb1x.customer_type_id) as "46"
                          
                 FROM sys_monthsx a        
                 LEFT join sys_buyback_matrix bb1x on bb1x.month_id = a.act_parent_id AND bb1x.show_it =0 AND 
                        bb1x.terrain_id = ' . intval($TerrainId) . ' AND 
                        bb1x.contract_type_id= ' . intval($ContractTypeId) . '  AND 
                        bb1x.model_id =' . intval($ModelId) . '  AND 
                        bb1x.customer_type_id=  ' . intval($CustomerTypeId) . '     
                WHERE  	
                    a.parent_id  = 26  /* buyback */  
                    a.deleted =0 AND
                    a.show_it =0   
                order by  a.mvalue 
                  
                  ';   
            $statement = $pdo->prepare($sql);
            //  echo debugPDO($sql, $params);               
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

                            
    
}
