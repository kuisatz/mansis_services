<?php
// test commit for branch slim2
require 'vendor/autoload.php';


use \Services\Filter\Helper\FilterFactoryNames as stripChainers;

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
 * @since 02.10.2015
 */
$res = $app->response();
$res->header('Access-Control-Allow-Origin', '*');
$res->header("Access-Control-Allow-Methods: PUT, GET, POST, DELETE, OPTIONS");

$app->add(new \Slim\Middleware\MiddlewareInsertUpdateDeleteLog());
$app->add(new \Slim\Middleware\MiddlewareHMAC()); 
$app->add(new \Slim\Middleware\MiddlewareSecurity());
$app->add(new \Slim\Middleware\MiddlewareMQManager());
$app->add(new \Slim\Middleware\MiddlewareBLLManager());
$app->add(new \Slim\Middleware\MiddlewareDalManager());
$app->add(new \Slim\Middleware\MiddlewareServiceManager());


$resErrorArrayEksikAlan =   array(
            'found' => false,   
            'errorInfo' => '99999',  ) ; 

   

/**
 *  * Okan CIRAN
 * @since 05.08.2018
 */
$app->get("/pkAccBodyDeffDdList_sysaccbodydeff/", function () use ($app ) {
    $stripper = $app->getServiceManager()->get('filterChainerCustom');
    $stripChainerFactory = new \Services\Filter\Helper\FilterChainerFactory(); 
    $BLL = $app->getBLLManager()->get('sysAccBodyDeffBLL');
    
    $componentType = 'ddslick';
    if (isset($_GET['component_type'])) {
        $componentType = strtolower(trim($_GET['component_type']));
    }
    $headerParams = $app->request()->headers();
    if(!isset($headerParams['X-Public'])) throw new Exception ('rest api "pkAccBodyDeffDdList_sysaccbodydeff" end point, X-Public variable not found');
   // $pk = $headerParams['X-Public'];
    
    $vLanguageCode = 'en';
    if (isset($_GET['language_code'])) {
         $stripper->offsetSet('language_code',$stripChainerFactory->get(stripChainers::FILTER_ONLY_LANGUAGE_CODE,
                                                $app,
                                                $_GET['language_code']));
    }
    $lid = null;
    if (isset($_GET['lid'])) {
         $stripper->offsetSet('lid',$stripChainerFactory->get(stripChainers::FILTER_ONLY_NUMBER_ALLOWED,
                                                $app,
                                                $_GET['lid']));
    }
    $stripper->strip();
    if($stripper->offsetExists('lid')) $lid = $stripper->offsetGet('lid')->getFilterValue();
    if($stripper->offsetExists('language_code')) $vLanguageCode = $stripper->offsetGet('language_code')->getFilterValue();
        
    $resCombobox = $BLL->accBodyDeffDdList(array(                                   
                                    'language_code' => $vLanguageCode,
                                    'LanguageID' => $lid,
                        ));    

    $flows = array(); 
    foreach ($resCombobox as $flow) {
        $flows[] = array(            
            "text" => $flow["name"],
            "value" =>  intval($flow["id"]),
            "selected" => false,
            "description" => $flow["name_eng"],
            "imageSrc"=>"",              
            "attributes" => array( 
                                    "active" => $flow["active"], 
                   
                ),
        );
    }
    $app->response()->header("Content-Type", "application/json");
    $app->response()->body(json_encode($flows));
});

/**
 *  * Okan CIRAN
 * @since 15-08-2018
 */
