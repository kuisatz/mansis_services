<?php
// test commit for branch slim2
require 'vendor/autoload.php';




/*$app = new \Slim\Slim(array(
    'mode' => 'development',
    'debug' => true,
    'log.enabled' => true,
    ));*/

$app = new \Slim\SlimExtended(array(
    'mode' => 'development',
    'debug' => true,
    'log.enabled' => true,
    'log.level' => \Slim\Log::INFO,
    'exceptions.rabbitMQ' => true,
    'exceptions.rabbitMQ.logging' => \Slim\SlimExtended::LOG_RABBITMQ_FILE,
    'exceptions.rabbitMQ.queue.name' => \Slim\SlimExtended::EXCEPTIONS_RABBITMQ_QUEUE_NAME
    ));

/**
 * "Cross-origion resource sharing" kontrolüne izin verilmesi için eklenmiştir
 * @author Okan CIRAN
 * @since 2.10.2015
 */
$res = $app->response();
$res->header('Access-Control-Allow-Origin', '*');
$res->header("Access-Control-Allow-Methods: PUT, GET, POST, DELETE, OPTIONS");

//$app->add(new \Slim\Middleware\MiddlewareTest());
//$app->add(new \Slim\Middleware\MiddlewareHMAC());
//$app->add(new \Slim\Middleware\MiddlewareSecurity());
$app->add(new \Slim\Middleware\MiddlewareBLLManager());
$app->add(new \Slim\Middleware\MiddlewareDalManager());
$app->add(new \Slim\Middleware\MiddlewareServiceManager());
$app->add(new \Slim\Middleware\MiddlewareMQManager());


 

\Slim\Route::setDefaultConditions(array(
    'firstName' => '[a-zA-Z]{3,}',
    'page' => '[0-9]{1,}'
));    

$app->get('/hello/:name/:firstName', function ($name) {
    echo "Hello, $name";
});

$app->post('/hello/:name/:firstName', function ($name) {
    echo "Hello, $name";
});

$app->get("/getDynamicForm_test/", function () use ($app) {
    $app->response()->header("Content-Type", "text/html");
    
    /*use PFBC\Form;
    use PFBC\Element;*/
    
    $options = array("Option #1", "Option #2", "Option #3");
    $form = new \PFBC\Form("form-elements");
    $form->clearValues();
    $form->configure(array(
            "prevent" => array("bootstrap", "jQuery")
    ));
    $form->addElement(new \PFBC\Element\Hidden("form", "form-elements"));
    $form->addElement(new \PFBC\Element\HTML('<legend>Standard</legend>'));
    $form->addElement(new \PFBC\Element\Textbox("Textbox:", "Textbox", array("onclick" => "alert('test alert');",
                                                                        'id' => 'test',
                                                                        'class' => 'okan')));
    $form->addElement(new \PFBC\Element\Password("Password:", "Password"));
    $form->addElement(new \PFBC\Element\File("File:", "File"));
    $form->addElement(new \PFBC\Element\Textarea("Textarea:", "Textarea"));
    $form->addElement(new \PFBC\Element\Select("Select:", "Select", $options));
    $form->addElement(new \PFBC\Element\Radio("Radio Buttons:", "RadioButtons", $options));
    $form->addElement(new \PFBC\Element\Checkbox("Checkboxes:", "Checkboxes", $options));
    echo $form->render(true);
    //echo htmlentities($form->render(true), ENT_QUOTES);

    }
);


/**
 * Okan CIRAN
 * @since 11-09-2014
 */
