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
class InfoProjectAcc extends \DAL\DalSlim {

    /**
     * @author Okan CIRAN    
     * @ info_project_acc tablosundan parametre olarak  gelen id kaydını siler. !!
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
                UPDATE info_project_acc
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
     * @ info_project_acc tablosundaki tüm kayıtları getirir.  !!
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
                FROM info_project_acc a
                INNER JOIN sys_language l ON l.id = a.language_id AND l.deleted =0 AND l.active = 0 
                LEFT JOIN sys_language lx ON lx.id = ".intval($languageIdValue)." AND lx.deleted =0 AND lx.active =0
                INNER JOIN sys_specific_definitions sd15 ON sd15.main_group = 15 AND sd15.first_group= a.deleted AND sd15.language_id = a.language_id AND sd15.deleted = 0 
                INNER JOIN sys_specific_definitions sd16 ON sd16.main_group = 16 AND sd16.first_group= a.active AND sd16.language_id = a.language_id AND sd16.deleted = 0
                INNER JOIN info_users u ON u.id = a.op_user_id    
                LEFT JOIN sys_specific_definitions sd15x ON (sd15x.id = sd15.id OR sd15x.language_parent_id = sd15.id) AND sd15x.language_id =lx.id  AND sd15x.deleted =0 AND sd15x.active =0 
                LEFT JOIN sys_specific_definitions sd16x ON (sd16x.id = sd16.id OR sd16x.language_parent_id = sd16.id)AND sd16x.language_id = lx.id  AND sd16x.deleted = 0 AND sd16x.active = 0
                LEFT JOIN info_project_acc ax ON (ax.id = a.id OR ax.language_parent_id = a.id) AND ax.deleted =0 AND ax.active =0 AND lx.id = ax.language_id
                
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
     * @ info_project_acc tablosuna yeni bir kayıt oluşturur.  !!
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
                INSERT INTO info_project_acc(
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
                    $insertID = $pdo->lastInsertId('info_project_acc_id_seq');
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
     * @ info_project_acc tablosunda property_name daha önce kaydedilmiş mi ?  
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
            FROM info_project_acc  a                          
            WHERE 
                a.project_id = " . intval($params['project_id']) . " AND   
                a.vehicles_group_id = " . intval($params['vehicles_group_id']) . " AND  
                a.vehicle_gt_model_id = " . intval($params['vehicle_gt_model_id']) . " AND  
                a.acc_option_id = " . intval($params['acc_option_id']) . " AND  
                a.accessories_matrix_id = " . intval($params['accessories_matrix_id']) . "     
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
     * info_project_acc tablosuna parametre olarak gelen id deki kaydın bilgilerini günceller   !!
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
                    UPDATE info_project_acc
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
     * @ Gridi doldurmak için info_project_acc tablosundan kayıtları döndürür !!
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
                FROM info_project_acc a
                INNER JOIN sys_language l ON l.id = a.language_id AND l.deleted =0 AND l.active = 0 
                LEFT JOIN sys_language lx ON lx.id = ".intval($languageIdValue)." AND lx.deleted =0 AND lx.active =0
                INNER JOIN sys_specific_definitions sd15 ON sd15.main_group = 15 AND sd15.first_group= a.deleted AND sd15.language_id = a.language_id AND sd15.deleted = 0 
                INNER JOIN sys_specific_definitions sd16 ON sd16.main_group = 16 AND sd16.first_group= a.active AND sd16.language_id = a.language_id AND sd16.deleted = 0
                INNER JOIN info_users u ON u.id = a.op_user_id    
                LEFT JOIN sys_specific_definitions sd15x ON (sd15x.id = sd15.id OR sd15x.language_parent_id = sd15.id) AND sd15x.language_id =lx.id  AND sd15x.deleted =0 AND sd15x.active =0 
                LEFT JOIN sys_specific_definitions sd16x ON (sd16x.id = sd16.id OR sd16x.language_parent_id = sd16.id)AND sd16x.language_id = lx.id  AND sd16x.deleted = 0 AND sd16x.active = 0
                LEFT JOIN info_project_acc ax ON (ax.id = a.id OR ax.language_parent_id = a.id) AND ax.deleted =0 AND ax.active =0 AND lx.id = ax.language_id
                
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
     * @ Gridi doldurmak için info_project_acc tablosundan çekilen kayıtlarının kaç tane olduğunu döndürür   !!
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
                FROM info_project_acc a
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
     * @ info_project_acc tablosundan parametre olarak  gelen id kaydın aktifliğini
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
                UPDATE info_project_acc
                SET active = (  SELECT   
                                CASE active
                                    WHEN 0 THEN 1
                                    ELSE 0
                                END activex
                                FROM info_project_acc
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
     * @ deal a eklenen  aracları dropdown ya da tree ye doldurmak için info_project_acc tablosundan kayıtları döndürür !!
     * @version v 1.0  11.08.2018
     * @param array | null $args
     * @return array
     * @throws \PDOException 
     */
    public function projectVehicleDdList($params = array()) {
        try {
            $pdo = $this->slimApp->getServiceManager()->get('pgConnectFactory');         
            $errorInfo[0] = "99999";         
            $addSQL =NULL;
            $ProjectId=-1 ; 
                            
            if (isset($params['ProjectId']) && $params['ProjectId'] != "") {
                $ProjectId = $params['ProjectId'];
                $addSQL .=   " pvm.project_id   = " . intval($ProjectId). "  AND  " ;
            }   
            if (isset($params['VehicleGroupsId']) && $params['VehicleGroupsId'] != "") {
                $ProjectId = $params['VehicleGroupsId'];
                $addSQL .=   " svgt.vehicle_groups_id  = " . intval($ProjectId). "  AND  " ;
            }  
            if ($addSQL == NULL ){$addSQL = " 1=2 AND ";}
                            
            $sql =  "    
                SELECT                    
                    a.act_parent_id AS id, 	
                    concat(sv.name,' - ' , svgt.name ,' - ' , a.name)   AS name, 
                    concat(sv.name,' - ' , svgt.name) AS name_eng,
                    0 as parent_id,
                    a.active,
                    0 AS state_type   
                FROM sys_vehicle_gt_models a  
                inner join info_project_vehicle_models pvm on pvm.vehicle_gt_model_id = a.act_parent_id AND pvm.active =0 AND pvm.deleted =0  
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
     * @ deal aracların buyback tanımlarını grid formatında döndürür !! ana tablo  info_project_acc 
     * @version v 1.0  20.08.2018
     * @param array | null $args 
     * @return array
     * @throws \PDOException  
     */  
    public function fillProjectVehicleAccGridx($params = array()) {
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
              $addSQL .=   " a.project_id  = " . intval($ProjectId). "  AND  " ;
                            
                $sql = "                
                    SELECT  
                        a.id, 
                        a.act_parent_id as apid,  
			a.project_id,
                        pp.deal_sis_key, 
			a.vehicle_gt_model_id,
			concat(sv.name,' - ' , svgt.name ,' - ' , gt.name) vehicle_gt_model_name, 
                        concat(sv.name,' - ' , svgt.name ,' - ' , gt.name , ' / ', cast(a.quantity as character varying(10)), ' Pieces' , ' /  Delivery Date =', cast(bbm.list_price as character varying(10))) tag_name, 
			a.quantity,
			
			bbm.list_price, 

			a.acc_option_id, 
			bbm1.name as option_name, 
			a.acc_supplier_id, 
			ss.name as supplier_name, 
			a.accessories_matrix_id,
			bbm.list_price ,
			
			a.is_other,  
			COALESCE(NULLIF(sd19a.description, ''), sd19ay.description_eng) AS is_other_name, 
			
			a.other_acc_name,
                        a.other_acc_brand,
                        a.other_acc_supplier,
			
			a.deal_acc_newvalue ,  
			a.isbo_confirm,   
			a.ishos_confirm, 
			a.sa_description, 
			a.bo_description,
 
                        a.active,
                        COALESCE(NULLIF(sd16x.description, ''), sd16.description_eng) AS state_active, 
                        a.op_user_id,
                        u.username AS op_user_name,  
                        a.s_date date_saved,
                        a.c_date date_modified, 
                        COALESCE(NULLIF(lx.id, NULL), 385) AS language_id, 
                        lx.language_main_code language_code, 
                        COALESCE(NULLIF(lx.language, ''), 'en') AS language_name
                    FROM info_project_acc a                    
                    INNER JOIN sys_language l ON l.id = 385 AND l.show_it =0
                    LEFT JOIN sys_language lx ON lx.id = " . intval($languageIdValue) . "  AND lx.show_it =0 
                    INNER JOIN info_users u ON u.id = a.op_user_id 
                    /*----*/   
		    inner join info_project pp on pp.act_parent_id = a.project_id 
                    LEFT join sys_vehicle_gt_models gt on gt.act_parent_id = a.vehicle_gt_model_id 
                    inner join sys_vehicle_group_types svgt ON svgt.act_parent_id = gt.vehicle_group_types_id AND svgt.show_it =0 
                    inner join sys_vehicle_groups sv ON sv.act_parent_id =svgt.vehicle_groups_id AND sv.show_it =0  
	  
		   
		    INNER JOIN sys_accessories_matrix bbm ON bbm.act_parent_id = a.accessories_matrix_id AND bbm.show_it = 0  
		    INNER JOIN sys_accessory_options bbm1 ON bbm1.act_parent_id = a.acc_option_id AND bbm1.show_it = 0  
		    INNER JOIN sys_supplier ss ON ss.act_parent_id = a.acc_supplier_id AND ss.show_it = 0  
 
                    /*----*/   
                   /* INNER JOIN sys_specific_definitions sd15 ON sd15.main_group = 15 AND sd15.first_group= a.deleted AND sd15.deleted =0 AND sd15.active =0 AND sd15.language_parent_id =0 */
                    INNER JOIN sys_specific_definitions sd16 ON sd16.main_group = 16 AND sd16.first_group= a.active AND sd16.deleted = 0 AND sd16.active = 0 AND sd16.language_id =l.id
                    /**/
                  /*  LEFT JOIN sys_specific_definitions sd15x ON sd15x.language_id =lx.id AND (sd15x.id = sd15.id OR sd15x.language_parent_id = sd15.id) AND sd15x.deleted =0 AND sd15x.active =0  */
                    LEFT JOIN sys_specific_definitions sd16x ON sd16x.language_id = lx.id AND (sd16x.id = sd16.id OR sd16x.language_parent_id = sd16.id) AND sd16x.deleted = 0 AND sd16x.active = 0

		    inner JOIN sys_specific_definitions sd19a ON sd19a.main_group = 19 AND sd19a.first_group= a.is_other AND sd19a.deleted = 0 AND sd19a.active = 0 AND sd19a.language_id =l.id
                    LEFT JOIN sys_specific_definitions sd19ay ON sd19ay.language_id = lx.id AND (sd19ay.id = sd19a.id OR sd19ay.language_parent_id = sd19a.id) AND sd19ay.deleted = 0 AND sd19ay.active = 0
                    
		   
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
               // echo debugPDO($sql, $params);               
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
     * @  garanti tanımlarını grid formatında gösterilirken kaç kayıt olduğunu döndürür !! ana tablo info_project_acc
     * @version v 1.0  20.08.2018
     * @param array | null $args
     * @return array
     * @throws \PDOException  
     */  
    public function fillProjectVehicleAccGridxRtl($params = array()) {
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
			a.vehicle_gt_model_id,
			concat(sv.name,' - ' , svgt.name ,' - ' , gt.name) vehicle_gt_model_name, 
                        concat(sv.name,' - ' , svgt.name ,' - ' , gt.name , ' / ', cast(a.quantity as character varying(10)), ' Pieces' , ' /  Delivery Date =', cast(bbm.list_price as character varying(10))) tag_name, 
			a.quantity,
			
			bbm.list_price, 

			a.acc_option_id, 
			bbm1.name as option_name, 
			a.acc_supplier_id, 
			ss.name as supplier_name, 
			a.accessories_matrix_id,
			bbm.list_price ,
			
			a.is_other,  
			COALESCE(NULLIF(sd19a.description, ''), sd19ay.description_eng) AS is_other_name, 
			
			a.other_acc_name,
                        a.other_acc_brand,
                        a.other_acc_supplier,
			
			a.deal_acc_newvalue ,  
			a.isbo_confirm,   
			a.ishos_confirm, 
			a.sa_description, 
			a.bo_description, 
                        COALESCE(NULLIF(sd16x.description, ''), sd16.description_eng) AS state_active 
                    FROM info_project_acc a                    
                    INNER JOIN sys_language l ON l.id = 385 AND l.show_it =0
                    LEFT JOIN sys_language lx ON lx.id = " . intval($languageIdValue) . "  AND lx.show_it =0 
                    INNER JOIN info_users u ON u.id = a.op_user_id 
                    /*----*/   
		    inner join info_project pp on pp.act_parent_id = a.project_id 
                    LEFT join sys_vehicle_gt_models gt on gt.act_parent_id = a.vehicle_gt_model_id 
                    inner join sys_vehicle_group_types svgt ON svgt.act_parent_id = gt.vehicle_group_types_id AND svgt.show_it =0 
                    inner join sys_vehicle_groups sv ON sv.act_parent_id =svgt.vehicle_groups_id AND sv.show_it =0  
	  
		   
		    INNER JOIN sys_accessories_matrix bbm ON bbm.act_parent_id = a.accessories_matrix_id AND bbm.show_it = 0  
		    INNER JOIN sys_accessory_options bbm1 ON bbm1.act_parent_id = a.acc_option_id AND bbm1.show_it = 0  
		    INNER JOIN sys_supplier ss ON ss.act_parent_id = a.acc_supplier_id AND ss.show_it = 0  
 
                    /*----*/   
                   /* INNER JOIN sys_specific_definitions sd15 ON sd15.main_group = 15 AND sd15.first_group= a.deleted AND sd15.deleted =0 AND sd15.active =0 AND sd15.language_parent_id =0 */
                    INNER JOIN sys_specific_definitions sd16 ON sd16.main_group = 16 AND sd16.first_group= a.active AND sd16.deleted = 0 AND sd16.active = 0 AND sd16.language_id =l.id
                    /**/
                  /*  LEFT JOIN sys_specific_definitions sd15x ON sd15x.language_id =lx.id AND (sd15x.id = sd15.id OR sd15x.language_parent_id = sd15.id) AND sd15x.deleted =0 AND sd15x.active =0  */
                    LEFT JOIN sys_specific_definitions sd16x ON sd16x.language_id = lx.id AND (sd16x.id = sd16.id OR sd16x.language_parent_id = sd16.id) AND sd16x.deleted = 0 AND sd16x.active = 0

		    inner JOIN sys_specific_definitions sd19a ON sd19a.main_group = 19 AND sd19a.first_group= a.is_other AND sd19a.deleted = 0 AND sd19a.active = 0 AND sd19a.language_id =l.id
                    LEFT JOIN sys_specific_definitions sd19ay ON sd19ay.language_id = lx.id AND (sd19ay.id = sd19a.id OR sd19ay.language_parent_id = sd19a.id) AND sd19ay.deleted = 0 AND sd19ay.active = 0
                     
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
     * @ info_project_acc tablosundan parametre olarak  gelen id kaydını active ve show_it alanlarını 1 yapar. !!
     * @version v 1.0  24.08.2018
     * @param type $params
     * @return array
     * @throws \PDOException
     */
    public function makePassive($params = array()) {
        try {
            $pdo = $this->slimApp->getServiceManager()->get('pgConnectFactory'); 
            $statement = $pdo->prepare(" 
                UPDATE info_project_acc
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
     * @ info_project_acc tablosundan parametre olarak  gelen id kaydın active veshow_it  alanını 1 yapar ve 
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
                    INSERT INTO info_project_acc (  
                        project_id,
                        vehicles_group_id,
                        vehicle_gt_model_id,
                        acc_option_id,
                        acc_supplier_id,
                        accessories_matrix_id,

                        deal_acc_newvalue,
                        quantity,
                        is_onsite_offsite,
                        sis_statu_id,
                        detail_information,
                        is_other,

                        isbo_confirm,
                        ishos_confirm,
                        sa_description,
                        bo_description,
                        other_acc_name,
                        other_acc_brand,
                        other_acc_supplier,
                         
                        active,
                        deleted,
                        op_user_id,
                        act_parent_id,
                        show_it
                        )
                    SELECT
                        project_id,
                        vehicles_group_id,
                        vehicle_gt_model_id,
                        acc_option_id,
                        acc_supplier_id,
                        accessories_matrix_id,

                        deal_acc_newvalue,
                        quantity,
                        is_onsite_offsite,
                        sis_statu_id,
                        detail_information,
                        is_other,

                        isbo_confirm,
                        ishos_confirm,
                        sa_description,
                        bo_description,
                        other_acc_name,
                        other_acc_brand,
                        other_acc_supplier,
                         
                        1 AS active,  
                        1 AS deleted, 
                        " . intval($opUserIdValue) . " AS op_user_id, 
                        act_parent_id,
                        0 AS show_it 
                    FROM info_project_acc 
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
     * @ info_project_acc tablosuna yeni bir kayıt oluşturur.  !! 
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
            $ProjectId = null;
            if ((isset($params['ProjectId']) && $params['ProjectId'] != "")) {
                $ProjectId = $params['ProjectId'];
            } else {
                throw new \PDOException($errorInfo[0]);
            }                            
                           
            $VehiclesGroupId = 0;
            if ((isset($params['VehiclesGroupId']) && $params['VehiclesGroupId'] != "")) {
                $VehiclesGroupId = intval($params['VehiclesGroupId']);
            }  else {
                throw new \PDOException($errorInfo[0]);
            }   
            $VehicleGtModelId = null;
            if ((isset($params['VehicleGtModelId']) && $params['VehicleGtModelId'] != "")) {
                $VehicleGtModelId = $params['VehicleGtModelId'];
            } 
            $AccOptionId = null;
            if ((isset($params['AccOptionId']) && $params['AccOptionId'] != "")) {
                $AccOptionId = $params['AccOptionId'];
            } 
            $AccSupplierId= null;
            if ((isset($params['AccSupplierId']) && $params['AccSupplierId'] != "")) {
                $AccSupplierId = $params['AccSupplierId'];
            } 
            $AccessoriesMatrixId = null;
            if ((isset($params['AccessoriesMatrixId']) && $params['AccessoriesMatrixId'] != "")) {
                $AccessoriesMatrixId = $params['AccessoriesMatrixId'];
            } 
            $DealAccNewvalue = null;
            if ((isset($params['DealAccNewvalue']) && $params['DealAccNewvalue'] != "")) {
                $DealAccNewvalue = $params['DealAccNewvalue'];
            }             
            $Quantity = null;
            if ((isset($params['Quantity']) && $params['Quantity'] != "")) {
                $Quantity = $params['Quantity'];
            } 
            $IsOnsiteOffsite = null;
            if ((isset($params['IsOnsiteOffsite']) && $params['IsOnsiteOffsite'] != "")) {
                $IsOnsiteOffsite = $params['IsOnsiteOffsite'];
            } 
            $DetailInformation = null;
            if ((isset($params['DetailInformation']) && $params['DetailInformation'] != "")) {
                $DetailInformation = $params['DetailInformation'];
            }  
            $IsOther = null;
            if ((isset($params['IsOther']) && $params['IsOther'] != "")) {
                $IsOther = $params['IsOther'];
            }             
            $IsBoConfirm = null;
            if ((isset($params['IsBoConfirm']) && $params['IsBoConfirm'] != "")) {
                $IsBoConfirm = $params['IsBoConfirm'];
            } 
            $IsHosConfirm = null;
            if ((isset($params['IsHosConfirm']) && $params['IsHosConfirm'] != "")) {
                $IsHosConfirm = $params['IsHosConfirm'];
            } 
            $SaDescription = null;
            if ((isset($params['SaDescription']) && $params['SaDescription'] != "")) {
                $SaDescription = $params['SaDescription'];
            } 
            $BoDescription = null;
            if ((isset($params['BoDescription']) && $params['BoDescription'] != "")) {
                $BoDescription = $params['BoDescription'];
            } 
            $OtherAccName = null;
            if ((isset($params['OtherAccName']) && $params['OtherAccName'] != "")) {
                $OtherAccName = $params['OtherAccName'];
            } 
            $OtherAccBrand= null;
            if ((isset($params['OtherAccBrand']) && $params['OtherAccBrand'] != "")) {
                $OtherAccBrand = $params['OtherAccBrand'];
            } 
            $OtherAccSupplier= null;
            if ((isset($params['OtherAccSupplier']) && $params['OtherAccSupplier'] != "")) {
                $OtherAccSupplier = $params['OtherAccSupplier'];
            } 
                           
            $opUserId = InfoUsers::getUserId(array('pk' => $params['pk']));
            if (\Utill\Dal\Helper::haveRecord($opUserId)) {
                $opUserIdValue = $opUserId ['resultSet'][0]['user_id'];

                $kontrol = $this->haveRecords(
                        array(
                            'project_id' => $ProjectId,   
                            'vehicles_group_id' => $VehiclesGroupId,  
                            'vehicle_gt_model_id' => $VehicleGtModelId,  
                            'acc_option_id' =>  $AccOptionId,  
                            'acc_supplier_id' => $AccSupplierId,  
                            'accessories_matrix_id' =>  $AccessoriesMatrixId,  
                ));
                if (!\Utill\Dal\Helper::haveRecord($kontrol)) {
                    $sql = "
                    INSERT INTO info_project_acc(
                            project_id,
                            vehicles_group_id,
                            vehicle_gt_model_id,
                            acc_option_id,
                            acc_supplier_id,
                            accessories_matrix_id,
                       
                            deal_acc_newvalue,
                            quantity,
                            is_onsite_offsite,
                         
                            detail_information,
                            is_other,
                            
                            isbo_confirm,
                            ishos_confirm,
                            sa_description,
                            bo_description,
                            other_acc_name,
                            other_acc_brand,
                            other_acc_supplier,
 
                            op_user_id,
                            act_parent_id  
                            )
                    VALUES ( 
                            " .  intval($ProjectId). ",
                         
                            " .  intval($VehiclesGroupId). ",
                            " .  intval($VehicleGtModelId). ",
                            " .  intval($AccOptionId) . ",
                            " .  intval($AccSupplierId). ",
                          
                            " .  intval($AccessoriesMatrixId). ",
                            " .  intval($DealAccNewvalue) . ",
                            " .  intval($Quantity). ",
                            " .  intval($IsOnsiteOffsite). ",
                            " .  intval($DetailInformation). ",
                            " .  intval($IsOther). ",
                            " .  intval($IsBoConfirm). ", 
                            " .  intval($IsHosConfirm). ", 
                            '" .   ($SaDescription). "',
                            '" .   ($BoDescription). "',
                            '" .   ($OtherAccName). "',
                            '" .   ($OtherAccBrand). "',
                            '" .   ($OtherAccSupplier). "',
                            
                  
                            " . intval($opUserIdValue) . ",
                           (SELECT last_value FROM info_project_acc_id_seq)
                                                 )   ";
                    $statement = $pdo->prepare($sql);
           //  echo debugPDO($sql, $params);
                    $result = $statement->execute();
                    $errorInfo = $statement->errorInfo();
                    if ($errorInfo[0] != "00000" && $errorInfo[1] != NULL && $errorInfo[2] != NULL)
                        throw new \PDOException($errorInfo[0]);
                    $insertID = $pdo->lastInsertId('info_project_acc_id_seq');
                           
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
     * info_project_acc tablosuna parametre olarak gelen id deki kaydın bilgilerini günceller   !!
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
                           
            $VehiclesGroupId = 0;
            if ((isset($params['VehiclesGroupId']) && $params['VehiclesGroupId'] != "")) {
                $VehiclesGroupId = intval($params['VehiclesGroupId']);
            }  else {
                throw new \PDOException($errorInfo[0]);
            }   
            $VehicleGtModelId = null;
            if ((isset($params['VehicleGtModelId']) && $params['VehicleGtModelId'] != "")) {
                $VehicleGtModelId = $params['VehicleGtModelId'];
            } 
            $AccOptionId = null;
            if ((isset($params['AccOptionId']) && $params['AccOptionId'] != "")) {
                $AccOptionId = $params['AccOptionId'];
            } 
            $AccSupplierId= null;
            if ((isset($params['AccSupplierId']) && $params['AccSupplierId'] != "")) {
                $AccSupplierId = $params['AccSupplierId'];
            } 
            $AccessoriesMatrixId = null;
            if ((isset($params['AccessoriesMatrixId']) && $params['AccessoriesMatrixId'] != "")) {
                $AccessoriesMatrixId = $params['AccessoriesMatrixId'];
            } 
            $DealAccNewvalue = null;
            if ((isset($params['DealAccNewvalue']) && $params['DealAccNewvalue'] != "")) {
                $DealAccNewvalue = $params['DealAccNewvalue'];
            }             
            $Quantity = null;
            if ((isset($params['Quantity']) && $params['Quantity'] != "")) {
                $Quantity = $params['Quantity'];
            } 
            $IsOnsiteOffsite = null;
            if ((isset($params['IsOnsiteOffsite']) && $params['IsOnsiteOffsite'] != "")) {
                $IsOnsiteOffsite = $params['IsOnsiteOffsite'];
            } 
            $DetailInformation = null;
            if ((isset($params['DetailInformation']) && $params['DetailInformation'] != "")) {
                $DetailInformation = $params['DetailInformation'];
            }  
            $IsOther = null;
            if ((isset($params['IsOther']) && $params['IsOther'] != "")) {
                $IsOther = $params['IsOther'];
            }             
            $IsBoConfirm = null;
            if ((isset($params['IsBoConfirm']) && $params['IsBoConfirm'] != "")) {
                $IsBoConfirm = $params['IsBoConfirm'];
            } 
            $IsHosConfirm = null;
            if ((isset($params['IsHosConfirm']) && $params['IsHosConfirm'] != "")) {
                $IsHosConfirm = $params['IsHosConfirm'];
            } 
            $SaDescription = null;
            if ((isset($params['SaDescription']) && $params['SaDescription'] != "")) {
                $SaDescription = $params['SaDescription'];
            } 
            $BoDescription = null;
            if ((isset($params['BoDescription']) && $params['BoDescription'] != "")) {
                $BoDescription = $params['BoDescription'];
            } 
            $OtherAccName = null;
            if ((isset($params['OtherAccName']) && $params['OtherAccName'] != "")) {
                $OtherAccName = $params['OtherAccName'];
            } 
            $OtherAccBrand= null;
            if ((isset($params['OtherAccBrand']) && $params['OtherAccBrand'] != "")) {
                $OtherAccBrand = $params['OtherAccBrand'];
            } 
            $OtherAccSupplier= null;
            if ((isset($params['OtherAccSupplier']) && $params['OtherAccSupplier'] != "")) {
                $OtherAccSupplier = $params['OtherAccSupplier'];
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
                            'vehicles_group_id' => $VehiclesGroupId,  
                            'vehicle_gt_model_id' => $VehicleGtModelId,  
                            'acc_option_id' =>  $AccOptionId,  
                            'acc_supplier_id' => $AccSupplierId,  
                            'accessories_matrix_id' =>  $AccessoriesMatrixId,  
                            'id' => $Id
                ));
                if (!\Utill\Dal\Helper::haveRecord($kontrol)) {

                    $this->makePassive(array('id' => $params['Id']));

                  $sql = "
                INSERT INTO info_project_acc (  
                        project_id,
                        vehicles_group_id,
                        vehicle_gt_model_id,
                        acc_option_id,
                        acc_supplier_id,
                        accessories_matrix_id,

                        deal_acc_newvalue,
                        quantity,
                        is_onsite_offsite,

                        detail_information,
                        is_other,

                        isbo_confirm,
                        ishos_confirm,
                        sa_description,
                        bo_description,
                        other_acc_name,
                        other_acc_brand,
                        other_acc_supplier,

                        op_user_id,
                        act_parent_id  
                        )  
                SELECT  
                    " .  intval($ProjectId). ",
                         
                    " .  intval($VehiclesGroupId). ",
                    " .  intval($VehicleGtModelId). ",
                    " .  intval($AccOptionId) . ",
                    " .  intval($AccSupplierId). ",

                    " .  intval($AccessoriesMatrixId). ",
                    " .  intval($DealAccNewvalue) . ",
                    " .  intval($Quantity). ",
                    " .  intval($IsOnsiteOffsite). ",
                    " .  intval($DetailInformation). ",
                    " .  intval($IsOther). ",
                    " .  intval($IsBoConfirm). ", 
                    " .  intval($IsHosConfirm). ", 
                    '" .   ($SaDescription). "',
                    '" .   ($BoDescription). "',
                    '" .   ($OtherAccName). "',
                    '" .   ($OtherAccBrand). "',
                    '" .   ($OtherAccSupplier). "',
                                 
                    " . intval($opUserIdValue) . " AS op_user_id,  
                    act_parent_id
                FROM info_project_acc 
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
                    $insertID = $pdo->lastInsertId('info_project_acc_id_seq');}
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
