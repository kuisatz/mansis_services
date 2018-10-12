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
$app->get("/pkFillBuybackMatrixGridx_sysbuybackmatrix/", function () use ($app ) {
    $stripper = $app->getServiceManager()->get('filterChainerCustom');
    $stripChainerFactory = new \Services\Filter\Helper\FilterChainerFactory();
    $BLL = $app->getBLLManager()->get('sysBuybackMatrixBLL');
    $headerParams = $app->request()->headers();
    if (!isset($headerParams['X-Public']))
        throw new Exception('rest api "pkFillBuybackMatrixGridx_sysbuybackmatrix" end point, X-Public variable not found');
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

    $resDataGrid = $BLL->fillBuybackMatrixGridx(array(
        'language_code' => $vLanguageCode,
        'LanguageID' => $lid,
        'page' => $vPage,
        'rows' => $vRows,
        'sort' => $vSort,
        'order' => $vOrder,
  
        'filterRules' => $filterRules,
        'pk' => $pk,
    ));
   
   
    $resTotalRowCount = $BLL->fillBuybackMatrixGridxRtl(array(
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
                "contract_type_id" => intval($menu["contract_type_id"]),  
                "contract_name" => html_entity_decode($menu["contract_name"]), 
                "model_id" => $menu["model_id"], 
                "vahicle_description" => html_entity_decode($menu["vahicle_description"]),   
               
                "terrain_id" => $menu["terrain_id"], 
                "terrain_name" => html_entity_decode($menu["terrain_name"]), 
                "month_id" => $menu["month_id"], 
                "month_name" => html_entity_decode($menu["month_name"]), 
                "mileage_id" => $menu["mileage_id"], 
                "mileage_type_name" => html_entity_decode($menu["mileage_type_name"]), 
                "price" => $menu["price"], 
                "comfort_super_id" => $menu["comfort_super_id"], 
                "comfort_super_name" => html_entity_decode($menu["comfort_super_name"]), 
                 "hydraulics" => $menu["hydraulics"], 
                "customer_type_id" => $menu["customer_type_id"], 
                "customer_type_name" => html_entity_decode($menu["customer_type_name"]), 
                "hydraulics_name" => html_entity_decode($menu["hydraulics_name"]), 
        
                
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
$app->get("/pkFillTradebackMatrixGridx_sysbuybackmatrix/", function () use ($app ) {
    $stripper = $app->getServiceManager()->get('filterChainerCustom');
    $stripChainerFactory = new \Services\Filter\Helper\FilterChainerFactory();
    $BLL = $app->getBLLManager()->get('sysBuybackMatrixBLL');
    $headerParams = $app->request()->headers();
    if (!isset($headerParams['X-Public']))
        throw new Exception('rest api "pkFillTradebackMatrixGridx_sysbuybackmatrix" end point, X-Public variable not found');
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

    $resDataGrid = $BLL->fillTradebackMatrixGridx(array(
        'language_code' => $vLanguageCode,
        'LanguageID' => $lid,
        'page' => $vPage,
        'rows' => $vRows,
        'sort' => $vSort,
        'order' => $vOrder,
  
        'filterRules' => $filterRules,
        'pk' => $pk,
    ));
   
    $resTotalRowCount = $BLL->fillTradebackMatrixGridxRtl(array(
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
                "contract_type_id" => intval($menu["contract_type_id"]),  
                "contract_name" => html_entity_decode($menu["contract_name"]), 
                "model_id" => $menu["model_id"], 
                "vahicle_description" => html_entity_decode($menu["vahicle_description"]),   
                
                "terrain_id" => $menu["terrain_id"], 
                "terrain_name" => html_entity_decode($menu["terrain_name"]), 
                "month_id" => $menu["month_id"], 
                "month_name" => html_entity_decode($menu["month_name"]), 
                "mileage_id" => $menu["mileage_id"], 
                "mileage_type_name" => html_entity_decode($menu["mileage_type_name"]), 
                "price" => $menu["price"], 
                "comfort_super_id" => $menu["comfort_super_id"], 
                "comfort_super_name" => html_entity_decode($menu["comfort_super_name"]), 
                "hydraulics" => $menu["hydraulics"], 
                "customer_type_id" => $menu["customer_type_id"], 
                "customer_type_name" => html_entity_decode($menu["customer_type_name"]), 
                 "hydraulics_name" => html_entity_decode($menu["hydraulics_name"]), 
                
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
$app->get("/pkUpdateMakeActiveOrPassive_sysbuybackmatrix/", function () use ($app ) {
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
    $BLL = $app->getBLLManager()->get('sysBuybackMatrixBLL');

    
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
$app->get("/pkInsertBBAct_sysbuybackmatrix/", function () use ($app ) {
    $stripper = $app->getServiceManager()->get('filterChainerCustom');
    $stripChainerFactory = new \Services\Filter\Helper\FilterChainerFactory(); 
    $BLL = $app->getBLLManager()->get('sysBuybackMatrixBLL');  
    $headerParams = $app->request()->headers();
    if(!isset($headerParams['X-Public'])) throw new Exception ('rest api "pkInsertBBAct_sysbuybackmatrix" end point, X-Public variable not found');    
     $pk =  $headerParams['X-Public'];
   //  print_r($pk) ; 
    $contractTypeId = NULL;
    if (isset($_GET['contract_type_id'])) {
         $stripper->offsetSet('contract_type_id',$stripChainerFactory->get(stripChainers::FILTER_ONLY_NUMBER_ALLOWED,
                                                $app,
                                                $_GET['contract_type_id']));
    } 
    $modelId = NULL;
    if (isset($_GET['model_id'])) {
         $stripper->offsetSet('model_id',$stripChainerFactory->get(stripChainers::FILTER_ONLY_NUMBER_ALLOWED,
                                                $app,
                                                $_GET['model_id']));
    } 
    $buybackTypeId = NULL;
    if (isset($_GET['buyback_type_id'])) {
         $stripper->offsetSet('buyback_type_id',$stripChainerFactory->get(stripChainers::FILTER_ONLY_NUMBER_ALLOWED,
                                                $app,
                                                $_GET['buyback_type_id']));
    } 
    $terrainId = NULL;
    if (isset($_GET['terrain_id'])) {
         $stripper->offsetSet('terrain_id',$stripChainerFactory->get(stripChainers::FILTER_ONLY_NUMBER_ALLOWED,
                                                $app,
                                                $_GET['terrain_id']));
    } 
    $monthId = NULL;
    if (isset($_GET['month_id'])) {
         $stripper->offsetSet('month_id',$stripChainerFactory->get(stripChainers::FILTER_ONLY_NUMBER_ALLOWED,
                                                $app,
                                                $_GET['month_id']));
    } 
    $mileageId = NULL;
    if (isset($_GET['mileage_id'])) {
         $stripper->offsetSet('mileage_id',$stripChainerFactory->get(stripChainers::FILTER_ONLY_NUMBER_ALLOWED,
                                                $app,
                                                $_GET['mileage_id']));
    } 
    $price = NULL;
    if (isset($_GET['price'])) {
         $stripper->offsetSet('price',$stripChainerFactory->get(stripChainers::FILTER_PARANOID_LEVEL2,
                                                $app,
                                                $_GET['price']));
    } 
    $ComfortSuperId = NULL;
    if (isset($_GET['comfort_super_id'])) {
         $stripper->offsetSet('comfort_super_id',$stripChainerFactory->get(stripChainers::FILTER_ONLY_NUMBER_ALLOWED,
                                                $app,
                                                $_GET['comfort_super_id']));
    } 
    $Hydraulics = NULL;
    if (isset($_GET['hydraulics'])) {
         $stripper->offsetSet('hydraulics',$stripChainerFactory->get(stripChainers::FILTER_ONLY_NUMBER_ALLOWED,
                                                $app,
                                                $_GET['hydraulics']));
    } 
    $CustomerTypeId = NULL;
    if (isset($_GET['customer_type_id'])) {
         $stripper->offsetSet('customer_type_id',$stripChainerFactory->get(stripChainers::FILTER_ONLY_NUMBER_ALLOWED,
                                                $app,
                                                $_GET['customer_type_id']));
    } 
     
  
    //  &contract_type_id=1,&model_id=2,&buyback_type_id=2,&terrain_id=2,&month_id=23,&mileage_id=23,&price=123      
     
    $stripper->strip();
    if($stripper->offsetExists('contract_type_id')) $contractTypeId = $stripper->offsetGet('contract_type_id')->getFilterValue(); 
    if($stripper->offsetExists('model_id')) $modelId = $stripper->offsetGet('model_id')->getFilterValue();
    if($stripper->offsetExists('buyback_type_id')) $buybackTypeId = $stripper->offsetGet('buyback_type_id')->getFilterValue();
    if($stripper->offsetExists('terrain_id')) $terrainId = $stripper->offsetGet('terrain_id')->getFilterValue();
    if($stripper->offsetExists('month_id')) $monthId = $stripper->offsetGet('month_id')->getFilterValue();
    if($stripper->offsetExists('mileage_id')) $mileageId = $stripper->offsetGet('mileage_id')->getFilterValue();
    if($stripper->offsetExists('price')) $price = $stripper->offsetGet('price')->getFilterValue();
    
    if($stripper->offsetExists('comfort_super_id')) $ComfortSuperId = $stripper->offsetGet('comfort_super_id')->getFilterValue();
    if($stripper->offsetExists('hydraulics')) $Hydraulics = $stripper->offsetGet('hydraulics')->getFilterValue();
    if($stripper->offsetExists('customer_type_id')) $CustomerTypeId = $stripper->offsetGet('customer_type_id')->getFilterValue();
          
     
    $resDataInsert = $BLL->InsertBBAct(array(
            'ContractTypeId' => $contractTypeId,   
            'ModelId' => $modelId,  
            'BuybackTypeId' => $buybackTypeId, 
            'TerrainId' => $terrainId, 
            'MonthId' => $monthId, 
            'MileageId' => $mileageId, 
            'Price' => $price, 
            'ComfortSuperId' => $ComfortSuperId, 
            'Hydraulics' => $Hydraulics, 
            'CustomerTypeId' => $CustomerTypeId, 
            'pk' => $pk));
        
    $app->response()->header("Content-Type", "application/json"); 
    $app->response()->body(json_encode($resDataInsert));
    
}
);
 
/**
 *  * Okan CIRAN
 * @since 15-08-2018
 */ 
$app->get("/pkInsertTBAct_sysbuybackmatrix/", function () use ($app ) {
    $stripper = $app->getServiceManager()->get('filterChainerCustom');
    $stripChainerFactory = new \Services\Filter\Helper\FilterChainerFactory(); 
    $BLL = $app->getBLLManager()->get('sysBuybackMatrixBLL');  
    $headerParams = $app->request()->headers();
    if(!isset($headerParams['X-Public'])) throw new Exception ('rest api "pkInsertAct_sysbuybackmatrix" end point, X-Public variable not found');    
     $pk =  $headerParams['X-Public'];
      
    $contractTypeId = NULL;
    if (isset($_GET['contract_type_id'])) {
         $stripper->offsetSet('contract_type_id',$stripChainerFactory->get(stripChainers::FILTER_ONLY_NUMBER_ALLOWED,
                                                $app,
                                                $_GET['contract_type_id']));
    } 
    $modelId = NULL;
    if (isset($_GET['model_id'])) {
         $stripper->offsetSet('model_id',$stripChainerFactory->get(stripChainers::FILTER_ONLY_NUMBER_ALLOWED,
                                                $app,
                                                $_GET['model_id']));
    } 
    $buybackTypeId = NULL;
    if (isset($_GET['buyback_type_id'])) {
         $stripper->offsetSet('buyback_type_id',$stripChainerFactory->get(stripChainers::FILTER_ONLY_NUMBER_ALLOWED,
                                                $app,
                                                $_GET['buyback_type_id']));
    } 
    $terrainId = NULL;
    if (isset($_GET['terrain_id'])) {
         $stripper->offsetSet('terrain_id',$stripChainerFactory->get(stripChainers::FILTER_ONLY_NUMBER_ALLOWED,
                                                $app,
                                                $_GET['terrain_id']));
    } 
    $monthId = NULL;
    if (isset($_GET['month_id'])) {
         $stripper->offsetSet('month_id',$stripChainerFactory->get(stripChainers::FILTER_ONLY_NUMBER_ALLOWED,
                                                $app,
                                                $_GET['month_id']));
    } 
    $mileageId = NULL;
    if (isset($_GET['mileage_id'])) {
         $stripper->offsetSet('mileage_id',$stripChainerFactory->get(stripChainers::FILTER_ONLY_NUMBER_ALLOWED,
                                                $app,
                                                $_GET['mileage_id']));
    } 
    $price = NULL;
    if (isset($_GET['price'])) {
         $stripper->offsetSet('price',$stripChainerFactory->get(stripChainers::FILTER_PARANOID_LEVEL2,
                                                $app,
                                                $_GET['price']));
    } 
     $ComfortSuperId = NULL;
    if (isset($_GET['comfort_super_id'])) {
         $stripper->offsetSet('comfort_super_id',$stripChainerFactory->get(stripChainers::FILTER_ONLY_NUMBER_ALLOWED,
                                                $app,
                                                $_GET['comfort_super_id']));
    } 
    $Hydraulics = NULL;
    if (isset($_GET['hydraulics'])) {
         $stripper->offsetSet('hydraulics',$stripChainerFactory->get(stripChainers::FILTER_ONLY_NUMBER_ALLOWED,
                                                $app,
                                                $_GET['hydraulics']));
    } 
    $CustomerTypeId = NULL;
    if (isset($_GET['customer_type_id'])) {
         $stripper->offsetSet('customer_type_id',$stripChainerFactory->get(stripChainers::FILTER_ONLY_NUMBER_ALLOWED,
                                                $app,
                                                $_GET['customer_type_id']));
    } 
     
  
    //  &contract_type_id=1,&model_id=2,&buyback_type_id=2,&terrain_id=2,&month_id=23,&mileage_id=23,&price=123      
     
    $stripper->strip();
    if($stripper->offsetExists('contract_type_id')) $contractTypeId = $stripper->offsetGet('contract_type_id')->getFilterValue(); 
    if($stripper->offsetExists('model_id')) $modelId = $stripper->offsetGet('model_id')->getFilterValue();
    if($stripper->offsetExists('buyback_type_id')) $buybackTypeId = $stripper->offsetGet('buyback_type_id')->getFilterValue();
    if($stripper->offsetExists('terrain_id')) $terrainId = $stripper->offsetGet('terrain_id')->getFilterValue();
    if($stripper->offsetExists('month_id')) $monthId = $stripper->offsetGet('month_id')->getFilterValue();
    if($stripper->offsetExists('mileage_id')) $mileageId = $stripper->offsetGet('mileage_id')->getFilterValue();
    if($stripper->offsetExists('price')) $price = $stripper->offsetGet('price')->getFilterValue();
          
    if($stripper->offsetExists('comfort_super_id')) $ComfortSuperId = $stripper->offsetGet('comfort_super_id')->getFilterValue();
    if($stripper->offsetExists('hydraulics')) $Hydraulics = $stripper->offsetGet('hydraulics')->getFilterValue();
    if($stripper->offsetExists('customer_type_id')) $CustomerTypeId = $stripper->offsetGet('customer_type_id')->getFilterValue();
          
     
    $resDataInsert = $BLL->InsertTBAct(array(
            'ContractTypeId' => $contractTypeId,   
            'ModelId' => $modelId,  
            'BuybackTypeId' => $buybackTypeId, 
            'TerrainId' => $terrainId, 
            'MonthId' => $monthId, 
            'MileageId' => $mileageId, 
            'Price' => $price, 
            'ComfortSuperId' => $ComfortSuperId, 
            'Hydraulics' => $Hydraulics, 
            'CustomerTypeId' => $CustomerTypeId, 
            'pk' => $pk));
        
    $app->response()->header("Content-Type", "application/json"); 
    $app->response()->body(json_encode($resDataInsert));
    
}
);

/**
 *  * Okan CIRAN
 * @since 15-08-2018
 */ 
$app->get("/pkUpdateBBAct_sysbuybackmatrix/", function () use ($app ) {
    $stripper = $app->getServiceManager()->get('filterChainerCustom');
    $stripChainerFactory = new \Services\Filter\Helper\FilterChainerFactory(); 
    $BLL = $app->getBLLManager()->get('sysBuybackMatrixBLL');  
    $headerParams = $app->request()->headers();
    if(!isset($headerParams['X-Public'])) throw new Exception ('rest api "pkUpdateAct_sysbuybackmatrix" end point, X-Public variable not found');    
    $pk = $headerParams['X-Public'];
    
    $vId = NULL;
    if (isset($_GET['id'])) {
         $stripper->offsetSet('id',$stripChainerFactory->get(stripChainers::FILTER_ONLY_NUMBER_ALLOWED,
                                                $app,
                                                $_GET['id']));
    } 
    $contractTypeId = NULL;
    if (isset($_GET['contract_type_id'])) {
         $stripper->offsetSet('contract_type_id',$stripChainerFactory->get(stripChainers::FILTER_ONLY_NUMBER_ALLOWED,
                                                $app,
                                                $_GET['contract_type_id']));
    } 
    $modelId = NULL;
    if (isset($_GET['model_id'])) {
         $stripper->offsetSet('model_id',$stripChainerFactory->get(stripChainers::FILTER_ONLY_NUMBER_ALLOWED,
                                                $app,
                                                $_GET['model_id']));
    } 
    $buybackTypeId = NULL;
    if (isset($_GET['buyback_type_id'])) {
         $stripper->offsetSet('buyback_type_id',$stripChainerFactory->get(stripChainers::FILTER_ONLY_NUMBER_ALLOWED,
                                                $app,
                                                $_GET['buyback_type_id']));
    } 
    $terrainId = NULL;
    if (isset($_GET['terrain_id'])) {
         $stripper->offsetSet('terrain_id',$stripChainerFactory->get(stripChainers::FILTER_ONLY_NUMBER_ALLOWED,
                                                $app,
                                                $_GET['terrain_id']));
    } 
    $monthId = NULL;
    if (isset($_GET['month_id'])) {
         $stripper->offsetSet('month_id',$stripChainerFactory->get(stripChainers::FILTER_ONLY_NUMBER_ALLOWED,
                                                $app,
                                                $_GET['month_id']));
    } 
    $mileageId = NULL;
    if (isset($_GET['mileage_id'])) {
         $stripper->offsetSet('mileage_id',$stripChainerFactory->get(stripChainers::FILTER_ONLY_NUMBER_ALLOWED,
                                                $app,
                                                $_GET['mileage_id']));
    } 
    $price = NULL;
    if (isset($_GET['price'])) {
         $stripper->offsetSet('price',$stripChainerFactory->get(stripChainers::FILTER_PARANOID_LEVEL2,
                                                $app,
                                                $_GET['price']));
    } 
     $ComfortSuperId = NULL;
    if (isset($_GET['comfort_super_id'])) {
         $stripper->offsetSet('comfort_super_id',$stripChainerFactory->get(stripChainers::FILTER_ONLY_NUMBER_ALLOWED,
                                                $app,
                                                $_GET['comfort_super_id']));
    } 
    $Hydraulics = NULL;
    if (isset($_GET['hydraulics'])) {
         $stripper->offsetSet('hydraulics',$stripChainerFactory->get(stripChainers::FILTER_ONLY_NUMBER_ALLOWED,
                                                $app,
                                                $_GET['hydraulics']));
    } 
    $CustomerTypeId = NULL;
    if (isset($_GET['customer_type_id'])) {
         $stripper->offsetSet('customer_type_id',$stripChainerFactory->get(stripChainers::FILTER_ONLY_NUMBER_ALLOWED,
                                                $app,
                                                $_GET['customer_type_id']));
    } 
     
  
            
     
    $stripper->strip();
    if($stripper->offsetExists('contract_type_id')) $contractTypeId = $stripper->offsetGet('contract_type_id')->getFilterValue(); 
    if($stripper->offsetExists('model_id')) $modelId = $stripper->offsetGet('model_id')->getFilterValue();
    if($stripper->offsetExists('buyback_type_id')) $buybackTypeId = $stripper->offsetGet('buyback_type_id')->getFilterValue();
    if($stripper->offsetExists('terrain_id')) $terrainId = $stripper->offsetGet('terrain_id')->getFilterValue();
    if($stripper->offsetExists('month_id')) $monthId = $stripper->offsetGet('month_id')->getFilterValue();
    if($stripper->offsetExists('mileage_id')) $mileageId = $stripper->offsetGet('mileage_id')->getFilterValue();
    if($stripper->offsetExists('price')) $price = $stripper->offsetGet('price')->getFilterValue();
    if($stripper->offsetExists('comfort_super_id')) $ComfortSuperId = $stripper->offsetGet('comfort_super_id')->getFilterValue();
    if($stripper->offsetExists('hydraulics')) $Hydraulics = $stripper->offsetGet('hydraulics')->getFilterValue();
    if($stripper->offsetExists('customer_type_id')) $CustomerTypeId = $stripper->offsetGet('customer_type_id')->getFilterValue();
    if($stripper->offsetExists('id')) $vId = $stripper->offsetGet('id')->getFilterValue();
     
          
    $resDataInsert = $BLL->UpdateBBAct(array(
            'Id' => $vId,   
            'ContractTypeId' => $contractTypeId,   
            'ModelId' => $modelId,  
            'BuybackTypeId' => $buybackTypeId, 
            'TerrainId' => $terrainId, 
            'MonthId' => $monthId, 
            'MileageId' => $mileageId, 
            'Price' => $price, 
            'ComfortSuperId' => $ComfortSuperId, 
            'Hydraulics' => $Hydraulics, 
            'CustomerTypeId' => $CustomerTypeId, 
            'pk' => $pk));
        
    $app->response()->header("Content-Type", "application/json"); 
    $app->response()->body(json_encode($resDataInsert));
    
}
);
 
/**
 *  * Okan CIRAN
 * @since 15-08-2018
 */ 
$app->get("/pkUpdateTBAct_sysbuybackmatrix/", function () use ($app ) {
    $stripper = $app->getServiceManager()->get('filterChainerCustom');
    $stripChainerFactory = new \Services\Filter\Helper\FilterChainerFactory(); 
    $BLL = $app->getBLLManager()->get('sysBuybackMatrixBLL');  
    $headerParams = $app->request()->headers();
    if(!isset($headerParams['X-Public'])) throw new Exception ('rest api "pkUpdateAct_sysbuybackmatrix" end point, X-Public variable not found');    
    $pk = $headerParams['X-Public'];
    
    $vId = NULL;
    if (isset($_GET['id'])) {
         $stripper->offsetSet('id',$stripChainerFactory->get(stripChainers::FILTER_ONLY_NUMBER_ALLOWED,
                                                $app,
                                                $_GET['id']));
    } 
    $contractTypeId = NULL;
    if (isset($_GET['contract_type_id'])) {
         $stripper->offsetSet('contract_type_id',$stripChainerFactory->get(stripChainers::FILTER_ONLY_NUMBER_ALLOWED,
                                                $app,
                                                $_GET['contract_type_id']));
    } 
    $modelId = NULL;
    if (isset($_GET['model_id'])) {
         $stripper->offsetSet('model_id',$stripChainerFactory->get(stripChainers::FILTER_ONLY_NUMBER_ALLOWED,
                                                $app,
                                                $_GET['model_id']));
    } 
    $buybackTypeId = NULL;
    if (isset($_GET['buyback_type_id'])) {
         $stripper->offsetSet('buyback_type_id',$stripChainerFactory->get(stripChainers::FILTER_ONLY_NUMBER_ALLOWED,
                                                $app,
                                                $_GET['buyback_type_id']));
    } 
    $terrainId = NULL;
    if (isset($_GET['terrain_id'])) {
         $stripper->offsetSet('terrain_id',$stripChainerFactory->get(stripChainers::FILTER_ONLY_NUMBER_ALLOWED,
                                                $app,
                                                $_GET['terrain_id']));
    } 
    $monthId = NULL;
    if (isset($_GET['month_id'])) {
         $stripper->offsetSet('month_id',$stripChainerFactory->get(stripChainers::FILTER_ONLY_NUMBER_ALLOWED,
                                                $app,
                                                $_GET['month_id']));
    } 
    $mileageId = NULL;
    if (isset($_GET['mileage_id'])) {
         $stripper->offsetSet('mileage_id',$stripChainerFactory->get(stripChainers::FILTER_ONLY_NUMBER_ALLOWED,
                                                $app,
                                                $_GET['mileage_id']));
    } 
    $price = NULL;
    if (isset($_GET['price'])) {
         $stripper->offsetSet('price',$stripChainerFactory->get(stripChainers::FILTER_PARANOID_LEVEL2,
                                                $app,
                                                $_GET['price']));
    } 
     $ComfortSuperId = NULL;
    if (isset($_GET['comfort_super_id'])) {
         $stripper->offsetSet('comfort_super_id',$stripChainerFactory->get(stripChainers::FILTER_ONLY_NUMBER_ALLOWED,
                                                $app,
                                                $_GET['comfort_super_id']));
    } 
    $Hydraulics = NULL;
    if (isset($_GET['hydraulics'])) {
         $stripper->offsetSet('hydraulics',$stripChainerFactory->get(stripChainers::FILTER_ONLY_NUMBER_ALLOWED,
                                                $app,
                                                $_GET['hydraulics']));
    } 
    $CustomerTypeId = NULL;
    if (isset($_GET['customer_type_id'])) {
         $stripper->offsetSet('customer_type_id',$stripChainerFactory->get(stripChainers::FILTER_ONLY_NUMBER_ALLOWED,
                                                $app,
                                                $_GET['customer_type_id']));
    } 
     
  
            
     
    $stripper->strip();
    if($stripper->offsetExists('contract_type_id')) $contractTypeId = $stripper->offsetGet('contract_type_id')->getFilterValue(); 
    if($stripper->offsetExists('model_id')) $modelId = $stripper->offsetGet('model_id')->getFilterValue();
    if($stripper->offsetExists('buyback_type_id')) $buybackTypeId = $stripper->offsetGet('buyback_type_id')->getFilterValue();
    if($stripper->offsetExists('terrain_id')) $terrainId = $stripper->offsetGet('terrain_id')->getFilterValue();
    if($stripper->offsetExists('month_id')) $monthId = $stripper->offsetGet('month_id')->getFilterValue();
    if($stripper->offsetExists('mileage_id')) $mileageId = $stripper->offsetGet('mileage_id')->getFilterValue();
    if($stripper->offsetExists('price')) $price = $stripper->offsetGet('price')->getFilterValue();
     if($stripper->offsetExists('comfort_super_id')) $ComfortSuperId = $stripper->offsetGet('comfort_super_id')->getFilterValue();
    if($stripper->offsetExists('hydraulics')) $Hydraulics = $stripper->offsetGet('hydraulics')->getFilterValue();
    if($stripper->offsetExists('customer_type_id')) $CustomerTypeId = $stripper->offsetGet('customer_type_id')->getFilterValue();
    if($stripper->offsetExists('id')) $vId = $stripper->offsetGet('id')->getFilterValue();
     
          
    $resDataInsert = $BLL->UpdateTBAct(array(
            'Id' => $vId,   
            'ContractTypeId' => $contractTypeId,   
            'ModelId' => $modelId,  
            'BuybackTypeId' => $buybackTypeId, 
            'TerrainId' => $terrainId, 
            'MonthId' => $monthId, 
            'MileageId' => $mileageId, 
            'Price' => $price, 
            'ComfortSuperId' => $ComfortSuperId, 
            'Hydraulics' => $Hydraulics, 
            'CustomerTypeId' => $CustomerTypeId, 
            'pk' => $pk));
        
    $app->response()->header("Content-Type", "application/json"); 
    $app->response()->body(json_encode($resDataInsert));
    
}
);
 
/**
 *  * Okan CIRAN
 * @since 15-08-2018
 */
$app->get("/pkDeletedAct_sysbuybackmatrix/", function () use ($app ) {
    $stripper = $app->getServiceManager()->get('filterChainerCustom');
    $stripChainerFactory = new \Services\Filter\Helper\FilterChainerFactory();    
    $BLL = $app->getBLLManager()->get('sysBuybackMatrixBLL');   
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


/**
 *  * Okan CIRAN
 * @since 15-08-2018
 */
$app->get("/pkFillBBSpecialGridx_sysbuybackmatrix/", function () use ($app ) {
    $stripper = $app->getServiceManager()->get('filterChainerCustom');
    $stripChainerFactory = new \Services\Filter\Helper\FilterChainerFactory();
    $BLL = $app->getBLLManager()->get('sysBuybackMatrixBLL');
    $headerParams = $app->request()->headers();
    if (!isset($headerParams['X-Public']))
        throw new Exception('rest api "pkFillBBSpecialGridx_sysbuybackmatrix" end point, X-Public variable not found');
    $pk = $headerParams['X-Public'];
 
    $filterRules = null;
    if (isset($_GET['filterRules'])) {
        $stripper->offsetSet('filterRules', $stripChainerFactory->get(stripChainers::FILTER_PARANOID_JASON_LVL1, $app, $_GET['filterRules']));
    } 
   
    $TerrainId = NULL;
    if (isset($_GET['terrain_id'])) {
         $stripper->offsetSet('terrain_id',$stripChainerFactory->get(stripChainers::FILTER_ONLY_NUMBER_ALLOWED,
                                                $app,
                                                $_GET['terrain_id']));
    } 
    $ContractTypeId = NULL;
    if (isset($_GET['contract_type_id'])) {
         $stripper->offsetSet('contract_type_id',$stripChainerFactory->get(stripChainers::FILTER_ONLY_NUMBER_ALLOWED,
                                                $app,
                                                $_GET['contract_type_id']));
    } 
    $ModelId = NULL;
    if (isset($_GET['model_id'])) {
         $stripper->offsetSet('model_id',$stripChainerFactory->get(stripChainers::FILTER_ONLY_NUMBER_ALLOWED,
                                                $app,
                                                $_GET['model_id']));
    } 
    $CustomerTypeId= NULL;
    if (isset($_GET['customer_type_id'])) {
         $stripper->offsetSet('customer_type_id',$stripChainerFactory->get(stripChainers::FILTER_ONLY_NUMBER_ALLOWED,
                                                $app,
                                                $_GET['customer_type_id']));
    } 
    // &terrain_id=1&contract_type_id=2&model_id=1&customer_type_id=1 
    $stripper->strip();
    
    if($stripper->offsetExists('terrain_id')) $TerrainId = $stripper->offsetGet('terrain_id')->getFilterValue();
    if($stripper->offsetExists('contract_type_id')) $ContractTypeId = $stripper->offsetGet('contract_type_id')->getFilterValue();
    if($stripper->offsetExists('model_id')) $ModelId = $stripper->offsetGet('model_id')->getFilterValue();
    if($stripper->offsetExists('customer_type_id')) $CustomerTypeId = $stripper->offsetGet('customer_type_id')->getFilterValue();
    

    $resDataGrid = $BLL->fillBBSpecialGridx(array(
        'TerrainId' => $TerrainId,
        'ContractTypeId' => $ContractTypeId,
        'ModelId' => $ModelId,
        'CustomerTypeId' => $CustomerTypeId, 
        'filterRules' => $filterRules,
        'pk' => $pk,
    ));
   
   
    $counts=5;
  
    $menu = array();            
    if (isset($resDataGrid[0]['id'])) {      
        foreach ($resDataGrid as $menu) {
            $menus[] = array( 
                "apid" => intval($menu["apid"]),  
                "mvalue" =>  ($menu["mvalue"]), 
                "37" => ($menu["37"]),
                "38" => ($menu["38"]),
                "39" => ($menu["39"]),
                "40" => ($menu["40"]),
                "41" => ($menu["41"]),
                "42" => ($menu["42"]),
                "43" => ($menu["43"]),
                "44" => ($menu["44"]),
                "45" => ($menu["45"]),
                "46" => ($menu["46"]),  
            );
        }
       
      } ELSE  $menus = array();       

    $app->response()->header("Content-Type", "application/json");
    $resultArray = array();
    $resultArray['totalCount'] = $counts;
    $resultArray['items'] = $menus;
    $app->response()->body(json_encode($resultArray));
});
  


 

$app->run();