$app->get("/pkFillAccBodyDeffGridx_sysaccbodydeff/", function () use ($app ) {
    $stripper = $app->getServiceManager()->get('filterChainerCustom');
    $stripChainerFactory = new \Services\Filter\Helper\FilterChainerFactory();
    $BLL = $app->getBLLManager()->get('sysAccBodyDeffBLL');
    $headerParams = $app->request()->headers();
    if (!isset($headerParams['X-Public']))
        throw new Exception('rest api "pkFillAccBodyDeffGridx_sysaccbodydeff" end point, X-Public variable not found');
    $pk = $headerParams['X-Public'];

    $vLanguageCode = 'en';
    if (isset($_GET['language_code'])) {
        $stripper->offsetSet('language_code', $stripChainerFactory->get(stripChainers::FILTER_ONLY_LANGUAGE_CODE, $app, $_GET['language_code']));
    }
    $vAccBodyTypeID= NULL;
    if (isset($_GET['acc_body_type_id'])) {
        $stripper->offsetSet('acc_body_type_id', $stripChainerFactory->get(stripChainers::FILTER_ONLY_NUMBER_ALLOWED,  $app,   $_GET['acc_body_type_id']));
    }
    $vPage = NULL;
    if (isset($_GET['page'])) {
        $stripper->offsetSet('page', $stripChainerFactory->get(stripChainers::FILTER_ONLY_NUMBER_ALLOWED, $app, $_GET['page']));
    }
    $vRows = NULL;
    if (isset($_GET['rows'])) {
        $stripper->offsetSet('rows', $stripChainerFactory->get(stripChainers::FILTER_ONLY_NUMBER_ALLOWED, $app, $_GET['rows']));
    }
    $vSort = NULL;
    if (isset($_GET['sort'])) {
        $stripper->offsetSet('sort', $stripChainerFactory->get(stripChainers::FILTER_PARANOID_LEVEL2, $app, $_GET['sort']));
    }
    $vOrder = NULL;
    if (isset($_GET['order'])) {
        $stripper->offsetSet('order', $stripChainerFactory->get(stripChainers::FILTER_ONLY_ORDER, $app, $_GET['order']));
    }
    $filterRules = null;
    if (isset($_GET['filterRules'])) {
        $stripper->offsetSet('filterRules', $stripChainerFactory->get(stripChainers::FILTER_PARANOID_JASON_LVL1, $app, $_GET['filterRules']));
    } 
    $lid = null;
    if (isset($_GET['lid'])) {
         $stripper->offsetSet('lid',$stripChainerFactory->get(stripChainers::FILTER_ONLY_NUMBER_ALLOWED,
                                                $app,
                                                $_GET['lid']));
    }
    $stripper->strip();
    if($stripper->offsetExists('lid')) $lid = $stripper->offsetGet('lid')->getFilterValue();
    if ($stripper->offsetExists('language_code')) $vLanguageCode = $stripper->offsetGet('language_code')->getFilterValue();    
    if ($stripper->offsetExists('acc_body_type_id'))$vAccBodyTypeID = $stripper->offsetGet('acc_body_type_id')->getFilterValue();
    if ($stripper->offsetExists('page')) { $vPage = $stripper->offsetGet('page')->getFilterValue(); }
    if ($stripper->offsetExists('rows')) { $vRows = $stripper->offsetGet('rows')->getFilterValue(); }
    if ($stripper->offsetExists('sort')) { $vSort = $stripper->offsetGet('sort')->getFilterValue(); }
    if ($stripper->offsetExists('order')) { $vOrder = $stripper->offsetGet('order')->getFilterValue(); }
    if ($stripper->offsetExists('filterRules')) { $filterRules = $stripper->offsetGet('filterRules')->getFilterValue(); } 

    $resDataGrid = $BLL->fillAccBodyDeffGridx(array(
        'language_code' => $vLanguageCode,
        'LanguageID' => $lid,
        'page' => $vPage,
        'rows' => $vRows,
        'sort' => $vSort,
        'order' => $vOrder,
        'AccBodyTypeID' => $vAccBodyTypeID,
        'filterRules' => $filterRules,
        'pk' => $pk,
    ));
   
    $resTotalRowCount = $BLL->fillAccBodyDeffGridxRtl(array(
        'language_code' => $vLanguageCode, 
        'LanguageID' => $lid,
        'AccBodyTypeID' => $vAccBodyTypeID,
        'filterRules' => $filterRules,
        'pk' => $pk,
    ));
    $counts=0;
  
    $menu = array();            
    if (isset($resDataGrid[0]['id'])) {      
        foreach ($resDataGrid as $menu) {
            $menus[] = array(
               "id" => $menu["id"],
                "apid" => intval($menu["apid"]),  
                "name" => html_entity_decode($menu["name"]), 
                "acc_body_type_id" => $menu["acc_body_type_id"], 
                "body_type_name" => html_entity_decode($menu["body_type_name"]),   
                "op_username" => html_entity_decode($menu["op_user_name"]), 
                "state_active" => html_entity_decode($menu["state_active"]),       
                "date_saved" => $menu["date_saved"],
                "date_modified" => $menu["date_modified"],  
                "language_code" => $menu["language_code"],
                "active" => $menu["active"], 
                "op_user_id" => $menu["op_user_id"], 
                "language_id" => $menu["language_id"],
                "language_name" =>html_entity_decode( $menu["language_name"]), 
                 
               
            );
        }
       $counts = $resTotalRowCount[0]['count'];
      } ELSE  $menus = array();       

    $app->response()->header("Content-Type", "application/json");
    $resultArray = array();
    $resultArray['totalCount'] = $counts;
    $resultArray['items'] = $menus;
    $app->response()->body(json_encode($resultArray));
});
 