$app->get("/getOracleConnTest/", function () use ($app) {
    echo ("--getOracleConn_test--");
                
           try {   
               //$pdo = $app->getServiceManager()->get('oracleConnectFactory');
               $pdo = new \PDO('oci:dbname=192.168.10.11/EBA;charset=UTF8;', 
                                'eba', 
                                'eba123');
                print_r($pdo);
                $pdo->beginTransaction();
                $statement = $pdo->prepare(" 
                select sum(a.toplam) toplam ,
                        to_date(a.islemtarihi, 'dd/mm/YYYY') tar,
                        IL,
                        ILCE
                        from faturalar a
                        where  durumid=1 and faturaturid in(1,2,3) and
                        (a.islemtarihi  between to_date('10/04/2018', 'dd/mm/yyyy') AND to_date('17/04/2018', 'dd/mm/yyyy'))
                        GROUP BY to_date(a.islemtarihi, 'dd/mm/YYYY'), IL, ILCE
                       ORDER BY to_date(a.islemtarihi, 'dd/mm/YYYY') desc");
                //Execute our DELETE statement.
                $statement->execute();
                $result = $statement->fetchAll(\PDO::FETCH_ASSOC);
                $errorInfo = $statement->errorInfo();
                //print_r($errorInfo);
                $pdo->commit();
                print_r($result);
                return array("found" => true, "errorInfo" => $errorInfo, "result" => $result);
           
        } catch (\PDOException $e /* Exception $e */) {
            $pdo->rollback();
            return array("found" => false, "errorInfo" => $e->getMessage());
        }
                
                
                
    //$app->getServiceManager()->get('oracleConnectFactory');
    //$app->getServiceManager()->get('pgConnectFactory');
    
    
});



/**
 * Okan CIRAN
 * @since 11-09-2014
 */
$app->get("/getReports_test/", function () use ($app) {

    //zend filter base service test ediliyor
    //$filterChainBaseText = $app->getServiceManager()->get(\Services\Filter\FilterServiceNames::TEXT_BASE_FILTER_NOT_TOLOWER_CASE);
    //echo $filterChainBaseText->filter("deneme   <a  TEST href='test'>");
    //echo $filterChainBaseText->filter("--TAMAMI BÜYÜK HARF--");
    //echo $filterChainBaseText->filter("&&& Ahanda javascript === {{{ }}} !-- Karakterler??");

    // zend filter sql test 
    /*$filterSQLReservedWords = $app->getServiceManager()->get(\Services\Filter\FilterServiceNames::FILTER_SQL_RESERVEDWORDS);
    echo $filterSQLReservedWords->filter('select drop deneme char varchar??cccc');*/

    // Filters are called from service manager
    //$filterHtmlAdvanced = $app->getServiceManager()->get(\Services\Filter\FilterServiceNames::FILTER_HTML_TAGS_ADVANCED);
    //$filterHexadecimalBase = $app->getServiceManager()->get(\Services\Filter\FilterServiceNames::FILTER_HEXADECIMAL_BASE);
    //$filterHexadecimalAdvanced = $app->getServiceManager()->get(\Services\Filter\FilterServiceNames::FILTER_HEXADECIMAL_ADVANCED);
    
    echo ("deneme   ");
    
    $BLL = $app->getBLLManager()->get('reportConfigurationBLL'); 

    /**
     * BLL insert örneği test edildi ,
     * test etmek için yorumu kaldırın
     * Okan CIRAN
     */
    /*$resultArr = $BLL->insert(array('name'=>'zeyn dag new', 
                       'international_code'=>25, 
                       'active'=>1));
    print_r($resultArr);*/
    
    /**
     * BLL update örneği test edildi
     * test etmek için yorumu kaldırın
     * Okan CIRAN
     */
    /*$resultArr = $BLL->update(3, array('name'=>'zeyn zeyn oldu şimdik'));
    print_r($resultArr);*/
    
    /**
     * BLL delete örneği test edildi
     * test etmek için yorumu kaldırın
     * Okan CIRAN
     */
    /*$resultArr = $BLL->delete(2);
    print_r($resultArr);*/
    
    /**
     * BLL gatAll örneği test edildi
     * test etmek için yorumu kaldırın
     * Okan CIRAN
     */
    /*$resultArr = $BLL->getAll();
    print_r($resultArr);*/
    
    /**
     * BLL fillGrid örneği test edildi
     * test etmek için yorumu kaldırın
     * Okan CIRAN
     */
    $resDataGrid = $BLL->fillGrid(array('page'=>$_GET['page'],
                                        'rows'=>$_GET['rows'],
                                        'sort'=>$_GET['sort'],
                                        'order'=>$_GET['order'] ));
    //print_r($resDataGrid);
    
    /**
     * BLL fillGridRowTotalCount örneği test edildi
     * örnek datagrid için total row count döndürüyor
     * Okan CIRAN
     */ 
    $resTotalRowCount = $BLL->fillGridRowTotalCount();

    $flows = array();
    foreach ($resDataGrid as $flow){
        $flows[]  = array(
            "id" => $flow["id"],
            "report_name" => $flow["report_name"],
            "r_date" => $flow["r_date"],
            "user_name" => $flow["user_name"],
            "name" => $flow["name"],
            "surname" => $flow["surname"],
            "company_name" => $flow["company_name"],
            "company_id" => $flow["company_id"],
        );
    }
    
    $app->response()->header("Content-Type", "application/json");
    
    $resultArray = array();
    $resultArray['total'] = $resTotalRowCount[0]['toplam'];
    $resultArray['rows'] = $flows;
    
    /*$app->contentType('application/json');
    $app->halt(302, '{"error":"Something went wrong"}');
    $app->stop();*/
    
    $app->response()->body(json_encode($resultArray));

});




$app->run();