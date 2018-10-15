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
 * @since 11.08.2018
 */
$app->get("/pkVehicleDescriptionsDdList_sysvehicles/", function () use ($app ) {
    $stripper = $app->getServiceManager()->get('filterChainerCustom');
    $stripChainerFactory = new \Services\Filter\Helper\FilterChainerFactory(); 
    $BLL = $app->getBLLManager()->get('sysVehiclesBLL');
    
    $componentType = 'ddslick';
    if (isset($_GET['component_type'])) {
        $componentType = strtolower(trim($_GET['component_type']));
    }
    $headerParams = $app->request()->headers();
    if(!isset($headerParams['X-Public'])) throw new Exception ('rest api "pkVehicleDescriptionsDdList_sysvehicles" end point, X-Public variable not found');
    //$pk = $headerParams['X-Public'];
    
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
    
        
    $resCombobox = $BLL->vehicleDescriptionsDdList(array(                                   
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
 * @since 11.08.2018
 */
$app->get("/pkVehicleFactoryNamesDdList_sysvehicles/", function () use ($app ) {
    $stripper = $app->getServiceManager()->get('filterChainerCustom');
    $stripChainerFactory = new \Services\Filter\Helper\FilterChainerFactory(); 
    $BLL = $app->getBLLManager()->get('sysVehiclesBLL');
    
    $componentType = 'ddslick';
    if (isset($_GET['component_type'])) {
        $componentType = strtolower(trim($_GET['component_type']));
    }
    $headerParams = $app->request()->headers();
    if(!isset($headerParams['X-Public'])) throw new Exception ('rest api "pkVehicleFactoryNamesDdList_sysvehicles" end point, X-Public variable not found');
    //$pk = $headerParams['X-Public'];
    
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
    
        
    $resCombobox = $BLL->vehicleFactoryNamesDdList(array(                                   
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
$app->get("/pkFillVehiclesGridx_sysvehicles/", function () use ($app ) {
    $stripper = $app->getServiceManager()->get('filterChainerCustom');
    $stripChainerFactory = new \Services\Filter\Helper\FilterChainerFactory();
    $BLL = $app->getBLLManager()->get('sysVehiclesBLL');
    $headerParams = $app->request()->headers();
    if (!isset($headerParams['X-Public']))
        throw new Exception('rest api "pkFillVehiclesGridx_sysvehicles" end point, X-Public variable not found');
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
    $VehicleGtModelID = null;
    if (isset($_GET['vehicle_gt_model_id'])) {
         $stripper->offsetSet('vehicle_gt_model_id',$stripChainerFactory->get(stripChainers::FILTER_ONLY_NUMBER_ALLOWED,
                                                $app,
                                                $_GET['vehicle_gt_model_id']));
    }
    
    
    $stripper->strip();
    if($stripper->offsetExists('lid')) $lid = $stripper->offsetGet('lid')->getFilterValue();
    if ($stripper->offsetExists('language_code')) $vLanguageCode = $stripper->offsetGet('language_code')->getFilterValue();    
    if ($stripper->offsetExists('page')) { $vPage = $stripper->offsetGet('page')->getFilterValue(); }
    if ($stripper->offsetExists('vehicle_gt_model_id')) { $VehicleGtModelID = $stripper->offsetGet('vehicle_gt_model_id')->getFilterValue(); }
    if ($stripper->offsetExists('rows')) { $vRows = $stripper->offsetGet('rows')->getFilterValue(); }
    if ($stripper->offsetExists('sort')) { $vSort = $stripper->offsetGet('sort')->getFilterValue(); }
    if ($stripper->offsetExists('order')) { $vOrder = $stripper->offsetGet('order')->getFilterValue(); }
    if ($stripper->offsetExists('filterRules')) { $filterRules = $stripper->offsetGet('filterRules')->getFilterValue(); } 

    $resDataGrid = $BLL->fillVehiclesGridx(array(
        'language_code' => $vLanguageCode,
        'LanguageID' => $lid,
        'page' => $vPage,
        'rows' => $vRows,
        'sort' => $vSort,
        'order' => $vOrder,
        'VehicleGtModelID' => $VehicleGtModelID,
        'filterRules' => $filterRules,
        'pk' => $pk,
    ));
   
    $resTotalRowCount = $BLL->fillVehiclesGridxRtl(array(
        'language_code' => $vLanguageCode, 
        'LanguageID' => $lid,
       'VehicleGtModelID' => $VehicleGtModelID,
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
             
                
                "ckdcbu_type_id" => intval($menu["ckdcbu_type_id"]),  
                "cbuckd_name" => html_entity_decode($menu["cbuckd_name"]),  
                
                "vehicle_gt_model_id" => intval($menu["vehicle_gt_model_id"]),  
                "gt_model_name" => html_entity_decode($menu["gt_model_name"]),  
                
                "model_variant_id" => intval($menu["model_variant_id"]),  
                "variant_name" => html_entity_decode($menu["variant_name"]),  
                
                
                "config_type_id" => intval($menu["config_type_id"]),  
                "config_type_name" => html_entity_decode($menu["config_type_name"]),  
                
                "cap_type_id" => intval($menu["cap_type_id"]),  
                "cap_type_name" => html_entity_decode($menu["cap_type_name"]),  
                
                "vehicle_app_type_id" => intval($menu["vehicle_app_type_id"]),  
                "app_type_name" => html_entity_decode($menu["app_type_name"]),  
                
                "kpnumber_id" => intval($menu["kpnumber_id"]),  
                "kp_name" => html_entity_decode($menu["kp_name"]),  
                
                "btsbto_type_id" => intval($menu["btsbto_type_id"]),  
                "btobts_name" => html_entity_decode($menu["btobts_name"]),  
                
             //   "roadtype_id" => intval($menu["roadtype_id"]),  
             //   "road_type_name" => html_entity_decode($menu["road_type_name"]),  
                
              
                "gfz" => html_entity_decode($menu["gfz"]),  
                
                "factorymodel_name" =>  ($menu["factorymodel_name"]),   
                
                "road_type_name" => html_entity_decode($menu["description"]),   
                
                 "horsepower_id" => intval($menu["horsepower_id"]),  
                "horse_power" => html_entity_decode($menu["horse_power"]),  
                
                  "vehicle_group_types_id" => intval($menu["vehicle_group_types_id"]),  
                "vehicle_group_types_name" => html_entity_decode($menu["vehicle_group_types_name"]),  
                
                  "vehicle_groups_id" => intval($menu["vehicle_groups_id"]),  
                "vehicle_groups_name" => html_entity_decode($menu["vehicle_groups_name"]),  
                 
                
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
$app->get("/pkUpdateMakeActiveOrPassive_sysvehicles/", function () use ($app ) {
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
    $BLL = $app->getBLLManager()->get('sysVehiclesBLL');

    
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
$app->get("/pkInsertAct_sysvehicles/", function () use ($app ) {
    $stripper = $app->getServiceManager()->get('filterChainerCustom');
    $stripChainerFactory = new \Services\Filter\Helper\FilterChainerFactory(); 
    $BLL = $app->getBLLManager()->get('sysVehiclesBLL');  
    $headerParams = $app->request()->headers();
    if(!isset($headerParams['X-Public'])) throw new Exception ('rest api "pkInsertAct_sysvehicles" end point, X-Public variable not found');    
     $pk =  $headerParams['X-Public'];
      
    $description = NULL;
    if (isset($_GET['description'])) {
         $stripper->offsetSet('description',$stripChainerFactory->get(stripChainers::FILTER_PARANOID_LEVEL2,
                                                $app,
                                                $_GET['description']));
    }   
     $factorymodelName = NULL;
    if (isset($_GET['factorymodel_name'])) {
         $stripper->offsetSet('factorymodel_name',$stripChainerFactory->get(stripChainers::FILTER_PARANOID_LEVEL2,
                                                $app,
                                                $_GET['factorymodel_name']));
    }  
    $gfz = NULL;
    if (isset($_GET['gfz'])) {
         $stripper->offsetSet('gfz',$stripChainerFactory->get(stripChainers::FILTER_PARANOID_LEVEL2,
                                                $app,
                                                $_GET['gfz']));
    }  
    
    $ckdcbuTypeId = null;
    if (isset($_GET['ckdcbu_type_id'])) {
         $stripper->offsetSet('ckdcbu_type_id',$stripChainerFactory->get(stripChainers::FILTER_ONLY_NUMBER_ALLOWED,
                                                $app,
                                                $_GET['ckdcbu_type_id']));
    }
     $vehicleGtModelId = null;
    if (isset($_GET['vehicle_gt_model_id'])) {
         $stripper->offsetSet('vehicle_gt_model_id',$stripChainerFactory->get(stripChainers::FILTER_ONLY_NUMBER_ALLOWED,
                                                $app,
                                                $_GET['vehicle_gt_model_id']));
    }
     $modelVariantId = null;
    if (isset($_GET['model_variant_id'])) {
         $stripper->offsetSet('model_variant_id',$stripChainerFactory->get(stripChainers::FILTER_ONLY_NUMBER_ALLOWED,
                                                $app,
                                                $_GET['model_variant_id']));
    }
     $configTypeId = null;
    if (isset($_GET['config_type_id'])) {
         $stripper->offsetSet('config_type_id',$stripChainerFactory->get(stripChainers::FILTER_ONLY_NUMBER_ALLOWED,
                                                $app,
                                                $_GET['config_type_id']));
    }
     $capTypeId = null;
    if (isset($_GET['cap_type_id'])) {
         $stripper->offsetSet('cap_type_id',$stripChainerFactory->get(stripChainers::FILTER_ONLY_NUMBER_ALLOWED,
                                                $app,
                                                $_GET['cap_type_id']));
    }
     $vehicleAppTypeId = null;
    if (isset($_GET['vehicle_app_type_id'])) {
         $stripper->offsetSet('vehicle_app_type_id',$stripChainerFactory->get(stripChainers::FILTER_ONLY_NUMBER_ALLOWED,
                                                $app,
                                                $_GET['vehicle_app_type_id']));
    }
     $kpnumberId = null;
    if (isset($_GET['kpnumber_id'])) {
         $stripper->offsetSet('kpnumber_id',$stripChainerFactory->get(stripChainers::FILTER_ONLY_NUMBER_ALLOWED,
                                                $app,
                                                $_GET['kpnumber_id']));
    }
    $btsbtoTypeId = null;
    if (isset($_GET['btsbto_type_id'])) {
         $stripper->offsetSet('btsbto_type_id',$stripChainerFactory->get(stripChainers::FILTER_ONLY_NUMBER_ALLOWED,
                                                $app,
                                                $_GET['btsbto_type_id']));
    }
    $roadTypeId = null;
    if (isset($_GET['roadtype_id'])) {
         $stripper->offsetSet('roadtype_id',$stripChainerFactory->get(stripChainers::FILTER_ONLY_NUMBER_ALLOWED,
                                                $app,
                                                $_GET['roadtype_id']));
    }
    
    
   
    $stripper->strip();
    if($stripper->offsetExists('description')) $description = $stripper->offsetGet('description')->getFilterValue(); 
    if($stripper->offsetExists('factorymodel_name')) $factorymodelName = $stripper->offsetGet('factorymodel_name')->getFilterValue();
    if($stripper->offsetExists('gfz')) $gfz= $stripper->offsetGet('gfz')->getFilterValue();
    if($stripper->offsetExists('ckdcbu_type_id')) $ckdcbuTypeId = $stripper->offsetGet('ckdcbu_type_id')->getFilterValue();
    if($stripper->offsetExists('vehicle_gt_model_id')) $vehicleGtModelId = $stripper->offsetGet('vehicle_gt_model_id')->getFilterValue();
    if($stripper->offsetExists('model_variant_id')) $modelVariantId = $stripper->offsetGet('model_variant_id')->getFilterValue();
    if($stripper->offsetExists('config_type_id')) $configTypeId = $stripper->offsetGet('config_type_id')->getFilterValue();
    if($stripper->offsetExists('cap_type_id')) $capTypeId = $stripper->offsetGet('cap_type_id')->getFilterValue();
    if($stripper->offsetExists('vehicle_app_type_id')) $vehicleAppTypeId = $stripper->offsetGet('vehicle_app_type_id')->getFilterValue();
    if($stripper->offsetExists('kpnumber_id')) $kpnumberId = $stripper->offsetGet('kpnumber_id')->getFilterValue();
    if($stripper->offsetExists('btsbto_type_id')) $btsbtoTypeId = $stripper->offsetGet('btsbto_type_id')->getFilterValue();
    if($stripper->offsetExists('roadtype_id')) $roadTypeId = $stripper->offsetGet('roadtype_id')->getFilterValue();
        
    
//    &description=aracdescriptioni&factorymodel_name=xcv&gfz=ggttrr&ckdcbu_type_id=1&vehicle_gt_model_id=2&model_variant_id=1&config_type_id=2&cap_type_id=3&vehicle_app_type_id=1&kpnumber_id=5&btsbto_type_id=1&roadtype_id=2
    
    
    $resDataInsert = $BLL->insertAct(array(
            'Description' => $description,    
            'FactorymodelName' => $factorymodelName,   
            'Gfz' => $gfz,   
            'CkdcbuTypeId' => $ckdcbuTypeId,   
            'VehicleGtModelId' => $vehicleGtModelId,   
            'ModelVariantId' => $modelVariantId,   
            'ConfigTypeId' => $configTypeId,   
            'CapTypeId' => $capTypeId,   
            'VehicleAppTypeId' => $vehicleAppTypeId,   
            'KpnumberId' => $kpnumberId,   
            'BtsbtoTypeId' => $btsbtoTypeId,   
            'RoadTypeId' => $roadTypeId,   
        
            'pk' => $pk));
        
    $app->response()->header("Content-Type", "application/json"); 
    $app->response()->body(json_encode($resDataInsert));
    
}
);

/**
 *  * Okan CIRAN
 * @since 15-08-2018
 */ 
$app->get("/pkUpdateAct_sysvehicles/", function () use ($app ) {
    $stripper = $app->getServiceManager()->get('filterChainerCustom');
    $stripChainerFactory = new \Services\Filter\Helper\FilterChainerFactory(); 
    $BLL = $app->getBLLManager()->get('sysVehiclesBLL');  
    $headerParams = $app->request()->headers();
    if(!isset($headerParams['X-Public'])) throw new Exception ('rest api "pkUpdateAct_sysvehicles" end point, X-Public variable not found');    
    $pk = $headerParams['X-Public'];
    
    $vId = NULL;
    if (isset($_GET['id'])) {
         $stripper->offsetSet('id',$stripChainerFactory->get(stripChainers::FILTER_ONLY_NUMBER_ALLOWED,
                                                $app,
                                                $_GET['id']));
    } 
   $description = NULL;
    if (isset($_GET['description'])) {
         $stripper->offsetSet('description',$stripChainerFactory->get(stripChainers::FILTER_PARANOID_LEVEL2,
                                                $app,
                                                $_GET['description']));
    }   
     $factorymodelName = NULL;
    if (isset($_GET['factorymodel_name'])) {
         $stripper->offsetSet('factorymodel_name',$stripChainerFactory->get(stripChainers::FILTER_PARANOID_LEVEL2,
                                                $app,
                                                $_GET['factorymodel_name']));
    }  
    $gfz = NULL;
    if (isset($_GET['gfz'])) {
         $stripper->offsetSet('gfz',$stripChainerFactory->get(stripChainers::FILTER_PARANOID_LEVEL2,
                                                $app,
                                                $_GET['gfz']));
    }  
    
    $ckdcbuTypeId = null;
    if (isset($_GET['ckdcbu_type_id'])) {
         $stripper->offsetSet('ckdcbu_type_id',$stripChainerFactory->get(stripChainers::FILTER_ONLY_NUMBER_ALLOWED,
                                                $app,
                                                $_GET['ckdcbu_type_id']));
    }
     $vehicleGtModelId = null;
    if (isset($_GET['vehicle_gt_model_id'])) {
         $stripper->offsetSet('vehicle_gt_model_id',$stripChainerFactory->get(stripChainers::FILTER_ONLY_NUMBER_ALLOWED,
                                                $app,
                                                $_GET['vehicle_gt_model_id']));
    }
     $modelVariantId = null;
    if (isset($_GET['model_variant_id'])) {
         $stripper->offsetSet('model_variant_id',$stripChainerFactory->get(stripChainers::FILTER_ONLY_NUMBER_ALLOWED,
                                                $app,
                                                $_GET['model_variant_id']));
    }
     $configTypeId = null;
    if (isset($_GET['config_type_id'])) {
         $stripper->offsetSet('config_type_id',$stripChainerFactory->get(stripChainers::FILTER_ONLY_NUMBER_ALLOWED,
                                                $app,
                                                $_GET['config_type_id']));
    }
     $capTypeId = null;
    if (isset($_GET['cap_type_id'])) {
         $stripper->offsetSet('cap_type_id',$stripChainerFactory->get(stripChainers::FILTER_ONLY_NUMBER_ALLOWED,
                                                $app,
                                                $_GET['cap_type_id']));
    }
     $vehicleAppTypeId = null;
    if (isset($_GET['vehicle_app_type_id'])) {
         $stripper->offsetSet('vehicle_app_type_id',$stripChainerFactory->get(stripChainers::FILTER_ONLY_NUMBER_ALLOWED,
                                                $app,
                                                $_GET['vehicle_app_type_id']));
    }
     $kpnumberId = null;
    if (isset($_GET['kpnumber_id'])) {
         $stripper->offsetSet('kpnumber_id',$stripChainerFactory->get(stripChainers::FILTER_ONLY_NUMBER_ALLOWED,
                                                $app,
                                                $_GET['kpnumber_id']));
    }
    $btsbtoTypeId = null;
    if (isset($_GET['btsbto_type_id'])) {
         $stripper->offsetSet('btsbto_type_id',$stripChainerFactory->get(stripChainers::FILTER_ONLY_NUMBER_ALLOWED,
                                                $app,
                                                $_GET['btsbto_type_id']));
    }
    $roadTypeId = null;
    if (isset($_GET['roadtype_id'])) {
         $stripper->offsetSet('roadtype_id',$stripChainerFactory->get(stripChainers::FILTER_ONLY_NUMBER_ALLOWED,
                                                $app,
                                                $_GET['roadtype_id']));
    }
    
    
   
    $stripper->strip();
    if($stripper->offsetExists('description')) $description = $stripper->offsetGet('description')->getFilterValue(); 
    if($stripper->offsetExists('factorymodel_name')) $factorymodelName = $stripper->offsetGet('factorymodel_name')->getFilterValue();
    if($stripper->offsetExists('gfz')) $gfz= $stripper->offsetGet('gfz')->getFilterValue();
    if($stripper->offsetExists('ckdcbu_type_id')) $ckdcbuTypeId = $stripper->offsetGet('ckdcbu_type_id')->getFilterValue();
    if($stripper->offsetExists('vehicle_gt_model_id')) $vehicleGtModelId = $stripper->offsetGet('vehicle_gt_model_id')->getFilterValue();
    if($stripper->offsetExists('model_variant_id')) $modelVariantId = $stripper->offsetGet('model_variant_id')->getFilterValue();
    if($stripper->offsetExists('config_type_id')) $configTypeId = $stripper->offsetGet('config_type_id')->getFilterValue();
    if($stripper->offsetExists('cap_type_id')) $capTypeId = $stripper->offsetGet('cap_type_id')->getFilterValue();
    if($stripper->offsetExists('vehicle_app_type_id')) $vehicleAppTypeId = $stripper->offsetGet('vehicle_app_type_id')->getFilterValue();
    if($stripper->offsetExists('kpnumber_id')) $kpnumberId = $stripper->offsetGet('kpnumber_id')->getFilterValue();
    if($stripper->offsetExists('btsbto_type_id')) $btsbtoTypeId = $stripper->offsetGet('btsbto_type_id')->getFilterValue();
    if($stripper->offsetExists('roadtype_id')) $roadTypeId = $stripper->offsetGet('roadtype_id')->getFilterValue();
        
    if($stripper->offsetExists('id')) $vId = $stripper->offsetGet('id')->getFilterValue();
     
          
    $resDataInsert = $BLL->updateAct(array(
            'Id' => $vId,   
            'Description' => $description,    
            'FactorymodelName' => $factorymodelName,   
            'Gfz' => $gfz,   
            'CkdcbuTypeId' => $ckdcbuTypeId,   
            'VehicleGtModelId' => $vehicleGtModelId,   
            'ModelVariantId' => $modelVariantId,   
            'ConfigTypeId' => $configTypeId,   
            'CapTypeId' => $capTypeId,   
            'VehicleAppTypeId' => $vehicleAppTypeId,   
            'KpnumberId' => $kpnumberId,   
            'BtsbtoTypeId' => $btsbtoTypeId,   
            'RoadTypeId' => $roadTypeId,   
            'pk' => $pk));
        
    $app->response()->header("Content-Type", "application/json"); 
    $app->response()->body(json_encode($resDataInsert));
    
}
);
 
/**
 *  * Okan CIRAN
 * @since 15-08-2018
 */
$app->get("/pkDeletedAct_sysvehicles/", function () use ($app ) {
    $stripper = $app->getServiceManager()->get('filterChainerCustom');
    $stripChainerFactory = new \Services\Filter\Helper\FilterChainerFactory();    
    $BLL = $app->getBLLManager()->get('sysVehiclesBLL');   
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