/**
 *  * Okan CIRAN
 * @since 15-08-2018
 */
$app->get("/fillAccBodyDeffGridx_sysaccbodydeff/", function () use ($app ) {
    $stripper = $app->getServiceManager()->get('filterChainerCustom');
    $stripChainerFactory = new \Services\Filter\Helper\FilterChainerFactory();
    $BLL = $app->getBLLManager()->get('sysAccBodyDeffBLL');
   /* $headerParams = $app->request()->headers();
    if (!isset($headerParams['X-Public']))
        throw new Exception('rest api "fillAccBodyDeffGridx_sysaccbodydeff" end point, X-Public variable not found');
    $pk = $headerParams['X-Public'];
*/
    $vLanguageCode = 'en';
    if (isset($_GET['language_code'])) {
        $stripper->offsetSet('language_code', $stripChainerFactory->get(stripChainers::FILTER_ONLY_LANGUAGE_CODE, $app, $_GET['language_code']));
    }
    $vAccBodyTypeID= NULL;
    if (isset($_GET['acc_body_type_id'])) {
        $stripper->offsetSet('acc_body_type_id', $stripChainerFactory->get(stripChainers::FILTER_ONLY_NUMBER_ALLOWED,  $app,   $_GET['acc_body_type_id']));
    }
    $vPage = NULL;
    if (isset($_GET['page'])) {
        $stripper->offsetSet('page', $stripChainerFactory->get(stripChainers::FILTER_ONLY_NUMBER_ALLOWED, $app, $_GET['page']));
    }
    $vRows = NULL;
    if (isset($_GET['rows'])) {
        $stripper->offsetSet('rows', $stripChainerFactory->get(stripChainers::FILTER_ONLY_NUMBER_ALLOWED, $app, $_GET['rows']));
    }
    $vSort = NULL;
    if (isset($_GET['sort'])) {
        $stripper->offsetSet('sort', $stripChainerFactory->get(stripChainers::FILTER_PARANOID_LEVEL2, $app, $_GET['sort']));
    }
    $vOrder = NULL;
    if (isset($_GET['order'])) {
        $stripper->offsetSet('order', $stripChainerFactory->get(stripChainers::FILTER_ONLY_ORDER, $app, $_GET['order']));
    }
    $filterRules = null;
    if (isset($_GET['filterRules'])) {
        $stripper->offsetSet('filterRules', $stripChainerFactory->get(stripChainers::FILTER_PARANOID_JASON_LVL1, $app, $_GET['filterRules']));
    } 
    $lid = null;
    if (isset($_GET['lid'])) {
         $stripper->offsetSet('lid',$stripChainerFactory->get(stripChainers::FILTER_ONLY_NUMBER_ALLOWED,
                                                $app,
                                                $_GET['lid']));
    }
    $stripper->strip();
    if($stripper->offsetExists('lid')) $lid = $stripper->offsetGet('lid')->getFilterValue();
    if ($stripper->offsetExists('language_code')) $vLanguageCode = $stripper->offsetGet('language_code')->getFilterValue();    
    if ($stripper->offsetExists('acc_body_type_id'))$vAccBodyTypeID = $stripper->offsetGet('acc_body_type_id')->getFilterValue();
    if ($stripper->offsetExists('page')) { $vPage = $stripper->offsetGet('page')->getFilterValue(); }
    if ($stripper->offsetExists('rows')) { $vRows = $stripper->offsetGet('rows')->getFilterValue(); }
    if ($stripper->offsetExists('sort')) { $vSort = $stripper->offsetGet('sort')->getFilterValue(); }
    if ($stripper->offsetExists('order')) { $vOrder = $stripper->offsetGet('order')->getFilterValue(); }
    if ($stripper->offsetExists('filterRules')) { $filterRules = $stripper->offsetGet('filterRules')->getFilterValue(); } 

    $resDataGrid = $BLL->fillAccBodyDeffGridx(array(
        'language_code' => $vLanguageCode,
        'LanguageID' => $lid,
        'page' => $vPage,
        'rows' => $vRows,
        'sort' => $vSort,
        'order' => $vOrder,
        'AccBodyTypeID' => $vAccBodyTypeID,
        'filterRules' => $filterRules,
   
    ));
   
    $resTotalRowCount = $BLL->fillAccBodyDeffGridxRtl(array(
        'language_code' => $vLanguageCode, 
        'LanguageID' => $lid,
        'AccBodyTypeID' => $vAccBodyTypeID,
        'filterRules' => $filterRules,
     
    ));
    $counts=0;
  
    $menu = array();            
    if (isset($resDataGrid[0]['id'])) {      
        foreach ($resDataGrid as $menu) {
            $menus[] = array(
                "id" => $menu["id"],
                "apid" => intval($menu["apid"]),  
                "name" => html_entity_decode($menu["name"]), 
                "acc_body_type_id" => $menu["acc_body_type_id"], 
                "body_type_name" => html_entity_decode($menu["body_type_name"]),   
                "op_username" => html_entity_decode($menu["op_user_name"]), 
                "state_active" => html_entity_decode($menu["state_active"]),       
                "date_saved" => $menu["date_saved"],
                "date_modified" => $menu["date_modified"],  
                "language_code" => $menu["language_code"],
                "active" => $menu["active"], 
                "op_user_id" => $menu["op_user_id"], 
                "language_id" => $menu["language_id"],
                "language_name" =>html_entity_decode( $menu["language_name"]), 
            );
        }
       $counts = $resTotalRowCount[0]['count'];
      } ELSE  $menus = array();       

    $app->response()->header("Content-Type", "application/json");
    $resultArray = array();
    $resultArray['totalCount'] = $counts;
    $resultArray['items'] = $menus;
    $app->response()->body(json_encode($resultArray));
});


