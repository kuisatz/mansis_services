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
 * @author Okan CİRANĞ
 */
class InfoUsersProductsServices extends \DAL\DalSlim {

    /**
     * @author Okan CIRAN
     * @ info_users_products_services tablosundan parametre olarak  gelen id kaydını siler. !!
     * @version v 1.0 24.06.2016
     * @param array | null $args
     * @return array
     * @throws \PDOException
     */
    public function delete($params = array()) {
        try {
            $pdo = $this->slimApp->getServiceManager()->get('oracleConnectFactory');
            $pdo->beginTransaction();
            $userId = InfoUsers::getUserId(array('pk' => $params['pk']));
            if (\Utill\Dal\Helper::haveRecord($userId)) {
                $userIdValue = $userId ['resultSet'][0]['user_id'];
                $statement = $pdo->prepare(" 
                UPDATE info_users_products_services
                SET  deleted= 1 , active = 1 ,
                     op_user_id = " . $userIdValue . "     
                WHERE id = :id");
                //Execute our DELETE statement.
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
     * @ info_users_products_services tablosundaki tüm kayıtları getirir.  !!
     * @version v 1.0  24.06.2016   
     * @param array | null $args
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
                        iud.name,
                        iud.surname,
                        iud.auth_email,
			a.unspsc_codes_id,
			COALESCE(NULLIF(sucx.unspsc_name, ''), suc.unspsc_name_eng) AS unspsc_name,
			suc.unspsc_name_eng,
			a.s_date,
                        a.c_date,
                        a.profile_public,
                        COALESCE(NULLIF(sd19x.description, ''), sd19.description_eng) AS state_profile_public,
                        a.operation_type_id,
                        COALESCE(NULLIF(opx.operation_name, ''), op.operation_name_eng) AS operation_name,
			a.act_parent_id,
                        COALESCE(NULLIF(lx.id, NULL), 385) AS language_id,
		        COALESCE(NULLIF(lx.language, ''), 'en') AS language_name,
                        a.active,
                        COALESCE(NULLIF(sd16x.description, ''), sd16.description_eng) AS state_active,
                        a.deleted,
			COALESCE(NULLIF(sd15x.description, ''), sd15.description_eng) AS state_deleted,
                        a.op_user_id,
                        u.username AS op_user_name,
			a.consultant_id, 
			uc.username AS consultant,
			a.consultant_confirm_type_id, 
			a.confirm_id,
                        a.cons_allow_id,
                        COALESCE(NULLIF(sd14x.description, ''), sd14.description_eng) AS cons_allow,
                        iu.network_key AS unpk
                    FROM info_users_products_services a                    
                    INNER JOIN info_users_detail iud ON iud.root_id = a.user_id AND iud.active = 0 AND iud.deleted = 0 AND iud.language_parent_id =0
                    INNER JOIN sys_language l ON l.id = iud.language_id AND l.deleted =0 AND l.active =0
                    LEFT JOIN sys_language lx ON lx.id = " . intval($languageIdValue) . " AND lx.deleted =0 AND lx.active =0
                    INNER JOIN info_users iu ON iu.id = a.user_id 

		    INNER JOIN sys_operation_types op ON op.id = a.operation_type_id AND op.deleted =0 AND op.active =0 AND op.language_parent_id =0
                    LEFT JOIN sys_operation_types opx ON (opx.id = op.id OR opx.language_parent_id = op.id) and opx.language_id =lx.id  AND opx.deleted =0 AND opx.active =0
                    
		    INNER JOIN sys_specific_definitions sd14 ON sd14.main_group = 14 AND a.cons_allow_id = sd14.first_group AND sd14.deleted =0 AND sd14.active =0 AND sd14.language_parent_id =0
		    INNER JOIN sys_specific_definitions sd15 ON sd15.main_group = 15 AND sd15.first_group= a.deleted AND sd15.deleted =0 AND sd15.active =0 AND sd15.language_parent_id =0
		    INNER JOIN sys_specific_definitions sd16 ON sd16.main_group = 16 AND sd16.first_group= a.active AND sd16.deleted = 0 AND sd16.active = 0 AND sd16.language_parent_id =0
		    INNER JOIN sys_specific_definitions sd19 ON sd19.main_group = 19 AND sd19.first_group= a.profile_public AND sd19.deleted = 0 AND sd19.active = 0 AND sd19.language_parent_id =0
                  
                    LEFT JOIN sys_specific_definitions sd14x ON sd14x.language_id = lx.id AND (sd14x.id = sd14.id OR sd14x.language_parent_id = sd14.id) AND sd14x.deleted =0 AND sd14x.active =0
                    LEFT JOIN sys_specific_definitions sd15x ON sd15x.language_id =lx.id AND (sd15x.id = sd15.id OR sd15x.language_parent_id = sd15.id) AND sd15x.deleted =0 AND sd15x.active =0 
                    LEFT JOIN sys_specific_definitions sd16x ON sd16x.language_id = lx.id AND (sd16x.id = sd16.id OR sd16x.language_parent_id = sd16.id) AND sd16x.deleted = 0 AND sd16x.active = 0
                    LEFT JOIN sys_specific_definitions sd19x ON sd19x.language_id = lx.id AND (sd19x.id = sd19.id OR sd19x.language_parent_id = sd19.id) AND sd19x.deleted = 0 AND sd19x.active = 0
                    
		    INNER JOIN sys_unspsc_codes suc ON suc.id = a.unspsc_codes_id AND suc.active = 0 AND suc.deleted = 0 AND suc.language_id =l.id AND suc.language_parent_id =0 
		    LEFT JOIN sys_unspsc_codes sucx ON (sucx.id = suc.id OR sucx.language_parent_id = suc.id) AND sucx.active = 0 AND sucx.deleted = 0 AND sucx.language_id =lx.id 		    
		    INNER JOIN info_users u ON u.id = a.op_user_id
		    INNER JOIN info_users uc ON uc.id = a.consultant_id
                          ");
            $statement->execute();
            $result = $statement->fetcAll(\PDO::FETCH_ASSOC);
            $errorInfo = $statement->errorInfo();
            if ($errorInfo[0] != "00000" && $errorInfo[1] != NULL && $errorInfo[2] != NULL)
                throw new \PDOException($errorInfo[0]);
            return array("found" => true, "errorInfo" => $errorInfo, "resultSet" => $result);
        } catch (\PDOException $e /* Exception $e */) {
            $pdo->rollback();
            return array("found" => false, "errorInfo" => $e->getMessage());
        }
    }

    /**
     * @author Okan CIRAN
     * @ info_users_products_services tablosunda name sutununda daha önce oluşturulmuş mu? 
     * @version v 1.0 24.06.2016
     * @param array | null $args
     * @return array
     * @throws \PDOException
     */
    public function haveRecords($params = array()) {
        try {
            $pdo = $this->slimApp->getServiceManager()->get('oracleConnectFactory');
            $addSql = " AND a.deleted =0  ";
            if (isset($params['id'])) {
                $addSql .= " AND a.id != " . intval($params['id']);
            }
            $sql = " 
            SELECT  
                a.unspsc_codes_id AS name , 
                a.unspsc_codes_id AS value , 
                LOWER(a.unspsc_codes_id) = LOWER('" . $params['unspsc_codes_id'] . "') AS control,
                CONCAT(a.unspsc_codes_id, ' daha önce kayıt edilmiş. Lütfen Kontrol Ediniz !!!' ) AS message                             
            FROM info_users_products_services a             
            WHERE a.firm_id = " . intval($params['firm_id']) . "
                AND a.unspsc_codes_id =  " . intval($params['unspsc_codes_id']) . " 
                AND a.active = 0 
                AND a.deleted = 0     
                " . $addSql . "                  
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
     * @ info_users_products_services tablosundan parametre olarak  gelen id kaydını aktifliğini 1 = pasif yapar. !!
     * @version v 1.0  24.06.2016
     * @param type $params
     * @return array
     * @throws \PDOException
     */
    public function makePassive($params = array()) {
        try {
            $pdo = $this->slimApp->getServiceManager()->get('oracleConnectFactory');
            //$pdo->beginTransaction();
            $statement = $pdo->prepare(" 
                UPDATE info_users_products_services
                SET                         
                    c_date =  timezone('Europe/Istanbul'::text, ('now'::text)::timestamp(0) with time zone) ,      
                    deleted = 1 ,
                    active = 1                    
                WHERE id = :id");
            $statement->bindValue(':id', $params['id'], \PDO::PARAM_INT);
            $update = $statement->execute();
            $afterRows = $statement->rowCount();
            $errorInfo = $statement->errorInfo();
            if ($errorInfo[0] != "00000" && $errorInfo[1] != NULL && $errorInfo[2] != NULL)
                throw new \PDOException($errorInfo[0]);
            //$pdo->commit();
            return array("found" => true, "errorInfo" => $errorInfo, "affectedRowsCount" => $afterRows);
        } catch (\PDOException $e /* Exception $e */) {
            //$pdo->rollback();
            return array("found" => false, "errorInfo" => $e->getMessage());
        }
    }

    /**
     * @author Okan CIRAN
     * @ info_users_products_services tablosuna yeni bir kayıt oluşturur.  !!
     * @version v 1.0  24.06.2016
     * @param array | null $args
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
                $getFirm = InfoFirmProfile :: getFirmIdsForNetworkKey(array('network_key' => $params['network_key']));
                if (\Utill\Dal\Helper::haveRecord($getFirm)) {
                    $getFirmId = $getFirm ['resultSet'][0]['firm_id'];

                    $kontrol = $this->haveRecords(array('firm_id' => $getFirmId, 'unspsc_codes_id' => $params['unspsc_codes_id'],));
                    if (!\Utill\Dal\Helper::haveRecord($kontrol)) {
                        $operationIdValue = -1;
                        $operationId = SysOperationTypes::getTypeIdToGoOperationId(
                                        array('parent_id' => 3, 'main_group' => 3, 'sub_grup_id' => 46, 'type_id' => 1,));
                        if (\Utill\Dal\Helper::haveRecord($operationId)) {
                            $operationIdValue = $operationId ['resultSet'][0]['id'];
                        }

                        $ConsultantId = 1001;
                        $getConsultant = SysOsbConsultants::getConsultantIdForTableName(array('table_name' => 'info_users_products_services', 'operation_type_id' => $operationIdValue));
                        if (\Utill\Dal\Helper::haveRecord($getConsultant)) {
                            $ConsultantId = $getConsultant ['resultSet'][0]['consultant_id'];
                        }

                        $profilePublic = 0;
                        if ((isset($params['profile_public']) && $params['profile_public'] != "")) {
                            $profilePublic = $params['profile_public'];
                        }

                        $languageId = NULL;
                        $languageIdValue = 647;
                        if ((isset($params['language_code']) && $params['language_code'] != "")) {
                            $languageId = SysLanguage::getLanguageId(array('language_code' => $params['language_code']));
                            if (\Utill\Dal\Helper::haveRecord($languageId)) {
                                $languageIdValue = $languageId ['resultSet'][0]['id'];
                            }
                        }

                        $sql = " 
                        INSERT INTO info_users_products_services(
                            firm_id, 
                            consultant_id,
                            operation_type_id,
                            language_id,
                            op_user_id, 
                            profile_public,
                            act_parent_id,
                            unspsc_codes_id
                            )
                        VALUES (
                            :firm_id, 
                            " . intval($ConsultantId) . ",
                            " . intval($operationIdValue) . ",                       
                            " . intval($languageIdValue) . ",
                            " . intval($opUserIdValue) . ",
                            " . intval($profilePublic) . ",                            
                            (SELECT last_value FROM info_users_products_services_id_seq),                           
                            :unspsc_codes_id                            
                             )";
                        $statement = $pdo->prepare($sql);
                        $statement->bindValue(':firm_id', $getFirmId, \PDO::PARAM_INT);
                        $statement->bindValue(':unspsc_codes_id', $params['unspsc_codes_id'], \PDO::PARAM_INT);
                        //  echo debugPDO($sql, $params);
                        $result = $statement->execute();
                        $insertID = $pdo->lastInsertId('info_users_products_services_id_seq');
                        $errorInfo = $statement->errorInfo();
                        if ($errorInfo[0] != "00000" && $errorInfo[1] != NULL && $errorInfo[2] != NULL)
                            throw new \PDOException($errorInfo[0]);

                        $xjobs = ActProcessConfirm::insert(array(
                                    'op_user_id' => intval($opUserIdValue),
                                    'operation_type_id' => intval($operationIdValue),
                                    'table_column_id' => intval($insertID),
                                    'cons_id' => intval($ConsultantId),
                                    'preferred_language_id' => intval($languageIdValue),
                                        )
                        );
                        if ($xjobs['errorInfo'][0] != "00000" && $xjobs['errorInfo'][1] != NULL && $xjobs['errorInfo'][2] != NULL)
                            throw new \PDOException($xjobs['errorInfo']);

                        $pdo->commit();
                        return array("found" => true, "errorInfo" => $errorInfo, "lastInsertId" => $insertID);
                    } else {
                        // 23505  unique_violation
                        $errorInfo = '23505';
                        $errorInfoColumn = 'unspsc_codes_id';
                        $pdo->rollback();
                        // $result = $kontrol;
                        return array("found" => false, "errorInfo" => $errorInfo, "resultSet" => '', "errorInfoColumn" => $errorInfoColumn);
                    }
                } else {
                    $errorInfo = '23502';   // 23502  not_null_violation
                    $errorInfoColumn = 'firm_id';
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
     * info_users_products_services tablosuna parametre olarak gelen id deki kaydın bilgilerini günceller   !!
     * @version v 1.0  24.06.2016
     * @param array | null $args
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
                $kontrol = $this->haveRecords($params);
                if (\Utill\Dal\Helper::haveRecord($kontrol)) {
                    $this->makePassive(array('id' => $params['id']));
                    $operationIdValue = -2;
                    $operationId = SysOperationTypes::getTypeIdToGoOperationId(
                                    array('parent_id' => 3, 'main_group' => 3, 'sub_grup_id' => 46, 'type_id' => 2,));
                    if (\Utill\Dal\Helper::haveRecord($operationId)) {
                        $operationIdValue = $operationId ['resultSet'][0]['id'];
                    }
                    $languageId = NULL;
                    $languageIdValue = 647;
                    if ((isset($params['language_code']) && $params['language_code'] != "")) {
                        $languageId = SysLanguage::getLanguageId(array('language_code' => $params['language_code']));
                        if (\Utill\Dal\Helper::haveRecord($languageId)) {
                            $languageIdValue = $languageId ['resultSet'][0]['id'];
                        }
                    }

                    $profilePublic = 0;
                    if ((isset($params['profile_public']) && $params['profile_public'] != "")) {
                        $profilePublic = $params['profile_public'];
                    }
                    $active = 0;
                    if ((isset($params['active']) && $params['active'] != "")) {
                        $active = $params['active'];
                    }

                    $statement_act_insert = $pdo->prepare(" 
                 INSERT INTO info_users_products_services(
                            firm_id, 
                            consultant_id,
                            operation_type_id,
                            language_id,
                            op_user_id, 
                            profile_public,
                            act_parent_id,
                            active,
                            
                            unspsc_codes_id,                            
                        )
                        SELECT  
                            firm_id,
                            consultant_id, 
                            " . intval($operationIdValue) . ",
                            " . intval($languageIdValue) . ",
                            " . intval($opUserIdValue) . ",
                            " . intval($profilePublic) . ",  
                            act_parent_id,
                            " . intval($active) . ",  
                            
                           
                            " . intval($params['unspsc_codes_id']) . " AS unspsc_codes_id                            
                        FROM info_users_products_services 
                        WHERE id =  " . intval($params['id']) . " 
                        ");
                    $insert_act_insert = $statement_act_insert->execute();
                    $affectedRows = $statement_act_insert->rowCount();
                    $insertID = $pdo->lastInsertId('info_users_products_services_id_seq');
                    $errorInfo = $insert_act_insert->errorInfo();
                    if ($errorInfo[0] != "00000" && $errorInfo[1] != NULL && $errorInfo[2] != NULL)
                        throw new \PDOException($errorInfo[0]);

                    /*
                     * ufak bir trik var. 
                     * işlem update oldugunda update işlemini yapan kişinin dil bilgisini kullanıcaz. 
                     * ancak delete işlemi oldugunda delete işlemini yapan user in dil bilgisini değil 
                     * silinen kaydı yapan kişinin dil bilgisini alıcaz.
                     */
                    $consIdAndLanguageId = SysOperationTypes::getConsIdAndLanguageId(
                                    array('operation_type_id' =>$operationIdValue, 'id' => $params['id'],));
                    if (\Utill\Dal\Helper::haveRecord($consIdAndLanguageId)) {
                        $ConsultantId = $consIdAndLanguageId ['resultSet'][0]['consultant_id'];
                        // $languageIdValue = $consIdAndLanguageId ['resultSet'][0]['language_id'];                       
                    }

                    $xjobs = ActProcessConfirm::insert(array(
                                'op_user_id' => intval($opUserIdValue), // işlemi yapan user
                                'operation_type_id' => intval($operationIdValue), // operasyon 
                                'table_column_id' => intval($insertID), // işlem yapılan tablo id si
                                'cons_id' => intval($ConsultantId), // atanmış olan danısman 
                                'preferred_language_id' => intval($languageIdValue), // dil bilgisi
                                    )
                    );

                    if ($xjobs['errorInfo'][0] != "00000" && $xjobs['errorInfo'][1] != NULL && $xjobs['errorInfo'][2] != NULL)
                        throw new \PDOException($xjobs['errorInfo']);
                    $pdo->commit();
                    return array("found" => true, "errorInfo" => $errorInfo, "affectedRowsCount" => $affectedRows);
                } else {
                    // 23505  unique_violation
                    $errorInfo = '23505';
                    $errorInfoColumn = 'unspsc_codes_id';
                    $pdo->rollback();
                    $result = $kontrol;
                    return array("found" => false, "errorInfo" => $errorInfo, "resultSet" => '');
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
     * @ Gridi doldurmak için info_users_products_services tablosundan kayıtları döndürür !!
     * @version v 1.0  24.06.2016
     * @param array | null $args
     * @return array
     * @throws \PDOException
     */
    public function fillGrid($params = array()) {
        if (isset($params['page']) && $params['page'] != "" && isset($params['rows']) && $params['rows'] != "") {
            $offset = ((intval($params['page']) - 1) * intval($params['rows']));
            $limit = intval($params['rows']);
        } else {
            $limit = 10;
            $offset = 0;
        }

        $sortArr = array();
        $orderArr = array();
        if (isset($params['sort']) && v['sort'] != "") {
            $sort = trim($params['sort']);
            $sortArr = explode(",", $sort);
            if (count($sortArr) === 1)
                $sort = trim($args['sort']);
        } else {
            $sort = "iud.name,iud.surname ";
        }

        if (isset($params['order']) && $params['order'] != "") {
            $order = trim($args['order']);
            $orderArr = explode(",", $order);
            if (count($orderArr) === 1)
                $order = trim($params['order']);
        } else {
            $order = "ASC";
        }
        $languageId = NULL;
        $languageIdValue = 647;
        if ((isset($params['language_code']) && $params['language_code'] != "")) {
            $languageId = SysLanguage::getLanguageId(array('language_code' => $params['language_code']));
            if (\Utill\Dal\Helper::haveRecord($languageId)) {
                $languageIdValue = $languageId ['resultSet'][0]['id'];
            }
        }

        try {
            $pdo = $this->slimApp->getServiceManager()->get('oracleConnectFactory');
            $sql = "                      
                    SELECT 
                        a.id,
                        iud.name,
                        iud.surname,
                        iud.auth_email,
			a.unspsc_codes_id,
			COALESCE(NULLIF(sucx.unspsc_name, ''), suc.unspsc_name_eng) AS unspsc_name,
			suc.unspsc_name_eng,
			a.s_date,
                        a.c_date,
                        a.profile_public,
                        COALESCE(NULLIF(sd19x.description, ''), sd19.description_eng) AS state_profile_public,
                        a.operation_type_id,
                        COALESCE(NULLIF(opx.operation_name, ''), op.operation_name_eng) AS operation_name,
			a.act_parent_id,
                        COALESCE(NULLIF(lx.id, NULL), 385) AS language_id,
		        COALESCE(NULLIF(lx.language, ''), 'en') AS language_name,
                        a.active,
                        COALESCE(NULLIF(sd16x.description, ''), sd16.description_eng) AS state_active,
                        a.deleted,
			COALESCE(NULLIF(sd15x.description, ''), sd15.description_eng) AS state_deleted,
                        a.op_user_id,
                        u.username AS op_user_name,
			a.consultant_id, 
			uc.username AS consultant,
			a.consultant_confirm_type_id, 
			a.confirm_id,
                        a.cons_allow_id,
                        COALESCE(NULLIF(sd14x.description, ''), sd14.description_eng) AS cons_allow,
                        iu.network_key AS unpk
                    FROM info_users_products_services a                    
                    INNER JOIN info_users_detail iud ON iud.root_id = a.user_id AND iud.active = 0 AND iud.deleted = 0 AND iud.language_parent_id =0
                    INNER JOIN sys_language l ON l.id = iud.language_id AND l.deleted =0 AND l.active =0
                    LEFT JOIN sys_language lx ON lx.id = " . intval($languageIdValue) . " AND lx.deleted =0 AND lx.active =0
                    INNER JOIN info_users iu ON iu.id = a.user_id 

		    INNER JOIN sys_operation_types op ON op.id = a.operation_type_id AND op.deleted =0 AND op.active =0 AND op.language_parent_id =0
                    LEFT JOIN sys_operation_types opx ON (opx.id = op.id OR opx.language_parent_id = op.id) and opx.language_id =lx.id  AND opx.deleted =0 AND opx.active =0
                    
		    INNER JOIN sys_specific_definitions sd14 ON sd14.main_group = 14 AND a.cons_allow_id = sd14.first_group AND sd14.deleted =0 AND sd14.active =0 AND sd14.language_parent_id =0
		    INNER JOIN sys_specific_definitions sd15 ON sd15.main_group = 15 AND sd15.first_group= a.deleted AND sd15.deleted =0 AND sd15.active =0 AND sd15.language_parent_id =0
		    INNER JOIN sys_specific_definitions sd16 ON sd16.main_group = 16 AND sd16.first_group= a.active AND sd16.deleted = 0 AND sd16.active = 0 AND sd16.language_parent_id =0
		    INNER JOIN sys_specific_definitions sd19 ON sd19.main_group = 19 AND sd19.first_group= a.profile_public AND sd19.deleted = 0 AND sd19.active = 0 AND sd19.language_parent_id =0
                  
                    LEFT JOIN sys_specific_definitions sd14x ON sd14x.language_id = lx.id AND (sd14x.id = sd14.id OR sd14x.language_parent_id = sd14.id) AND sd14x.deleted =0 AND sd14x.active =0
                    LEFT JOIN sys_specific_definitions sd15x ON sd15x.language_id =lx.id AND (sd15x.id = sd15.id OR sd15x.language_parent_id = sd15.id) AND sd15x.deleted =0 AND sd15x.active =0 
                    LEFT JOIN sys_specific_definitions sd16x ON sd16x.language_id = lx.id AND (sd16x.id = sd16.id OR sd16x.language_parent_id = sd16.id) AND sd16x.deleted = 0 AND sd16x.active = 0
                    LEFT JOIN sys_specific_definitions sd19x ON sd19x.language_id = lx.id AND (sd19x.id = sd19.id OR sd19x.language_parent_id = sd19.id) AND sd19x.deleted = 0 AND sd19x.active = 0
                    
		    INNER JOIN sys_unspsc_codes suc ON suc.id = a.unspsc_codes_id AND suc.active = 0 AND suc.deleted = 0 AND suc.language_id =l.id AND suc.language_parent_id =0 
		    LEFT JOIN sys_unspsc_codes sucx ON (sucx.id = suc.id OR sucx.language_parent_id = suc.id) AND sucx.active = 0 AND sucx.deleted = 0 AND sucx.language_id =lx.id 		    
		    INNER JOIN info_users u ON u.id = a.op_user_id
		    INNER JOIN info_users uc ON uc.id = a.consultant_id
                    WHERE a.deleted =0  
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
     * @ Gridi doldurmak için info_users_products_services tablosundan çekilen kayıtlarının kaç tane olduğunu döndürür   !!
     * @version v 1.0  24.06.2016
     * @param array | null $args
     * @return array
     * @throws \PDOException
     */
    public function fillGridRowTotalCount($params = array()) {
        try {
            $pdo = $this->slimApp->getServiceManager()->get('oracleConnectFactory');
            $whereSQL = " WHERE a.deleted =0 ";

            $sql = "
                SELECT 
                    COUNT(a.id) AS COUNT
                FROM info_users_products_services a                    
                INNER JOIN info_users_detail iud ON iud.root_id = a.user_id AND iud.active = 0 AND iud.deleted = 0 AND iud.language_parent_id =0
                INNER JOIN sys_language l ON l.id = iud.language_id AND l.deleted =0 AND l.active =0                
                INNER JOIN info_users iu ON iu.id = a.user_id 
                INNER JOIN sys_operation_types op ON op.id = a.operation_type_id AND op.deleted =0 AND op.active =0 AND op.language_parent_id =0                
                INNER JOIN sys_specific_definitions sd14 ON sd14.main_group = 14 AND a.cons_allow_id = sd14.first_group AND sd14.deleted =0 AND sd14.active =0 AND sd14.language_parent_id =0
                INNER JOIN sys_specific_definitions sd15 ON sd15.main_group = 15 AND sd15.first_group= a.deleted AND sd15.deleted =0 AND sd15.active =0 AND sd15.language_parent_id =0
                INNER JOIN sys_specific_definitions sd16 ON sd16.main_group = 16 AND sd16.first_group= a.active AND sd16.deleted = 0 AND sd16.active = 0 AND sd16.language_parent_id =0
                INNER JOIN sys_specific_definitions sd19 ON sd19.main_group = 19 AND sd19.first_group= a.profile_public AND sd19.deleted = 0 AND sd19.active = 0 AND sd19.language_parent_id =0
                INNER JOIN sys_unspsc_codes suc ON suc.id = a.unspsc_codes_id AND suc.active = 0 AND suc.deleted = 0 AND suc.language_id =l.id AND suc.language_parent_id =0                 
                INNER JOIN info_users u ON u.id = a.op_user_id
                INNER JOIN info_users uc ON uc.id = a.consultant_id
                " . $whereSQL . "'
                ";
            $statement = $pdo->prepare($sql);
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
     * delete olayında önce kaydın active özelliğini pasif e olarak değiştiriyoruz. 
     * daha sonra deleted= 1 ve active = 1 olan kaydı oluşturuyor. 
     * böylece tablo içerisinde loglama mekanizması için gerekli olan kayıt oluşuyor.
     * @version 24.06.2016 
     * @param type $id
     * @param type $params
     * @return array
     * @throws PDOException
     */
    public function deletedAct($params = array()) {
        try {
            $pdo = $this->slimApp->getServiceManager()->get('oracleConnectFactory');
            $pdo->beginTransaction();
            $opUserId = InfoUsers::getUserId(array('pk' => $params['pk']));
            if (\Utill\Dal\Helper::haveRecord($opUserId)) {
                $opUserIdValue = $opUserId ['resultSet'][0]['user_id'];
                $this->makePassive(array('id' => $params['id']));
                $operationIdValue = -3;
                $operationId = SysOperationTypes::getTypeIdToGoOperationId(
                                array('parent_id' => 3, 'main_group' => 3, 'sub_grup_id' => 46, 'type_id' => 3,));
                if (\Utill\Dal\Helper::haveRecord($operationId)) {
                    $operationIdValue = $operationId ['resultSet'][0]['id'];
                }
                $sql = "                
                  INSERT INTO info_users_products_services(
                            firm_id, 
                            consultant_id,
                            operation_type_id,
                            language_id,
                            op_user_id, 
                            profile_public,
                            act_parent_id,                         
                            
                            unspsc_codes_id,                            
                            
                            consultant_confirm_type_id,
                            confirm_id,                         
                            cons_allow_id,
                            language_parent_id,
                            active,
                            deleted 
                        )
                        SELECT  
                            firm_id,
                            consultant_id,                            
                            " . intval($operationIdValue) . ",    
                            language_id,    
                            " . intval($opUserIdValue) . ",
                            profile_public,
                            act_parent_id,
                            
                            unspsc_codes_id,                            
                         
                            consultant_confirm_type_id,
                            confirm_id,                        
                            cons_allow_id,
                            language_parent_id,
                            1,
                            1                            
                        FROM info_users_products_services 
                        WHERE id =  " . intval($params['id']) . " 
                        ";
                $statement_act_insert = $pdo->prepare($sql);
                //  echo debugPDO($sql, $params);
                $insert_act_insert = $statement_act_insert->execute();
                $affectedRows = $statementInsert->rowCount();
                $insertID = $pdo->lastInsertId('info_users_products_services_id_seq');
                /*
                 * ufak bir trik var. 
                 * işlem update oldugunda update işlemini yapan kişinin dil bilgisini kullanıcaz. 
                 * ancak delete işlemi oldugunda delete işlemini yapan user in dil bilgisini değil 
                 * silinen kaydı yapan kişinin dil bilgisini alıcaz.
                 */
                $consIdAndLanguageId = SysOperationTypes::getConsIdAndLanguageId(
                                array('operation_type_id' =>$operationIdValue, 'id' => $params['id'],));
                if (\Utill\Dal\Helper::haveRecord($consIdAndLanguageId)) {
                    $ConsultantId = $consIdAndLanguageId ['resultSet'][0]['consultant_id'];
                    $languageIdValue = $consIdAndLanguageId ['resultSet'][0]['language_id'];                       
                }

                $xjobs = ActProcessConfirm::insert(array(
                            'op_user_id' => intval($opUserIdValue), // işlemi yapan user
                            'operation_type_id' => intval($operationIdValue), // operasyon 
                            'table_column_id' => intval($insertID), // işlem yapılan tablo id si
                            'cons_id' => intval($ConsultantId), // atanmış olan danısman 
                            'preferred_language_id' => intval($languageIdValue), // dil bilgisi
                                )
                );

                if ($xjobs['errorInfo'][0] != "00000" && $xjobs['errorInfo'][1] != NULL && $xjobs['errorInfo'][2] != NULL)
                    throw new \PDOException($xjobs['errorInfo']);
                $pdo->commit();
                return array("found" => true, "errorInfo" => $errorInfo, "affectedRowsCount" => $affectedRows);
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
     * @ npk lı userın danısman tarafından onaylanmış kayıtlarını döndürür !!
     * @version v 1.0  24.06.2016
     * @param array | null $args
     * @return array
     * @throws \PDOException
     */
    public function fillUserProductsServicesNpk($params = array()) {
        try {
            $pdo = $this->slimApp->getServiceManager()->get('oracleConnectFactory');
            $userId = InfoUsers::getUserId(array('pk' => $params['pk']));
            if (\Utill\Dal\Helper::haveRecord($userId)) {
                //  $opUserIdValue = $userId ['resultSet'][0]['user_id'];    
                $getUserIdValue = NULL;
                $getUser = InfoUsers:: getUserIdsForNetworkKey(array('network_key' => $params['network_key']));
                if (\Utill\Dal\Helper::haveRecord($getUser)) {
                    $getUserIdValue = $getUser ['resultSet'][0]['user_id'];
                    $languageId = NULL;
                    $languageIdValue = 647;
                    if ((isset($params['language_code']) && $params['language_code'] != "")) {
                        $languageId = SysLanguage::getLanguageId(array('language_code' => $params['language_code']));
                        if (\Utill\Dal\Helper::haveRecord($languageId)) {
                            $languageIdValue = $languageId ['resultSet'][0]['id'];
                        }
                    }

                    $sql = " 
                     SELECT 
                        a.id,
                        iu.network_key as unpk,
			a.unspsc_codes_id,
			COALESCE(NULLIF(sucx.unspsc_name, ''), suc.unspsc_name_eng) AS unspsc_name,
			suc.unspsc_name_eng,
			a.act_parent_id,
                        COALESCE(NULLIF(lx.id, NULL), 385) AS language_id,
		        COALESCE(NULLIF(lx.language, ''), 'en') AS language_name
                    FROM info_users_products_services a
                    INNER JOIN info_users_detail iud ON iud.root_id = a.user_id AND iud.active = 0 AND iud.deleted = 0 AND iud.language_parent_id =0
                    INNER JOIN sys_language l ON l.id = iud.language_id AND l.deleted =0 AND l.active =0
                    LEFT JOIN sys_language lx ON lx.id = " . intval($languageIdValue) . " AND lx.deleted =0 AND lx.active =0
                    INNER JOIN info_users iu ON iu.id = a.user_id 
		    INNER JOIN sys_unspsc_codes suc ON suc.id = a.unspsc_codes_id AND suc.active = 0 AND suc.deleted = 0 AND suc.language_id =l.id AND suc.language_parent_id =0 
		    LEFT JOIN sys_unspsc_codes sucx ON (sucx.id = suc.id OR sucx.language_parent_id = suc.id) AND sucx.active = 0 AND sucx.deleted = 0 AND sucx.language_id =lx.id
		    WHERE 
                        a.user_id = " . intval($getUserIdValue) . " AND
                        a.cons_allow_id =2 AND 
			a.profile_public = 0 
		    ORDER BY unspsc_name
                ";
                    $statement = $pdo->prepare($sql);
                    //echo debugPDO($sql, $params);
                    $statement->execute();
                    $result = $statement->fetchAll(\PDO::FETCH_ASSOC);
                    $errorInfo = $statement->errorInfo();
                    if ($errorInfo[0] != "00000" && $errorInfo[1] != NULL && $errorInfo[2] != NULL)
                        throw new \PDOException($errorInfo[0]);
                    return array("found" => true, "errorInfo" => $errorInfo, "resultSet" => $result);
                } else {
                    $errorInfo = '23502';   // 23502  not_null_violation
                    $errorInfoColumn = 'unpk';
                    $pdo->rollback();
                    return array("found" => false, "errorInfo" => $errorInfo, "resultSet" => '', "errorInfoColumn" => $errorInfoColumn);
                }
            } else {
                $errorInfo = '23502';   // 23502  user_id not_null_violation
                $errorInfoColumn = 'pk';
                return array("found" => false, "errorInfo" => $errorInfo, "resultSet" => '', "errorInfoColumn" => $errorInfoColumn);
            }
        } catch (\PDOException $e /* Exception $e */) {
            return array("found" => false, "errorInfo" => $e->getMessage());
        }
    }

    /**
     * @author Okan CIRAN
     * @ npk lı userin danısman tarafından onaylanmış kayıtların sayısını döndürür !!
     * @version v 1.0  24.06.2016
     * @param array | null $args
     * @return array
     * @throws \PDOException
     */
    public function fillUserProductsServicesNpkRtc($params = array()) {
        try {
            $pdo = $this->slimApp->getServiceManager()->get('oracleConnectFactory');
            $userId = InfoUsers::getUserId(array('pk' => $params['pk']));
            if (\Utill\Dal\Helper::haveRecord($userId)) {
                $opUserIdValue = $userId ['resultSet'][0]['user_id'];
                $getUserIdValue = NULL;
                $getUser = InfoUsers:: getUserIdsForNetworkKey(array('network_key' => $params['network_key']));
                if (\Utill\Dal\Helper::haveRecord($getUser)) {
                    $getUserIdValue = $getUser ['resultSet'][0]['user_id'];

                    $sql = " 
                    SELECT 
                       COUNT(a.id) AS count
                    FROM info_users_products_services a
                    INNER JOIN info_users_detail iud ON iud.root_id = a.user_id AND iud.active = 0 AND iud.deleted = 0 AND iud.language_parent_id =0
                    INNER JOIN sys_language l ON l.id = iud.language_id AND l.deleted =0 AND l.active =0                    
                    INNER JOIN info_users iu ON iu.id = a.user_id 
		    INNER JOIN sys_unspsc_codes suc ON suc.id = a.unspsc_codes_id AND suc.active = 0 AND suc.deleted = 0 AND suc.language_id =l.id AND suc.language_parent_id =0 		    
		    WHERE 
                        a.user_id = " . intval($getUserIdValue) . " AND
                        a.cons_allow_id =2 AND 
			a.profile_public = 0 
		    ORDER BY unspsc_name
                ";
                    $statement = $pdo->prepare($sql);
                    //echo debugPDO($sql, $params);
                    $statement->execute();
                    $result = $statement->fetchAll(\PDO::FETCH_ASSOC);
                    $errorInfo = $statement->errorInfo();
                    if ($errorInfo[0] != "00000" && $errorInfo[1] != NULL && $errorInfo[2] != NULL)
                        throw new \PDOException($errorInfo[0]);
                    return array("found" => true, "errorInfo" => $errorInfo, "resultSet" => $result);
                } else {
                    $errorInfo = '23502';   // 23502  not_null_violation
                    $errorInfoColumn = 'unpk';
                    $pdo->rollback();
                    return array("found" => false, "errorInfo" => $errorInfo, "resultSet" => '', "errorInfoColumn" => $errorInfoColumn);
                }
            } else {
                $errorInfo = '23502';   // 23502  user_id not_null_violation
                $errorInfoColumn = 'pk';
                return array("found" => false, "errorInfo" => $errorInfo, "resultSet" => '', "errorInfoColumn" => $errorInfoColumn);
            }
        } catch (\PDOException $e /* Exception $e */) {
            return array("found" => false, "errorInfo" => $e->getMessage());
        }
    }

    /**
     * @author Okan CIRAN
     * @ quest için npk lı firmanın danısman tarafından onaylanmış kayıtlarını döndürür !!
     * @version v 1.0  24.06.2016
     * @param array | null $args
     * @return array
     * @throws \PDOException
     */
    public function fillUserProductsServicesNpkQuest($params = array()) {
        try {
            $pdo = $this->slimApp->getServiceManager()->get('oracleConnectFactory');
            $getUserIdValue = NULL;
            $getUser = InfoUsers:: getUserIdsForNetworkKey(array('network_key' => $params['network_key']));
            if (\Utill\Dal\Helper::haveRecord($getUser)) {
                $getUserIdValue = $getUser ['resultSet'][0]['user_id'];
                $languageId = NULL;
                $languageIdValue = 647;
                if ((isset($params['language_code']) && $params['language_code'] != "")) {
                    $languageId = SysLanguage::getLanguageId(array('language_code' => $params['language_code']));
                    if (\Utill\Dal\Helper::haveRecord($languageId)) {
                        $languageIdValue = $languageId ['resultSet'][0]['id'];
                    }
                }

                $sql = " 
                     SELECT 
                        a.id,
                        iu.network_key as unpk,
			a.unspsc_codes_id,
			COALESCE(NULLIF(sucx.unspsc_name, ''), suc.unspsc_name_eng) AS unspsc_name,
			suc.unspsc_name_eng,
			a.act_parent_id,
                        COALESCE(NULLIF(lx.id, NULL), 385) AS language_id,
		        COALESCE(NULLIF(lx.language, ''), 'en') AS language_name
                    FROM info_users_products_services a
                    INNER JOIN info_users_detail iud ON iud.root_id = a.user_id AND iud.active = 0 AND iud.deleted = 0 AND iud.language_parent_id =0
                    INNER JOIN sys_language l ON l.id = iud.language_id AND l.deleted =0 AND l.active =0
                    LEFT JOIN sys_language lx ON lx.id = " . intval($languageIdValue) . " AND lx.deleted =0 AND lx.active =0
                    INNER JOIN info_users iu ON iu.id = a.user_id 
		    INNER JOIN sys_unspsc_codes suc ON suc.id = a.unspsc_codes_id AND suc.active = 0 AND suc.deleted = 0 AND suc.language_id =l.id AND suc.language_parent_id =0 
		    LEFT JOIN sys_unspsc_codes sucx ON (sucx.id = suc.id OR sucx.language_parent_id = suc.id) AND sucx.active = 0 AND sucx.deleted = 0 AND sucx.language_id =lx.id
		    WHERE 
                        a.user_id = " . intval($getUserIdValue) . " AND
                        a.cons_allow_id =2 AND 
			a.profile_public = 0 
		    ORDER BY unspsc_name
                ";
                $statement = $pdo->prepare($sql);
                echo debugPDO($sql, $params);
                $statement->execute();
                $result = $statement->fetchAll(\PDO::FETCH_ASSOC);
                $errorInfo = $statement->errorInfo();
                if ($errorInfo[0] != "00000" && $errorInfo[1] != NULL && $errorInfo[2] != NULL)
                    throw new \PDOException($errorInfo[0]);
                return array("found" => true, "errorInfo" => $errorInfo, "resultSet" => $result);
            } else {
                $errorInfo = '23502';   // 23502  not_null_violation
                $errorInfoColumn = 'unpk';
                return array("found" => false, "errorInfo" => $errorInfo, "resultSet" => '', "errorInfoColumn" => $errorInfoColumn);
            }
        } catch (\PDOException $e /* Exception $e */) {
            return array("found" => false, "errorInfo" => $e->getMessage());
        }
    }

    /**
     * @author Okan CIRAN
     * @ Quest için npk lı firmanın danısman tarafından onaylanmış kayıtların sayısını döndürür !!
     * @version v 1.0  24.06.2016
     * @param array | null $args
     * @return array
     * @throws \PDOException
     */
    public function fillUserProductsServicesNpkQuestRtc($params = array()) {
        try {
            $pdo = $this->slimApp->getServiceManager()->get('oracleConnectFactory');
            $getUserIdValue = NULL;
            $getUser = InfoUsers:: getUserIdsForNetworkKey(array('network_key' => $params['network_key']));
            if (\Utill\Dal\Helper::haveRecord($getUser)) {
                $getUserIdValue = $getUser ['resultSet'][0]['user_id'];

                $sql = " 
                    SELECT 
                       COUNT(a.id) AS count
                    FROM info_users_products_services a
                    INNER JOIN info_users_detail iud ON iud.root_id = a.user_id AND iud.active = 0 AND iud.deleted = 0 AND iud.language_parent_id =0
                    INNER JOIN sys_language l ON l.id = iud.language_id AND l.deleted =0 AND l.active =0                    
                    INNER JOIN info_users iu ON iu.id = a.user_id 
		    INNER JOIN sys_unspsc_codes suc ON suc.id = a.unspsc_codes_id AND suc.active = 0 AND suc.deleted = 0 AND suc.language_id =l.id AND suc.language_parent_id =0 		    
		    WHERE 
                        a.user_id = " . intval($getUserIdValue) . " AND
                        a.cons_allow_id =2 AND 
			a.profile_public = 0 
		    ORDER BY unspsc_name
                ";
                $statement = $pdo->prepare($sql);
                //echo debugPDO($sql, $params);
                $statement->execute();
                $result = $statement->fetchAll(\PDO::FETCH_ASSOC);
                $errorInfo = $statement->errorInfo();
                if ($errorInfo[0] != "00000" && $errorInfo[1] != NULL && $errorInfo[2] != NULL)
                    throw new \PDOException($errorInfo[0]);
                return array("found" => true, "errorInfo" => $errorInfo, "resultSet" => $result);
            } else {
                $errorInfo = '23502';   // 23502  not_null_violation
                $errorInfoColumn = 'unpk';
                return array("found" => false, "errorInfo" => $errorInfo, "resultSet" => '', "errorInfoColumn" => $errorInfoColumn);
            }
        } catch (\PDOException $e /* Exception $e */) {
            return array("found" => false, "errorInfo" => $e->getMessage());
        }
    }

}
