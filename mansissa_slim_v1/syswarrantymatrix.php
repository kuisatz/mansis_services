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
$app->get("/pkFillWarrantyMatrixGridx_syswarrantymatrix/", function () use ($app ) {
    $stripper = $app->getServiceManager()->get('filterChainerCustom');
    $stripChainerFactory = new \Services\Filter\Helper\FilterChainerFactory();
    $BLL = $app->getBLLManager()->get('sysWarrantyMatrixBLL');
    $headerParams = $app->request()->headers();
    if (!isset($headerParams['X-Public']))
        throw new Exception('rest api "pkFillWarrantyMatrixGridx_syswarrantymatrix" end point, X-Public variable not found');
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
    $stripper->strip();
    if($stripper->offsetExists('lid')) $lid = $stripper->offsetGet('lid')->getFilterValue();
    if ($stripper->offsetExists('language_code')) $vLanguageCode = $stripper->offsetGet('language_code')->getFilterValue();    
  
    if ($stripper->offsetExists('page')) { $vPage = $stripper->offsetGet('page')->getFilterValue(); }
    if ($stripper->offsetExists('rows')) { $vRows = $stripper->offsetGet('rows')->getFilterValue(); }
    if ($stripper->offsetExists('sort')) { $vSort = $stripper->offsetGet('sort')->getFilterValue(); }
    if ($stripper->offsetExists('order')) { $vOrder = $stripper->offsetGet('order')->getFilterValue(); }
    if ($stripper->offsetExists('filterRules')) { $filterRules = $stripper->offsetGet('filterRules')->getFilterValue(); } 

    $resDataGrid = $BLL->fillWarrantyMatrixGridx(array(
        'language_code' => $vLanguageCode,
        'LanguageID' => $lid,
        'page' => $vPage,
        'rows' => $vRows,
        'sort' => $vSort,
        'order' => $vOrder,
 
        'filterRules' => $filterRules,
        'pk' => $pk,
    ));
  
   
    $resTotalRowCount = $BLL->fillWarrantyMatrixGridxRtl(array(
        'language_code' => $vLanguageCode, 
        'LanguageID' => $lid,
    
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
             
                "vehicle_group_id" => $menu["vehicle_group_id"], 
                "vehicle_group" => html_entity_decode($menu["vehicle_group"]),   
                "vehicle_group_name" => html_entity_decode($menu["vehicle_group_name"]),   
                 
                "vehicle_config_type_id" => $menu["vehicle_config_type_id"], 
                "vehicle_config_name" => html_entity_decode($menu["vehicle_config_name"]),   
                "vehicle_group_id" => $menu["vehicle_group_id"], 
                "vehicle_group_name" => html_entity_decode($menu["vehicle_group_name"]),   
                "warranty_types_id" => $menu["warranty_types_id"], 
                "warranty_type_name" => html_entity_decode($menu["warranty_type_name"]),   
                
                "months1_id" => intval($menu["months1_id"]),  
                "month_value" => intval($menu["month_value"]),  
                "mileages1_id" => intval($menu["mileages1_id"]), 
                "mileages1" => intval($menu["mileages1"]), 
                
                
                "ismaintenance" => intval($menu["ismaintenance"]),  
                "maintenance" => html_entity_decode($menu["maintenance"]), 
              
                 
                "unique_code" => html_entity_decode($menu["unique_code"]), 
                "price_in_euros" => $menu["price_in_euros"], 
                
                
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
 * @since 15-08-2018
 */
$app->get("/pkUpdateMakeActiveOrPassive_syswarrantymatrix/", function () use ($app ) {
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
    $BLL = $app->getBLLManager()->get('sysWarrantyMatrixBLL');

    
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
$app->get("/pkInsertAct_syswarrantymatrix/", function () use ($app ) {
    $stripper = $app->getServiceManager()->get('filterChainerCustom');
    $stripChainerFactory = new \Services\Filter\Helper\FilterChainerFactory(); 
    $BLL = $app->getBLLManager()->get('sysWarrantyMatrixBLL');  
    $headerParams = $app->request()->headers();
    if(!isset($headerParams['X-Public'])) throw new Exception ('rest api "pkInsertAct_syswarrantymatrix" end point, X-Public variable not found');    
     $pk =  $headerParams['X-Public'];
      
    
    $warrantyId = NULL;
    if (isset($_GET['warranty_id'])) {
         $stripper->offsetSet('warranty_id',$stripChainerFactory->get(stripChainers::FILTER_ONLY_NUMBER_ALLOWED,
                                                $app,
                                                $_GET['warranty_id']));
    } 
    $vehicleConfigTypId = NULL;
    if (isset($_GET['vehicle_config_type_id'])) {
         $stripper->offsetSet('vehicle_config_type_id',$stripChainerFactory->get(stripChainers::FILTER_ONLY_NUMBER_ALLOWED,
                                                $app,
                                                $_GET['vehicle_config_type_id']));
    } 
    $months1Id = NULL;
    if (isset($_GET['months1_id'])) {
         $stripper->offsetSet('months1_id',$stripChainerFactory->get(stripChainers::FILTER_ONLY_NUMBER_ALLOWED,
                                                $app,
                                                $_GET['months1_id']));
    } 
    $mileages1Id = NULL;
    if (isset($_GET['mileages1_id'])) {
         $stripper->offsetSet('mileages1_id',$stripChainerFactory->get(stripChainers::FILTER_ONLY_NUMBER_ALLOWED,
                                                $app,
                                                $_GET['mileages1_id']));
    }
    $warrantyTypesId = NULL;
    if (isset($_GET['warranty_types_id'])) {
         $stripper->offsetSet('warranty_types_id',$stripChainerFactory->get(stripChainers::FILTER_ONLY_NUMBER_ALLOWED,
                                                $app,
                                                $_GET['warranty_types_id']));
    }
    $ismaintenance = NULL;
    if (isset($_GET['ismaintenance'])) {
         $stripper->offsetSet('ismaintenance',$stripChainerFactory->get(stripChainers::FILTER_ONLY_NUMBER_ALLOWED,
                                                $app,
                                                $_GET['ismaintenance']));
    }
    $uniqueCode = NULL;
    if (isset($_GET['unique_code'])) {
         $stripper->offsetSet('unique_code',$stripChainerFactory->get(stripChainers::FILTER_PARANOID_LEVEL2,
                                                $app,
                                                $_GET['unique_code']));
    }
    $priceInEuros = NULL;
    if (isset($_GET['price_in_euros'])) {
         $stripper->offsetSet('price_in_euros',$stripChainerFactory->get(stripChainers::FILTER_ONLY_NUMBER_ALLOWED,
                                                $app,
                                                $_GET['price_in_euros']));
    } 
     
     
    $stripper->strip();
    if($stripper->offsetExists('warranty_id')) $warrantyId = $stripper->offsetGet('warranty_id')->getFilterValue(); 
    if($stripper->offsetExists('vehicle_config_type_id')) $vehicleConfigTypId = $stripper->offsetGet('vehicle_config_type_id')->getFilterValue();
    if($stripper->offsetExists('months1_id')) $months1Id = $stripper->offsetGet('months1_id')->getFilterValue();
    if($stripper->offsetExists('mileages1_id')) $mileages1Id = $stripper->offsetGet('mileages1_id')->getFilterValue();
    if($stripper->offsetExists('warranty_types_id')) $warrantyTypesId = $stripper->offsetGet('warranty_types_id')->getFilterValue();
    if($stripper->offsetExists('ismaintenance')) $ismaintenance = $stripper->offsetGet('ismaintenance')->getFilterValue(); 
    if($stripper->offsetExists('unique_code')) $uniqueCode = $stripper->offsetGet('unique_code')->getFilterValue();
    if($stripper->offsetExists('price_in_euros')) $priceInEuros = $stripper->offsetGet('price_in_euros')->getFilterValue();
 
          
    $resDataInsert = $BLL->insertAct(array(
            'WarrantyId' => $warrantyId,   
            'VehicleConfigTypeId' => $vehicleConfigTypId,  
            'Months1Id' => $months1Id,  
            'Mileages1Id' => $mileages1Id,  
            'WarrantyTypesId' => $warrantyTypesId,  
            'Ismaintenance' => $ismaintenance,  
            'UniqueCode' => $uniqueCode,  
            'PriceInEuros' => $priceInEuros,   
            'pk' => $pk));
        
    $app->response()->header("Content-Type", "application/json"); 
    $app->response()->body(json_encode($resDataInsert));
    
}
);

/**
 *  * Okan CIRAN
 * @since 15-08-2018
 */ 
$app->get("/pkUpdateAct_syswarrantymatrix/", function () use ($app ) {
    $stripper = $app->getServiceManager()->get('filterChainerCustom');
    $stripChainerFactory = new \Services\Filter\Helper\FilterChainerFactory(); 
    $BLL = $app->getBLLManager()->get('sysWarrantyMatrixBLL');  
    $headerParams = $app->request()->headers();
    if(!isset($headerParams['X-Public'])) throw new Exception ('rest api "pkUpdateAct_syswarrantymatrix" end point, X-Public variable not found');    
    $pk = $headerParams['X-Public'];
    
    $vId = NULL;
    if (isset($_GET['id'])) {
         $stripper->offsetSet('id',$stripChainerFactory->get(stripChainers::FILTER_ONLY_NUMBER_ALLOWED,
                                                $app,
                                                $_GET['id']));
    } 
   $warrantyId = NULL;
    if (isset($_GET['warranty_id'])) {
         $stripper->offsetSet('warranty_id',$stripChainerFactory->get(stripChainers::FILTER_ONLY_NUMBER_ALLOWED,
                                                $app,
                                                $_GET['warranty_id']));
    } 
    $vehicleConfigTypId = NULL;
    if (isset($_GET['vehicle_config_type_id'])) {
         $stripper->offsetSet('vehicle_config_type_id',$stripChainerFactory->get(stripChainers::FILTER_ONLY_NUMBER_ALLOWED,
                                                $app,
                                                $_GET['vehicle_config_type_id']));
    } 
    $months1Id = NULL;
    if (isset($_GET['months1_id'])) {
         $stripper->offsetSet('months1_id',$stripChainerFactory->get(stripChainers::FILTER_ONLY_NUMBER_ALLOWED,
                                                $app,
                                                $_GET['months1_id']));
    } 
    $mileages1Id = NULL;
    if (isset($_GET['mileages1_id'])) {
         $stripper->offsetSet('mileages1_id',$stripChainerFactory->get(stripChainers::FILTER_ONLY_NUMBER_ALLOWED,
                                                $app,
                                                $_GET['mileages1_id']));
    }
    $warrantyTypesId = NULL;
    if (isset($_GET['warranty_types_id'])) {
         $stripper->offsetSet('warranty_types_id',$stripChainerFactory->get(stripChainers::FILTER_ONLY_NUMBER_ALLOWED,
                                                $app,
                                                $_GET['warranty_types_id']));
    }
    $ismaintenance = NULL;
    if (isset($_GET['ismaintenance'])) {
         $stripper->offsetSet('ismaintenance',$stripChainerFactory->get(stripChainers::FILTER_ONLY_NUMBER_ALLOWED,
                                                $app,
                                                $_GET['ismaintenance']));
    }
    $uniqueCode = NULL;
    if (isset($_GET['unique_code'])) {
         $stripper->offsetSet('unique_code',$stripChainerFactory->get(stripChainers::FILTER_PARANOID_LEVEL2, 
                                                $app,
                                                $_GET['unique_code']));
    }
    $priceInEuros = NULL;
    if (isset($_GET['price_in_euros'])) {
         $stripper->offsetSet('price_in_euros',$stripChainerFactory->get(stripChainers::FILTER_ONLY_NUMBER_ALLOWED,
                                                $app,
                                                $_GET['price_in_euros']));
    } 
     
    $stripper->strip();
    if($stripper->offsetExists('warranty_id')) $warrantyId = $stripper->offsetGet('warranty_id')->getFilterValue(); 
    if($stripper->offsetExists('vehicle_config_type_id')) $vehicleConfigTypId = $stripper->offsetGet('vehicle_config_type_id')->getFilterValue();
    if($stripper->offsetExists('months1_id')) $months1Id = $stripper->offsetGet('months1_id')->getFilterValue();
    if($stripper->offsetExists('mileages1_id')) $mileages1Id = $stripper->offsetGet('mileages1_id')->getFilterValue();
    if($stripper->offsetExists('warranty_types_id')) $warrantyTypesId = $stripper->offsetGet('warranty_types_id')->getFilterValue();
    if($stripper->offsetExists('ismaintenance')) $ismaintenance = $stripper->offsetGet('ismaintenance')->getFilterValue(); 
    if($stripper->offsetExists('unique_code')) $uniqueCode = $stripper->offsetGet('unique_code')->getFilterValue();
    if($stripper->offsetExists('price_in_euros')) $priceInEuros = $stripper->offsetGet('price_in_euros')->getFilterValue();
    if($stripper->offsetExists('id')) $vId = $stripper->offsetGet('id')->getFilterValue();
     
          
    $resDataInsert = $BLL->updateAct(array(
            'Id' => $vId,   
            'WarrantyId' => $warrantyId,   
            'VehicleConfigTypeId' => $vehicleConfigTypId,  
            'Months1Id' => $months1Id,  
            'Mileages1Id' => $mileages1Id,  
            'WarrantyTypesId' => $warrantyTypesId,  
            'Ismaintenance' => $ismaintenance,  
            'UniqueCode' => $uniqueCode,  
            'PriceInEuros' => $priceInEuros,   
            'pk' => $pk));
        
    $app->response()->header("Content-Type", "application/json"); 
    $app->response()->body(json_encode($resDataInsert));
    
}
);
 
/**
 *  * Okan CIRAN
 * @since 15-08-2018
 */
$app->get("/pkDeletedAct_syswarrantymatrix/", function () use ($app ) {
    $stripper = $app->getServiceManager()->get('filterChainerCustom');
    $stripChainerFactory = new \Services\Filter\Helper\FilterChainerFactory();    
    $BLL = $app->getBLLManager()->get('sysWarrantyMatrixBLL');   
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