/**x
 *  * Okan CIRAN
 * @since 29-03-2016
 */
$app->get("/pkUpdateMakeActiveOrPassive_sysaccbodydeff/", function () use ($app ) { 
    
   
  //Connecting to Redis server on localhost 
   $redis = new Redis(); 
   $redis->connect('127.0.0.1', 6379); 
   $redis->auth('1q2w3e4r'); 
 //  echo "Connection to server sucessfully"; 
   //check whether server is running or not 
 //   echo "Server is running: ".$redis->ping(); 
    $rid =  'testInstance7794f89a-59a3-44f8-b2f8-1e44dc8a6f34';
    $user = $redis->hGetAll($rid);
 
    $user=  trim($user['data']);
    
 //   print_r($user) ; 
    $jsonFilter = json_decode($user, true); 
                    if ($jsonFilter != null) {
                      print_r("<<<<<<<<<<<<<<<<<<<<" );  
                        print_r($jsonFilter ["Id"]);  
                          print_r(">>>>>>>>>>>>>>>>>" );  
    } 
   
   
    
   
  
    
    $stripper = $app->getServiceManager()->get('filterChainerCustom');
    $stripChainerFactory = new \Services\Filter\Helper\FilterChainerFactory();    
    $BLL = $app->getBLLManager()->get('sysAccBodyDeffBLL');
  
    $headerParams = $app->request()->headers();
    $Pk = $headerParams['X-Public'];  
     
    $vId = NULL;
    if (isset($_GET['id'])) {
        $stripper->offsetSet('id', $stripChainerFactory->get(stripChainers::FILTER_ONLY_NUMBER_ALLOWED,
                                                $app,
                                                $_GET['id']));
    } 

    $stripper->strip(); 
    if ($stripper->offsetExists('id')) {$vId = $stripper->offsetGet('id')->getFilterValue(); }
      
    $resData = $BLL->makeActiveOrPassive(array(                  
            'id' => $vId ,    
            'pk' => $Pk,        
            )); 

    $app->response()->header("Content-Type", "application/json"); 
    $app->response()->body(json_encode($resData));
}
); 
 
/**
 *  * Okan CIRAN
 * @since 13-01-2016
 */ 
