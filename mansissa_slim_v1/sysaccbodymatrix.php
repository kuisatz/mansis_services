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
 * @since 2.10.2015
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



 
/**
 *  * Okan CIRAN
 * @since 15-08-2018
 */
$app->get("/pkFillBodyMatrixGridx_sysaccbodymatrix/", function () use ($app ) {
    $stripper = $app->getServiceManager()->get('filterChainerCustom');
    $stripChainerFactory = new \Services\Filter\Helper\FilterChainerFactory();
    $BLL = $app->getBLLManager()->get('sysAccBodyMatrixBLL');
    $headerParams = $app->request()->headers();
    if (!isset($headerParams['X-Public']))
        throw new Exception('rest api "pkFillBodyMatrixGridx_sysaccbodymatrix" end point, X-Public variable not found');
    $pk = $headerParams['X-Public'];

    $vLanguageCode = 'en';
    if (isset($_GET['language_code'])) {
        $stripper->offsetSet('language_code', $stripChainerFactory->get(stripChainers::FILTER_ONLY_LANGUAGE_CODE, $app, $_GET['language_code']));
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
    
    $vehicleGtModelsId= NULL;
    if (isset($_GET['vehicle_gt_models_id'])) {
        $stripper->offsetSet('vehicle_gt_models_id', $stripChainerFactory->get(stripChainers::FILTER_ONLY_NUMBER_ALLOWED,  $app,   $_GET['vehicle_gt_models_id']));
    } 
    $supplierID= NULL;
    if (isset($_GET['supplier_id'])) {
        $stripper->offsetSet('supplier_id', $stripChainerFactory->get(stripChainers::FILTER_ONLY_NUMBER_ALLOWED,  $app,   $_GET['supplier_id']));
    }
    $stripper->strip();
    if($stripper->offsetExists('lid')) $lid = $stripper->offsetGet('lid')->getFilterValue();
    if ($stripper->offsetExists('language_code')) $vLanguageCode = $stripper->offsetGet('language_code')->getFilterValue();    
    if ($stripper->offsetExists('page')) { $vPage = $stripper->offsetGet('page')->getFilterValue(); }
    if ($stripper->offsetExists('rows')) { $vRows = $stripper->offsetGet('rows')->getFilterValue(); }
    if ($stripper->offsetExists('sort')) { $vSort = $stripper->offsetGet('sort')->getFilterValue(); }
    if ($stripper->offsetExists('order')) { $vOrder = $stripper->offsetGet('order')->getFilterValue(); }
    if ($stripper->offsetExists('filterRules')) { $filterRules = $stripper->offsetGet('filterRules')->getFilterValue(); } 
    if ($stripper->offsetExists('vehicle_gt_models_id'))$vehicleGtModelsId = $stripper->offsetGet('vehicle_gt_models_id')->getFilterValue(); 
    if ($stripper->offsetExists('supplier_id'))$supplierID = $stripper->offsetGet('supplier_id')->getFilterValue();
 
    $resDataGrid = $BLL->fillBodyMatrixGridx(array(
        'language_code' => $vLanguageCode,
        'LanguageID' => $lid,
        'page' => $vPage,
        'rows' => $vRows,
        'sort' => $vSort,
        'order' => $vOrder, 
        'VehicleGTmodelsID' => $vehicleGtModelsId, 
        'SupplierID' => $supplierID,
        'filterRules' => $filterRules,
        'pk' => $pk,
    ));
 
 
    $resTotalRowCount = $BLL->fillBodyMatrixGridxRtl(array(
        'language_code' => $vLanguageCode, 
        'LanguageID' => $lid, 
        'filterRules' => $filterRules, 
        'VehicleGTmodelsID' => $vehicleGtModelsId, 
        'SupplierID' => $supplierID,
        'pk' => $pk,
    ));
    $counts=0;
 
    $menu = array();            
    if (isset($resDataGrid[0]['id'])) {      
        foreach ($resDataGrid as $menu) {
            $menus[] = array(
                "id" => $menu["id"],
                "apid" => intval($menu["apid"]),  
                "vehicle_gtname" => html_entity_decode($menu["vehicle_gtname"]), 
                "supplier_name" => html_entity_decode($menu["supplier_name"]),   
                "body_deff_name" => html_entity_decode($menu["body_deff_name"]), 
                "body_type_name" => html_entity_decode($menu["body_type_name"]), 
                
                "vehicle_gt_models_id" => $menu["vehicle_gt_models_id"],   
                "supplier_id" => $menu["supplier_id"],   
                "cost" => $menu["cost"],   
                "acc_body_deff_id" => $menu["acc_body_deff_id"],   
                "acc_body_type_id" => $menu["acc_body_type_id"], 
                
                
                
                "op_username" => html_entity_decode($menu["op_user_name"]),
                "active" => $menu["active"],   
                "state_active" => html_entity_decode($menu["state_active"]),       
                "date_saved" => $menu["date_saved"],
                "date_modified" => $menu["date_modified"],  
                "language_code" => $menu["language_code"],
                "language_name" =>html_entity_decode( $menu["language_name"]), 
                "language_id" => $menu["language_id"],
                "op_user_id" => $menu["op_user_id"], 
                
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
 * @since 15-08-2018
 */
$app->get("/pkUpdateMakeActiveOrPassive_sysaccbodymatrix/", function () use ($app ) {
    $RedisConnect = $app->getServiceManager()->get('redisConnectFactory');
 
    $rid = 'testInstance7794f89a-59a3-44f8-b2f8-1e44dc8a6f34';
    $user = $RedisConnect->hGetAll($rid);
    //   echo "Server is running: ".$RedisConnect->ping(); 

 //   print_r($user);
    if (isset($user['data']) && $user['data'] != "") {
        $user = trim($user['data']);

        $jsonFilter = json_decode($user, true);
        if ($jsonFilter != null) {
    //        print_r("<<<<<<<<<<<<<<<<<<<<");
            if (isset($jsonFilter['Id'])) {
           //     print_r($jsonFilter ["Id"]);
            }
    ///        print_r(">>>>>>>>><<<<<<<<<<<");
            if (isset($jsonFilter['RootId'])) {
      //          print_r($jsonFilter ["RootId"]);
            }
      //      print_r(">>>>>>>>>>>>>>>>>");
            if (isset($jsonFilter['RoleId'])) {
       //         print_r($jsonFilter ["RoleId"]);
            }
        //    print_r(">>>>>>>>>>>>>>>>>");
        }
    } else {
       //  print_r("<<<<<<<3ee3>>>>>>>");
         //  print_r("<<<<<<<123>>>>>>>"); 
        ;
    }
 
    $stripper = $app->getServiceManager()->get('filterChainerCustom');
    $stripChainerFactory = new \Services\Filter\Helper\FilterChainerFactory();
    $BLL = $app->getBLLManager()->get('sysAccBodyMatrixBLL');

    
    $RedisConnect = $app->getServiceManager()->get('redisConnectFactory');
      
    $headerParams = $app->request()->headers();
    $Pk = $headerParams['X-Public'];
 //   $user = $RedisConnect->hGetAll($Pk);

    $vId = NULL;
    if (isset($_GET['id'])) {
        $stripper->offsetSet('id', $stripChainerFactory->get(stripChainers::FILTER_ONLY_NUMBER_ALLOWED, $app, $_GET['id']));
    }

    $stripper->strip();
    if ($stripper->offsetExists('id')) {
        $vId = $stripper->offsetGet('id')->getFilterValue();
    }

    $resData = $BLL->makeActiveOrPassive(array(
        'id' => $vId,
        'pk' => $Pk,
    ));

    $app->response()->header("Content-Type", "application/json");
    $app->response()->body(json_encode($resData));
}
);

/**
 *  * Okan CIRAN
 * @since 15-08-2018
 */ 
$app->get("/pkInsertAct_sysaccbodymatrix/", function () use ($app ) {
    $stripper = $app->getServiceManager()->get('filterChainerCustom');
    $stripChainerFactory = new \Services\Filter\Helper\FilterChainerFactory(); 
    $BLL = $app->getBLLManager()->get('sysAccBodyMatrixBLL');  
    $headerParams = $app->request()->headers();
    if(!isset($headerParams['X-Public'])) throw new Exception ('rest api "pkInsertAct_sysaccbodymatrix" end point, X-Public variable not found');    
     $pk =  $headerParams['X-Public'];
       
    $supplierId = NULL;
    if (isset($_GET['supplier_id'])) {
         $stripper->offsetSet('supplier_id',$stripChainerFactory->get(stripChainers::FILTER_ONLY_NUMBER_ALLOWED,
                                                $app,
                                                $_GET['supplier_id']));
    } 
    $vehicleGtModelId = NULL;
    if (isset($_GET['vehicle_gt_models_id'])) {
         $stripper->offsetSet('vehicle_gt_models_id',$stripChainerFactory->get(stripChainers::FILTER_ONLY_NUMBER_ALLOWED,
                                                $app,
                                                $_GET['vehicle_gt_models_id']));
    } 
    $accBodyDeffId = NULL;
    if (isset($_GET['acc_body_deff_id'])) {
         $stripper->offsetSet('acc_body_deff_id',$stripChainerFactory->get(stripChainers::FILTER_ONLY_NUMBER_ALLOWED,
                                                $app,
                                                $_GET['acc_body_deff_id']));
    } 
    $accBodyTypeId = NULL;
    if (isset($_GET['acc_body_type_id'])) {
         $stripper->offsetSet('acc_body_type_id',$stripChainerFactory->get(stripChainers::FILTER_ONLY_NUMBER_ALLOWED,
                                                $app,
                                                $_GET['acc_body_type_id']));
    } 
    $cost = NULL;
    if (isset($_GET['cost'])) {
         $stripper->offsetSet('cost',$stripChainerFactory->get(stripChainers::FILTER_PARANOID_LEVEL2,
                                                $app,
                                                $_GET['cost']));
    } 
     
    $stripper->strip(); 
    if($stripper->offsetExists('supplier_id')) $supplierId = $stripper->offsetGet('supplier_id')->getFilterValue();
    if($stripper->offsetExists('vehicle_gt_models_id')) $vehicleGtModelId = $stripper->offsetGet('vehicle_gt_models_id')->getFilterValue();
    if($stripper->offsetExists('acc_body_deff_id')) $accBodyDeffId = $stripper->offsetGet('acc_body_deff_id')->getFilterValue();
    if($stripper->offsetExists('acc_body_type_id')) $accBodyTypeId = $stripper->offsetGet('acc_body_type_id')->getFilterValue();
    if($stripper->offsetExists('cost')) $cost = $stripper->offsetGet('cost')->getFilterValue();
          
    $resDataInsert = $BLL->insertAct(array(
            'SupplierId' => $supplierId,   
            'VehicleGtModelId' => $vehicleGtModelId,  
            'AccBodyDeffId' => $accBodyDeffId, 
            'AccBodyTypeId' => $accBodyTypeId, 
            'Cost' => $cost,   
            'pk' => $pk));
        
    $app->response()->header("Content-Type", "application/json"); 
    $app->response()->body(json_encode($resDataInsert));
    
}
);

/**
 *  * Okan CIRAN
 * @since 15-08-2018
 */ 
$app->get("/pkUpdateAct_sysaccbodymatrix/", function () use ($app ) {
    $stripper = $app->getServiceManager()->get('filterChainerCustom');
    $stripChainerFactory = new \Services\Filter\Helper\FilterChainerFactory(); 
    $BLL = $app->getBLLManager()->get('sysAccBodyMatrixBLL');  
    $headerParams = $app->request()->headers();
    if(!isset($headerParams['X-Public'])) throw new Exception ('rest api "pkUpdateAct_sysaccbodymatrix" end point, X-Public variable not found');    
    $pk = $headerParams['X-Public'];
    
    $vId = NULL;
    if (isset($_GET['id'])) {
         $stripper->offsetSet('id',$stripChainerFactory->get(stripChainers::FILTER_ONLY_NUMBER_ALLOWED,
                                                $app,
                                                $_GET['id']));
    } 
   $supplierId = NULL;
    if (isset($_GET['supplier_id'])) {
         $stripper->offsetSet('supplier_id',$stripChainerFactory->get(stripChainers::FILTER_ONLY_NUMBER_ALLOWED,
                                                $app,
                                                $_GET['supplier_id']));
    } 
    $vehicleGtModelId = NULL;
    if (isset($_GET['vehicle_gt_models_id'])) {
         $stripper->offsetSet('vehicle_gt_models_id',$stripChainerFactory->get(stripChainers::FILTER_ONLY_NUMBER_ALLOWED,
                                                $app,
                                                $_GET['vehicle_gt_models_id']));
    } 
    $accBodyDeffId = NULL;
    if (isset($_GET['acc_body_deff_id'])) {
         $stripper->offsetSet('acc_body_deff_id',$stripChainerFactory->get(stripChainers::FILTER_ONLY_NUMBER_ALLOWED,
                                                $app,
                                                $_GET['acc_body_deff_id']));
    } 
    $accBodyTypeId = NULL;
    if (isset($_GET['acc_body_type_id'])) {
         $stripper->offsetSet('acc_body_type_id',$stripChainerFactory->get(stripChainers::FILTER_ONLY_NUMBER_ALLOWED,
                                                $app,
                                                $_GET['acc_body_type_id']));
    } 
    $cost = NULL;
    if (isset($_GET['cost'])) {
         $stripper->offsetSet('cost',$stripChainerFactory->get(stripChainers::FILTER_PARANOID_LEVEL2,
                                                $app,
                                                $_GET['cost']));
    } 
     
    $stripper->strip(); 
    if($stripper->offsetExists('supplier_id')) $supplierId = $stripper->offsetGet('supplier_id')->getFilterValue();
    if($stripper->offsetExists('vehicle_gt_models_id')) $vehicleGtModelId = $stripper->offsetGet('vehicle_gt_models_id')->getFilterValue();
    if($stripper->offsetExists('acc_body_deff_id')) $accBodyDeffId = $stripper->offsetGet('acc_body_deff_id')->getFilterValue();
    if($stripper->offsetExists('acc_body_type_id')) $accBodyTypeId = $stripper->offsetGet('acc_body_type_id')->getFilterValue();
    if($stripper->offsetExists('cost')) $cost = $stripper->offsetGet('cost')->getFilterValue(); 
    if($stripper->offsetExists('id')) $vId = $stripper->offsetGet('id')->getFilterValue();
     
          
    $resDataInsert = $BLL->updateAct(array(
            'Id' => $vId,   
            'SupplierId' => $supplierId,   
            'VehicleGtModelId' => $vehicleGtModelId,  
            'AccBodyDeffId' => $accBodyDeffId, 
            'AccBodyTypeId' => $accBodyTypeId, 
            'Cost' => $cost,   
            'pk' => $pk));
        
    $app->response()->header("Content-Type", "application/json"); 
    $app->response()->body(json_encode($resDataInsert));
    
}
);
 
/**
 *  * Okan CIRAN
 * @since 15-08-2018
 */
$app->get("/pkDeletedAct_sysaccbodymatrix/", function () use ($app ) {
    $stripper = $app->getServiceManager()->get('filterChainerCustom');
    $stripChainerFactory = new \Services\Filter\Helper\FilterChainerFactory();    
    $BLL = $app->getBLLManager()->get('sysAccBodyMatrixBLL');   
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