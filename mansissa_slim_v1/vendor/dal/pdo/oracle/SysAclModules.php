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
 */
class SysAclModules extends \DAL\DalSlim {

    /**
     * @author Okan CIRAN
     * @ sys_acl_modules tablosundan parametre olarak  gelen id kaydını siler. !!
     * @version v 1.0  26.07.2016
     * @param type $params
     * @return array
     * @throws \PDOException
     */
    public function delete($params = array()) {
        try {
            $pdo = $this->slimApp->getServiceManager()->get('oracleConnectFactory');
            $pdo->beginTransaction();
            $opUserId = InfoUsers::getUserId(array('pk' => $params['pk']));
            if (\Utill\Dal\Helper::haveRecord($opUserId)) {
                $ModuleId = $this -> haveActionRecords(array('id' => $params['id']));
                if (!\Utill\Dal\Helper::haveRecord($ModuleId)) {
                $opUserIdValue = $opUserId ['resultSet'][0]['user_id'];
                $sql = " 
                UPDATE sys_acl_modules
                SET  deleted= 1, active = 1,
                     op_user_id = " . intval($opUserIdValue) . "
                WHERE id = " . intval($params['id'])
                ;
                $statement = $pdo->prepare($sql);
               // echo debugPDO($sql, $params);                
                $update = $statement->execute();
                $afterRows = $statement->rowCount();
                $errorInfo = $statement->errorInfo();
                if ($errorInfo[0] != "00000" && $errorInfo[1] != NULL && $errorInfo[2] != NULL)
                    throw new \PDOException($errorInfo[0]);
                $pdo->commit();
                return array("found" => true, "errorInfo" => $errorInfo, "affectedRowsCount" => $afterRows);
                 } else {
                $errorInfo = '23503';   // 23503  foreign_key_violation
                $errorInfoColumn = 'module_id';
                $pdo->rollback();
                return array("found" =>false, "errorInfo" => $errorInfo, "resultSet" => '', "errorInfoColumn" => $errorInfoColumn);
            }
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
     * @ sys_acl_modules tablosundaki tüm kayıtları getirir.  !!
     * @version v 1.0  26.07.2016    
     * @param type $params
     * @return array
     * @throws \PDOException
     */
    public function getAll($params = array()) {
        try {
            $pdo = $this->slimApp->getServiceManager()->get('oracleConnectFactory');            
            $statement = $pdo->prepare("
                SELECT 
                    a.id,
                    a.name AS name,                        
                    a.c_date AS create_date,                        
                    a.deleted,
                    sd.description AS state_deleted,
                    a.active,
                    sd1.description AS state_active,
                    a.description,
                    a.op_user_id,
                    u.username
                FROM sys_acl_modules a                
                INNER JOIN sys_language l ON l.id = 647 AND l.deleted =0 AND l.active =0                
                INNER JOIN sys_specific_definitions sd ON sd.main_group = 15 AND sd.first_group= a.deleted AND sd.language_id = l.id AND sd.deleted = 0 AND sd.active = 0
                INNER JOIN sys_specific_definitions sd1 ON sd1.main_group = 16 AND sd1.first_group= a.active AND sd1.language_id = l.id AND sd1.deleted = 0 AND sd1.active = 0
                INNER JOIN info_users u ON u.id = a.op_user_id                
                ORDER BY a.name
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
     * @ sys_acl_modules tablosuna yeni bir kayıt oluşturur.  !!
     * @version v 1.0  26.07.2016
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
                $kontrol = $this->haveRecords($params);
                if (!\Utill\Dal\Helper::haveRecord($kontrol)) {
                            
                    $sql = "
                INSERT INTO sys_acl_modules(
                        name, 
                        op_user_id, 
                        description)
                VALUES (
                        '".$params['name']."', 
                        " . intval($opUserIdValue) . ",
                        '".$params['description']."'
                                              )  ";
                    $statement = $pdo->prepare($sql);                    
                    //   echo debugPDO($sql, $params);
                    $result = $statement->execute();
                    $insertID = $pdo->lastInsertId('sys_acl_modules_id_seq');
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
     * sys_acl_modules tablosuna parametre olarak gelen id deki kaydın bilgilerini günceller   !!
     * @version v 1.0  26.07.2016
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
                $kontrol = $this->haveRecords($params);
                if (!\Utill\Dal\Helper::haveRecord($kontrol)) {

                    $sql = "
                UPDATE sys_acl_modules
                SET
                    name = '" . $params['name'] . "',                   
                    op_user_id= " . intval($opUserIdValue) . ",
                    description = '" . $params['description'] . "'
                WHERE id = " . intval($params['id']) . "
                    ";
                    $statement = $pdo->prepare($sql);
                    // echo debugPDO($sql, $params);
                    $update = $statement->execute();
                    $affectedRows = $statement->rowCount();
                    $errorInfo = $statement->errorInfo();
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
     * @ sys_acl_roles tablosunda name sutununda daha önce oluşturulmuş mu? 
     * @version v 1.0  26.07.2016
     * @param type $params
     * @return array
     * @throws \PDOException
     */
    public function haveRecords($params = array()) {
        try {
            $pdo = $this->slimApp->getServiceManager()->get('oracleConnectFactory');
            $addSql = "";
            if (isset($params['id'])) {
                $addSql = " AND id != " . intval($params['id']) . " ";
            }
            $sql = " 
            SELECT  
                name as name , 
                '" . $params['name'] . "' as value , 
                name ='" . $params['name'] . "' as control,
                concat(name , ' daha önce kayıt edilmiş. Lütfen Kontrol Ediniz !!!' ) as message                             
            FROM sys_acl_modules                
            WHERE LOWER(REPLACE(name,' ','')) = LOWER(REPLACE('" . $params['name'] . "',' ',''))"
                    . $addSql . " 
               AND deleted =0   
                               ";
            $statement = $pdo->prepare($sql);
            //  echo debugPDO($sql, $params);
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
     * @ Gridi doldurmak için sys_acl_modules tablosundan kayıtları döndürür !!
     * @version v 1.0  26.07.2016
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
            $sort = "a.name";
        }

        if (isset($args['order']) && $args['order'] != "") {
            $order = trim($args['order']);
            $orderArr = explode(",", $order);
            if (count($orderArr) === 1)
                $order = trim($args['order']);
        } else {
            $order = "ASC";
        } 
                            
        try {
            $pdo = $this->slimApp->getServiceManager()->get('oracleConnectFactory');
            $sql = "                   
                SELECT 
                    a.id,
                    a.name AS name,                        
                    a.c_date AS create_date,                        
                    a.deleted,
                    sd.description AS state_deleted,
                    a.active,
                    sd1.description AS state_active,
                    a.description,
                    a.op_user_id,
                    u.username
                FROM sys_acl_modules a                
                INNER JOIN sys_language l ON l.id = 647 AND l.deleted =0 AND l.active =0                
                INNER JOIN sys_specific_definitions sd ON sd.main_group = 15 AND sd.first_group= a.deleted AND sd.language_id = l.id AND sd.deleted = 0 AND sd.active = 0
                INNER JOIN sys_specific_definitions sd1 ON sd1.main_group = 16 AND sd1.first_group= a.active AND sd1.language_id = l.id AND sd1.deleted = 0 AND sd1.active = 0
                INNER JOIN info_users u ON u.id = a.op_user_id     
                WHERE a.deleted =0  
                  " . $whereSQL . "
                ORDER BY " . $sort . " "
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
     * @ Gridi doldurmak için sys_acl_modules tablosundan çekilen kayıtlarının kaç tane olduğunu döndürür   !!
     * @version v 1.0  26.07.2016
     * @param array | null $args
     * @return array
     * @throws \PDOException
     */
    public function fillGridRowTotalCount($params = array()) {
        try {
            $pdo = $this->slimApp->getServiceManager()->get('oracleConnectFactory');
            $whereSQL = ' WHERE a. deleted =0 ';
            $sql = "
                SELECT 
                    COUNT(a.id) AS COUNT
                FROM sys_acl_modules a
                INNER JOIN sys_language l ON l.id = 647 AND l.deleted =0 AND l.active =0
                INNER JOIN sys_specific_definitions sd ON sd.main_group = 15 AND sd.first_group= a.deleted AND sd.language_id = l.id AND sd.deleted = 0 AND sd.active = 0
                INNER JOIN sys_specific_definitions sd1 ON sd1.main_group = 16 AND sd1.first_group= a.active AND sd1.language_id = l.id AND sd1.deleted = 0 AND sd1.active = 0
                INNER JOIN info_users u ON u.id = a.op_user_id
                " . $whereSQL . "
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
     * @author Okan CIRAN
     * @ combobox doldurmak için sys_acl_modules tablosundan tüm kayıtları döndürür !!
     * @version v 1.0  26.07.2016
     * @param array $params
     * @return array
     * @throws \PDOException
     */
    public function fillComboBoxFullModules($params = array()) {
        try {
            $pdo = $this->slimApp->getServiceManager()->get('oracleConnectFactory');
            $statement = $pdo->prepare("
                SELECT 
                    a.id,
                    a.name AS name,
                    'open' AS state_type,
                    a.active
                FROM sys_acl_modules a
                WHERE
                    a.deleted = 0
                ORDER BY name
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
     * @ tree doldurmak için sys_acl_modules tablosundan tüm kayıtları döndürür !!
      * @version v 1.0  26.07.2016
     * @param array $params
     * @return array
     * @throws \PDOException
     */
    public function fillModulesTree($params = array()) {
        try {
            $pdo = $this->slimApp->getServiceManager()->get('oracleConnectFactory');
            $id = 0;
            if (isset($params['id']) && $params['id'] != "") {
                $id = $params['id'];
            }
            $sql = " 
                SELECT
                    a.id,
                    a.name AS name,
                    'open' AS state_type,
                    a.active
                FROM sys_acl_modules a
                WHERE                    
                    a.deleted = 0
                ORDER BY name
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
     * @ resource bilgilerini döndürür !!
     * filterRules aktif 
     * @version v 1.0  26.07.2016
     * @param array | null $args
     * @return array
     * @throws \PDOException
     */
    public function fillModulesList($params = array()) {
        try {
            $pdo = $this->slimApp->getServiceManager()->get('oracleConnectFactory');
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
                $sort = " a.name";
            }

            if (isset($params['order']) && $params['order'] != "") {
                $order = trim($params['order']);
                $orderArr = explode(",", $order);
                //print_r($orderArr);
                if (count($orderArr) === 1)
                    $order = trim($params['order']);
            } else {
                $order = "ASC";
            }

            $sorguStr = null;
            if ((isset($params['filterRules']) && $params['filterRules'] != "")) {
                $filterRules = trim($params['filterRules']);
                $jsonFilter = json_decode($filterRules, true);

                $sorguExpression = null;
                foreach ($jsonFilter as $std) {
                    if ($std['value'] != null) {
                        switch (trim($std['field'])) {
                            case 'name':
                                $sorguExpression = ' ILIKE \'%' . $std['value'] . '%\' ';
                                $sorguStr.=" AND a.name" . $sorguExpression . ' ';

                                break;
                            case 'description':
                                $sorguExpression = ' ILIKE \'%' . $std['value'] . '%\'  ';
                                $sorguStr.=" AND a.description" . $sorguExpression . ' ';

                                break;
                            case 'state_active':
                                $sorguExpression = ' ILIKE \'%' . $std['value'] . '%\'  ';
                                $sorguStr.=" AND sd1.description" . $sorguExpression . ' ';

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
            
            $sorguStr2 = null;
            if (isset($params['name']) && $params['name'] != "") {
                $sorguStr2 .= " AND a.name Like '%" . $params['name'] . "%'";
            }
            if (isset($params['description']) && $params['description'] != "") {
                $sorguStr2 .= " AND a.description Like '%" . $params['description'] . "%'";
            }
            if (isset($params['active']) && $params['active'] != "") {
                $sorguStr2 .= " AND a.active = " . $params['active'] ;
            }
            
            
            $sql = "                 
		SELECT 
                        a.id,
                        a.name AS name,                        
                        a.c_date AS create_date,                        
                        a.deleted,
                        sd.description AS state_deleted,
                        a.active,
                        sd1.description AS state_active,
                        a.description,
                        a.op_user_id,
                        u.username
                FROM sys_acl_modules a                
                INNER JOIN sys_language l ON l.id = 647 AND l.deleted =0 AND l.active =0 
                INNER JOIN sys_specific_definitions sd ON sd.main_group = 15 AND sd.first_group= a.deleted AND sd.language_id = l.id AND sd.deleted = 0 AND sd.active = 0
                INNER JOIN sys_specific_definitions sd1 ON sd1.main_group = 16 AND sd1.first_group= a.active AND sd1.language_id = l.id AND sd1.deleted = 0 AND sd1.active = 0
                INNER JOIN info_users u ON u.id = a.op_user_id 
                WHERE a.deleted =0 
                " . $sorguStr . "
                " . $sorguStr2 . "
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
            //echo debugPDO($sql, $params);
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
     * @ resource bilgilerinin sayısını döndürür !!
     * filterRules aktif 
     * @version v 1.0  26.07.2016
     * @param array | null $args
     * @return array
     * @throws \PDOException
     */
    public function fillModulesListRtc($params = array()) {
        try {
            $pdo = $this->slimApp->getServiceManager()->get('oracleConnectFactory');
            $sorguStr = null;
            if ((isset($params['filterRules']) && $params['filterRules'] != "")) {
                $filterRules = trim($params['filterRules']);
                $jsonFilter = json_decode($filterRules, true);

                $sorguExpression = null;
                foreach ($jsonFilter as $std) {
                    if ($std['value'] != null) {
                        switch (trim($std['field'])) {
                             case 'name':
                                $sorguExpression = ' ILIKE \'%' . $std['value'] . '%\' ';
                                $sorguStr.=" AND a.name" . $sorguExpression . ' ';

                                break;
                            case 'description':
                                $sorguExpression = ' ILIKE \'%' . $std['value'] . '%\'  ';
                                $sorguStr.=" AND a.description" . $sorguExpression . ' ';

                                break;
                            case 'state_active':
                                $sorguExpression = ' ILIKE \'%' . $std['value'] . '%\'  ';
                                $sorguStr.=" AND sd1.description" . $sorguExpression . ' ';

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
             $sorguStr2 = null;
            if (isset($params['name']) && $params['name'] != "") {
                $sorguStr2 .= " AND a.name Like '%" . $params['name'] . "%'";
            }
            if (isset($params['description']) && $params['description'] != "") {
                $sorguStr2 .= " AND a.description Like '%" . $params['description'] . "%'";
            }
            if (isset($params['active']) && $params['active'] != "") {
                $sorguStr2 .= " AND a.active = " . $params['active'] ;
            }
            $sql = "   
                SELECT COUNT(id) AS count 
                FROM (
                    SELECT id,name,deleted,active,description,state_deleted,state_active
                    FROM (
                        SELECT 
                            a.id,
                            a.name AS name,                        
                            a.c_date AS create_date,                        
                            a.deleted,
                            sd.description AS state_deleted,
                            a.active,
                            sd1.description AS state_active,
                            a.description,
                            a.op_user_id,
                            u.username
                        FROM sys_acl_modules a                
                        INNER JOIN sys_language l ON l.id = 647 AND l.deleted =0 AND l.active =0 
                        INNER JOIN sys_specific_definitions sd ON sd.main_group = 15 AND sd.first_group= a.deleted AND sd.language_id = l.id AND sd.deleted = 0 AND sd.active = 0
                        INNER JOIN sys_specific_definitions sd1 ON sd1.main_group = 16 AND sd1.first_group= a.active AND sd1.language_id = l.id AND sd1.deleted = 0 AND sd1.active = 0
                        INNER JOIN info_users u ON u.id = a.op_user_id 
                        WHERE a.deleted =0 
                        " . $sorguStr . "
                        " . $sorguStr2 . "
                    ) as xtable
                    WHERE deleted =0                  
                ) AS xxTable    
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
     * @ sys_acl_modules tablosundan parametre olarak  gelen id kaydın aktifliğini
     *  0(aktif) ise 1 , 1 (pasif) ise 0  yapar. !!
     * @version v 1.0  26.07.2016
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
                UPDATE sys_acl_modules
                SET active = (  SELECT   
                                CASE active
                                    WHEN 0 THEN 1
                                    ELSE 0
                                END activex
                                FROM sys_acl_modules
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
     * @ sys_acl_modules tablosundan kayıtları döndürür !!
     * @version v 1.0  26.07.2016
     * @param array | null $args
     * @return array
     * @throws \PDOException 
     */
    public function fillModulesDdList($params = array()) {
        try {
            $pdo = $this->slimApp->getServiceManager()->get('oracleConnectFactory');
            $statement = $pdo->prepare("        
               SELECT                    
                    a.id, 	
                    a.name,  
                    a.description,                                    
                    a.active,
                    'open' AS state_type  
	         FROM sys_acl_modules a    
                 WHERE                    
                    a.deleted = 0                    
               ORDER BY a.name 
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
     * @ sys_acl_actions tablosunda module_id li action daha önce kaydedilmiş mi ?  
     * @version v 1.0  26.07.2016
     * @param type $params
     * @return array
     * @throws \PDOException
     */
    public function haveActionRecords($params = array()) {
        try {
            $pdo = $this->slimApp->getServiceManager()->get('oracleConnectFactory');                             
            $sql = " 
               SELECT  
                a.module_id AS name ,             
                a.module_id = " . $params['id'] . " AS control,
                'Bu Modüle Altında Action Kaydı Bulunmakta. Lütfen Kontrol Ediniz !!!' AS message   
            FROM sys_acl_actions  a                          
            WHERE a.module_id = ".$params['id']. "
                AND a.deleted =0    
            LIMIT 1                     
                               ";
            $statement = $pdo->prepare($sql);
           //echo debugPDO($sql, $params);
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
 
                             
                            
    
    
}
