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
class InfoFileUpload extends \DAL\DalSlim {

    /**
     * @author Okan CIRAN    
     * @ info_project tablosundan parametre olarak  gelen id kaydını siler. !!
     * @version v 1.0  30.07.2018
     * @param array $params   
     * @return array  
     * @throws \PDOException  
     */
    public function delete($params = array()) {
     
    } 

    /**
     * @author Okan CIRAN
     * @ info_project tablosundaki tüm kayıtları getirir.  !!
     * @version v 1.0  30.07.2018  
     * @param array $params
     * @return array
     * @throws \PDOException 
     */
    public function getAll($params = array()) {
        
    }

    /**
     * @author Okan CIRAN
     * @ info_project tablosuna yeni bir kayıt oluşturur.  !!
     * @version v 1.0  30.07.2018
     * @param type $params
     * @return array
     * @throws \PDOException
     */
    public function insert($params = array()) {
       
    }

    /**
     * @author Okan CIRAN
     * @ info_project tablosunda property_name daha önce kaydedilmiş mi ?  
     * @version v 1.0 13.03.2016
     * @param type $params
     * @return array
     * @throws \PDOException
     */
    public function haveRecords($params = array()) {
     
    }

    /**
     * @author Okan CIRAN
     * info_project tablosuna parametre olarak gelen id deki kaydın bilgilerini günceller   !!
     * @version v 1.0  30.07.2018
     * @param type $params
     * @return array
     * @throws \PDOException
     */
    public function update($params = array()) {
        
    }

    /**
     * @author Okan CIRAN
     * @ Gridi doldurmak için info_project tablosundan kayıtları döndürür !!
     * @version v 1.0  30.07.2018
     * @param array | null $args
     * @return array
     * @throws \PDOException
     */
    public function fillGrid($args = array()) {
     
    }

    /**
     * @author Okan CIRAN
     * @ Gridi doldurmak için info_project tablosundan çekilen kayıtlarının kaç tane olduğunu döndürür   !!
     * @version v 1.0  30.07.2018
     * @param array | null $args
     * @return array
     * @throws \PDOException
     */
    public function fillGridRowTotalCount($params = array()) {
        
    }
    
    /*
     * @author Okan CIRAN
     * @ info_project tablosundan parametre olarak  gelen id kaydın aktifliğini
     *  0(aktif) ise 1 , 1 (pasif) ise 0  yapar. !!
      * @version v 1.0  30.07.2018
     * @param type $params
     * @return array
     * @throws \PDOException
     */
    public function testvasxmlupload($params = array()) {
        try { 
            $pdo = $this->slimApp->getServiceManager()->get('pgConnectFactory');
           // $pdo->beginTransaction();
                $sql = "                 
                  select oki_xml_import('D:/VAS.xml');
                "  ;
                $statement = $pdo->prepare($sql);
                    //  echo debugPDO($sql, $params);
                $update = $statement->execute();
           
                print_r($statement);
                
                
                
            
       //     $xml=simplexml_load_file("D://xampp//htdocs//VAS.xml") or die("Error: Cannot create object");

         //  print_r($xml); 
            
            
                    $sql = "                 
                UPDATE info_project
                SET active = (  SELECT   
                                CASE active
                                    WHEN 0 THEN 1
                                    ELSE 0
                                END activex
                                FROM info_project
                                WHERE id =  
                ),
                op_user_id =  
                WHERE id =  ";
                    $statement = $pdo->prepare($sql);
                    //  echo debugPDO($sql, $params);
                //    $update = $statement->execute();
               //     $afterRows = $statement->rowCount();
                //    $errorInfo = $statement->errorInfo();
              //      if ($errorInfo[0] != "00000" && $errorInfo[1] != NULL && $errorInfo[2] != NULL)
               //         throw new \PDOException($errorInfo[0]);
               
              //  $pdo->commit();
              //  return array("found" => true, "errorInfo" => $errorInfo, "affectedRowsCount" => $afterRows);
            
        } catch (\PDOException $e /* Exception $e */) {
            $pdo->rollback();
            return array("found" => false, "errorInfo" => $e->getMessage());
        }
    }
 
    
}