$app->get("/pkInsertAct_sysaccbodydeff/", function () use ($app ) {
    $stripper = $app->getServiceManager()->get('filterChainerCustom');
    $stripChainerFactory = new \Services\Filter\Helper\FilterChainerFactory(); 
    $BLL = $app->getBLLManager()->get('sysAccBodyDeffBLL');  
    $headerParams = $app->request()->headers();
    if(!isset($headerParams['X-Public'])) throw new Exception ('rest api "pkInsertAct_sysaccbodydeff" end point, X-Public variable not found');    
     $pk =  $headerParams['X-Public'];
      
    $vName = NULL;
    if (isset($_GET['name'])) {
         $stripper->offsetSet('name',$stripChainerFactory->get(stripChainers::FILTER_PARANOID_LEVEL2,
                                                $app,
                                                $_GET['name']));
    }  
    $AccBodyTypeId = NULL;
    if (isset($_GET['acc_body_type_id'])) {
         $stripper->offsetSet('acc_body_type_id',$stripChainerFactory->get(stripChainers::FILTER_ONLY_NUMBER_ALLOWED,
                                                $app,
                                                $_GET['acc_body_type_id']));
    } 
     
    $stripper->strip();
    if($stripper->offsetExists('name')) $vName = $stripper->offsetGet('name')->getFilterValue(); 
    if($stripper->offsetExists('acc_body_type_id')) $AccBodyTypeId = $stripper->offsetGet('acc_body_type_id')->getFilterValue();
          
    $resDataInsert = $BLL->insertAct(array(
            'Name' => $vName,   
            'AccBodyTypeId' => $AccBodyTypeId,  
            'pk' => $pk));
        
    $app->response()->header("Content-Type", "application/json"); 
    $app->response()->body(json_encode($resDataInsert));
    
}
);

/**
 *  * Okan CIRAN
 * @since 13-01-2016
 */ 
$app->get("/pkUpdateAct_sysaccbodydeff/", function () use ($app ) {
    $stripper = $app->getServiceManager()->get('filterChainerCustom');
    $stripChainerFactory = new \Services\Filter\Helper\FilterChainerFactory(); 
    $BLL = $app->getBLLManager()->get('sysAccBodyDeffBLL');  
    $headerParams = $app->request()->headers();
    if(!isset($headerParams['X-Public'])) throw new Exception ('rest api "pkUpdateAct_sysaccbodydeff" end point, X-Public variable not found');    
    $pk = $headerParams['X-Public'];
    
    $vId = NULL;
    if (isset($_GET['id'])) {
         $stripper->offsetSet('id',$stripChainerFactory->get(stripChainers::FILTER_ONLY_NUMBER_ALLOWED,
                                                $app,
                                                $_GET['id']));
    } 
    $vName = NULL;
    if (isset($_GET['name'])) {
         $stripper->offsetSet('name',$stripChainerFactory->get(stripChainers::FILTER_PARANOID_LEVEL2,
                                                $app,
                                                $_GET['name']));
    }  
    $AccBodyTypeId = NULL;
    if (isset($_GET['acc_body_type_id'])) {
         $stripper->offsetSet('acc_body_type_id',$stripChainerFactory->get(stripChainers::FILTER_ONLY_NUMBER_ALLOWED,
                                                $app,
                                                $_GET['acc_body_type_id']));
    } 
     
    $stripper->strip();
    if($stripper->offsetExists('name')) $vName = $stripper->offsetGet('name')->getFilterValue(); 
    if($stripper->offsetExists('acc_body_type_id')) $AccBodyTypeId = $stripper->offsetGet('acc_body_type_id')->getFilterValue();
    if($stripper->offsetExists('id')) $vId = $stripper->offsetGet('id')->getFilterValue();
     
          
    $resDataInsert = $BLL->updateAct(array(
            'Id' => $vId,   
            'Name' => $vName,   
            'AccBodyTypeId' => $AccBodyTypeId,  
            'pk' => $pk));
        
    $app->response()->header("Content-Type", "application/json"); 
    $app->response()->body(json_encode($resDataInsert));
    
}
);
 
/**
 *  * Okan CIRAN
 * @since 13-01-2016
 */
$app->get("/pkDeletedAct_sysaccbodydeff/", function () use ($app ) {
    $stripper = $app->getServiceManager()->get('filterChainerCustom');
    $stripChainerFactory = new \Services\Filter\Helper\FilterChainerFactory();    
    $BLL = $app->getBLLManager()->get('sysAccBodyDeffBLL');   
    $headerParams = $app->request()->headers();
    $Pk = $headerParams['X-Public'];  
    $vId = NULL;
    if (isset($_GET['id'])) {
        $stripper->offsetSet('id', $stripChainerFactory->get(stripChainers::FILTER_ONLY_NUMBER_ALLOWED,
                                                $app,
                                                $_GET['id']));
    } 
    $stripper->strip(); 
    if ($stripper->offsetExists('id')) {$vId = $stripper->offsetGet('id')->getFilterValue(); }  
    $resDataDeleted = $BLL->deletedAct(array(                  
            'id' => $vId ,    
            'pk' => $Pk,        
            ));
    $app->response()->header("Content-Type", "application/json"); 
    $app->response()->body(json_encode($resDataDeleted));
}
); 




$app->run();