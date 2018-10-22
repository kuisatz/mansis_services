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
 * @since 05.08.2018
 */
$app->get("/pkProjectVehicleTradeInAllDdList_infoprojecttradeinvehicle/", function () use ($app ) { 
    $stripper = $app->getServiceManager()->get('filterChainerCustom');
    $stripChainerFactory = new \Services\Filter\Helper\FilterChainerFactory(); 
    $BLL = $app->getBLLManager()->get('infoProjectTradeinvehicleBLL');
    
    $componentType = 'ddslick';
    if (isset($_GET['component_type'])) {
        $componentType = strtolower(trim($_GET['component_type']));
    }
    $headerParams = $app->request()->headers();
    if(!isset($headerParams['X-Public'])) throw new Exception ('rest api "pkProjectVehicleTradeInAllDdList_infoprojecttradeinvehicle" end point, X-Public variable not found');
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
    $ProjectId = NULL;
    if (isset($_GET['project_id'])) {
         $stripper->offsetSet('project_id',$stripChainerFactory->get(stripChainers::FILTER_ONLY_NUMBER_ALLOWED,
                                                $app,
                                                $_GET['project_id']));
    } 
    $stripper->strip();
    if($stripper->offsetExists('lid')) $lid = $stripper->offsetGet('lid')->getFilterValue();
    if($stripper->offsetExists('project_id')) $ProjectId = $stripper->offsetGet('project_id')->getFilterValue();
    if($stripper->offsetExists('language_code')) $vLanguageCode = $stripper->offsetGet('language_code')->getFilterValue();
        
    $resCombobox = $BLL->ProjectVehicleTradeInAllDdList(array(                                   
                                    'language_code' => $vLanguageCode,
                                    'ProjectId' => $ProjectId,
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
        );
    }
    $app->response()->header("Content-Type", "application/json");
    $app->response()->body(json_encode($flows));
});
 
 /**
 *  * Okan CIRAN
 * @since 05.08.2018
 */
$app->get("/pkProjectVehicleTradeInBoDdList_infoprojecttradeinvehicle/", function () use ($app ) { 
    $stripper = $app->getServiceManager()->get('filterChainerCustom');
    $stripChainerFactory = new \Services\Filter\Helper\FilterChainerFactory(); 
    $BLL = $app->getBLLManager()->get('infoProjectTradeinvehicleBLL');
    
    $componentType = 'ddslick';
    if (isset($_GET['component_type'])) {
        $componentType = strtolower(trim($_GET['component_type']));
    }
    $headerParams = $app->request()->headers();
    if(!isset($headerParams['X-Public'])) throw new Exception ('rest api "pkProjectVehicleTradeInBoDdList_infoprojecttradeinvehicle" end point, X-Public variable not found');
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
    $ProjectId = NULL;
    if (isset($_GET['project_id'])) {
         $stripper->offsetSet('project_id',$stripChainerFactory->get(stripChainers::FILTER_ONLY_NUMBER_ALLOWED,
                                                $app,
                                                $_GET['project_id']));
    } 
    $stripper->strip();
    if($stripper->offsetExists('lid')) $lid = $stripper->offsetGet('lid')->getFilterValue();
    if($stripper->offsetExists('project_id')) $ProjectId = $stripper->offsetGet('project_id')->getFilterValue();
    if($stripper->offsetExists('language_code')) $vLanguageCode = $stripper->offsetGet('language_code')->getFilterValue();
        
    $resCombobox = $BLL->projectVehicleTradeInBoDdList(array(                                   
                                    'language_code' => $vLanguageCode,
                                    'ProjectId' => $ProjectId,
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
        );
    }
    $app->response()->header("Content-Type", "application/json");
    $app->response()->body(json_encode($flows));
});
 
 
 /**
 *  * Okan CIRAN
 * @since 05.08.2018
 */
$app->get("/pkProjectVehicleTradeInHosDdList_infoprojecttradeinvehicle/", function () use ($app ) { 
    $stripper = $app->getServiceManager()->get('filterChainerCustom');
    $stripChainerFactory = new \Services\Filter\Helper\FilterChainerFactory(); 
    $BLL = $app->getBLLManager()->get('infoProjectTradeinvehicleBLL');
    
    $componentType = 'ddslick';
    if (isset($_GET['component_type'])) {
        $componentType = strtolower(trim($_GET['component_type']));
    }
    $headerParams = $app->request()->headers();
    if(!isset($headerParams['X-Public'])) throw new Exception ('rest api "pkProjectVehicleTradeInHosDdList_infoprojecttradeinvehicle" end point, X-Public variable not found');
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
    $ProjectId = NULL;
    if (isset($_GET['project_id'])) {
         $stripper->offsetSet('project_id',$stripChainerFactory->get(stripChainers::FILTER_ONLY_NUMBER_ALLOWED,
                                                $app,
                                                $_GET['project_id']));
    } 
    $stripper->strip();
    if($stripper->offsetExists('lid')) $lid = $stripper->offsetGet('lid')->getFilterValue();
    if($stripper->offsetExists('project_id')) $ProjectId = $stripper->offsetGet('project_id')->getFilterValue();
    if($stripper->offsetExists('language_code')) $vLanguageCode = $stripper->offsetGet('language_code')->getFilterValue();
        
    $resCombobox = $BLL->projectVehicleTradeInHosDdList(array(                                   
                                    'language_code' => $vLanguageCode,
                                    'ProjectId' => $ProjectId,
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
        );
    }
    $app->response()->header("Content-Type", "application/json");
    $app->response()->body(json_encode($flows));
});
 
 
/**
 *  * Okan CIRAN
 * @since 15-08-2018
 */
$app->get("/pkFillProjectVehicleTIGridx_infoprojecttradeinvehicle/", function () use ($app ) {
    $stripper = $app->getServiceManager()->get('filterChainerCustom');
    $stripChainerFactory = new \Services\Filter\Helper\FilterChainerFactory();
    $BLL = $app->getBLLManager()->get('infoProjectTradeinvehicleBLL');
    $headerParams = $app->request()->headers();
    if (!isset($headerParams['X-Public']))
        throw new Exception('rest api "pkFillProjectVehicleTIGridx_infoprojecttradeinvehicle" end point, X-Public variable not found');
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
    $ProjectId = NULL;
    if (isset($_GET['project_id'])) {
         $stripper->offsetSet('project_id',$stripChainerFactory->get(stripChainers::FILTER_ONLY_NUMBER_ALLOWED,
                                                $app,
                                                $_GET['project_id']));
    } 
    $stripper->strip();
    if($stripper->offsetExists('lid')) $lid = $stripper->offsetGet('lid')->getFilterValue();
    if ($stripper->offsetExists('language_code')) $vLanguageCode = $stripper->offsetGet('language_code')->getFilterValue();    
    if($stripper->offsetExists('project_id')) $ProjectId = $stripper->offsetGet('project_id')->getFilterValue();
    if ($stripper->offsetExists('page')) { $vPage = $stripper->offsetGet('page')->getFilterValue(); }
    if ($stripper->offsetExists('rows')) { $vRows = $stripper->offsetGet('rows')->getFilterValue(); }
    if ($stripper->offsetExists('sort')) { $vSort = $stripper->offsetGet('sort')->getFilterValue(); }
    if ($stripper->offsetExists('order')) { $vOrder = $stripper->offsetGet('order')->getFilterValue(); }
    if ($stripper->offsetExists('filterRules')) { $filterRules = $stripper->offsetGet('filterRules')->getFilterValue(); } 

    $resDataGrid = $BLL->fillProjectVehicleTIGridx(array(
        'language_code' => $vLanguageCode,
        'LanguageID' => $lid,
        'page' => $vPage,
        'rows' => $vRows,
        'sort' => $vSort,
        'order' => $vOrder,
        'ProjectId' => $ProjectId,
        'filterRules' => $filterRules,
        'pk' => $pk,
    ));
   
    $resTotalRowCount = $BLL->fillProjectVehicleTIGridxRtl(array(
        'language_code' => $vLanguageCode, 
        'LanguageID' => $lid,
        'ProjectId' => $ProjectId,
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
                "project_id" =>  ($menu["project_id"]),
              
                "engine_number" => html_entity_decode($menu["engine_number"]),
                "vin_number" => html_entity_decode($menu["vin_number"]),
                "km" =>  ($menu["km"]),
                "brand" => html_entity_decode($menu["brand"]),
                "brand" => html_entity_decode($menu["brand"]),
                "vehicle_brand" => html_entity_decode($menu["vehicle_brand"]),
                "vehicle_model" => html_entity_decode($menu["vehicle_model"]),
                "license_plate" => html_entity_decode($menu["license_plate"]),
                "model_year" =>  ($menu["model_year"]),
                "waranty" => html_entity_decode($menu["waranty"]),
                "truck_number" => html_entity_decode($menu["truck_number"]),
                "embrace_transfer_date" =>  ($menu["embrace_transfer_date"]),
                "vehicle_location" => html_entity_decode($menu["vehicle_location"]),
                "vehicle_up_desc" => html_entity_decode($menu["vehicle_up_desc"]),
                "vehicle_type_of_body" => html_entity_decode($menu["vehicle_type_of_body"]),
                "isbo_confirm" =>  ($menu["isbo_confirm"]),
                "ishos_confirm" =>  ($menu["ishos_confirm"]),
                "sa_description" => html_entity_decode($menu["sa_description"]),
                "bo_description" => html_entity_decode($menu["bo_description"]),
                    
               
                  
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
$app->get("/pkUpdateMakeActiveOrPassive_infoprojecttradeinvehicle/", function () use ($app ) {
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
    $BLL = $app->getBLLManager()->get('infoProjectTradeinvehicleBLL');

    
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
$app->get("/pkInsertAct_infoprojecttradeinvehicle/", function () use ($app ) {
    $stripper = $app->getServiceManager()->get('filterChainerCustom');
    $stripChainerFactory = new \Services\Filter\Helper\FilterChainerFactory(); 
    $BLL = $app->getBLLManager()->get('infoProjectTradeinvehicleBLL');  
    $headerParams = $app->request()->headers();
    if(!isset($headerParams['X-Public'])) throw new Exception ('rest api "pkInsertAct_infoprojecttradeinvehicle" end point, X-Public variable not found');    
     $pk =  $headerParams['X-Public'];
      
    $ProjectId = NULL;
    if (isset($_GET['project_id'])) {
         $stripper->offsetSet('project_id',$stripChainerFactory->get(stripChainers::FILTER_ONLY_NUMBER_ALLOWED,
                                                $app,
                                                $_GET['project_id']));
    }  
    $engineNumber = NULL;
    if (isset($_GET['engine_number'])) {
         $stripper->offsetSet('engine_number',$stripChainerFactory->get(stripChainers::FILTER_PARANOID_LEVEL2,
                                                $app,
                                                $_GET['engine_number']));
    } 
    $vinNumber = NULL;
    if (isset($_GET['vin_number'])) {
         $stripper->offsetSet('vin_number',$stripChainerFactory->get(stripChainers::FILTER_PARANOID_LEVEL2,
                                                $app,
                                                $_GET['vin_number']));
    }  
     $km = NULL;
    if (isset($_GET['km'])) {
         $stripper->offsetSet('km',$stripChainerFactory->get(stripChainers::FILTER_ONLY_NUMBER_ALLOWED,
                                                $app,
                                                $_GET['km']));
    } 
    $brand= NULL;
    if (isset($_GET['brand'])) {
         $stripper->offsetSet('brand',$stripChainerFactory->get(stripChainers::FILTER_PARANOID_LEVEL2,
                                                $app,
                                                $_GET['brand']));
    }  
    $vehicleBrand = NULL;
    if (isset($_GET['vehicle_brand'])) {
         $stripper->offsetSet('vehicle_brand',$stripChainerFactory->get(stripChainers::FILTER_PARANOID_LEVEL2,
                                                $app,
                                                $_GET['vehicle_brand']));
    }  
    $vehicleModel = NULL;
    if (isset($_GET['vehicle_model'])) {
         $stripper->offsetSet('vehicle_model',$stripChainerFactory->get(stripChainers::FILTER_PARANOID_LEVEL2,
                                                $app,
                                                $_GET['vehicle_model']));
    }  
     $licensePlate = NULL;
    if (isset($_GET['license_plate'])) {
         $stripper->offsetSet('license_plate',$stripChainerFactory->get(stripChainers::FILTER_PARANOID_LEVEL2,
                                                $app,
                                                $_GET['license_plate']));
    }  
     $modelyear = NULL;
    if (isset($_GET['model_year'])) {
         $stripper->offsetSet('model_year',$stripChainerFactory->get(stripChainers::FILTER_ONLY_NUMBER_ALLOWED,
                                                $app,
                                                $_GET['model_year']));
    }  
    $waranty = NULL;
    if (isset($_GET['waranty'])) {
         $stripper->offsetSet('waranty',$stripChainerFactory->get(stripChainers::FILTER_PARANOID_LEVEL2,
                                                $app,
                                                $_GET['waranty']));
    }  
     $trucknumber = NULL;
    if (isset($_GET['truck_number'])) {
         $stripper->offsetSet('truck_number',$stripChainerFactory->get(stripChainers::FILTER_PARANOID_LEVEL2,
                                                $app,
                                                $_GET['truck_number']));
    }  
    $vehicleLocation= NULL;
    if (isset($_GET['vehicle_location'])) {
         $stripper->offsetSet('vehicle_location',$stripChainerFactory->get(stripChainers::FILTER_PARANOID_LEVEL2,
                                                $app,
                                                $_GET['vehicle_location']));
    }  
    $vehicleUpdesc= NULL;
    if (isset($_GET['vehicle_up_desc'])) {
         $stripper->offsetSet('vehicle_up_desc',$stripChainerFactory->get(stripChainers::FILTER_PARANOID_LEVEL2,
                                                $app,
                                                $_GET['vehicle_up_desc']));
    }
    $vehicleTypeOfBody = NULL;
    if (isset($_GET['vehicle_type_of_body'])) {
         $stripper->offsetSet('vehicle_type_of_body',$stripChainerFactory->get(stripChainers::FILTER_PARANOID_LEVEL2,
                                                $app,
                                                $_GET['vehicle_type_of_body']));
    }
     $saDescription = NULL;
    if (isset($_GET['sa_description'])) {
         $stripper->offsetSet('sa_description',$stripChainerFactory->get(stripChainers::FILTER_PARANOID_LEVEL2,
                                                $app,
                                                $_GET['sa_description']));
    }
    $boDescription= NULL;
    if (isset($_GET['bo_description'])) {
         $stripper->offsetSet('bo_description',$stripChainerFactory->get(stripChainers::FILTER_PARANOID_LEVEL2,
                                                $app,
                                                $_GET['bo_description']));
    }
 
    $isboConfirm = NULL;
    if (isset($_GET['isbo_confirm'])) {
         $stripper->offsetSet('isbo_confirm',$stripChainerFactory->get(stripChainers::FILTER_ONLY_NUMBER_ALLOWED,
                                                $app,
                                                $_GET['isbo_confirm']));
    } 
    $ishosConfirm = NULL;
    if (isset($_GET['ishos_confirm'])) {
         $stripper->offsetSet('ishos_confirm',$stripChainerFactory->get(stripChainers::FILTER_ONLY_NUMBER_ALLOWED,
                                                $app,
                                                $_GET['ishos_confirm']));
    } 
    $embraceTransferDate= NULL;
    if (isset($_GET['embrace_transfer_date'])) {
         $stripper->offsetSet('embrace_transfer_date',$stripChainerFactory->get(stripChainers::FILTER_PARANOID_LEVEL2,
                                                $app,
                                                $_GET['embrace_transfer_date']));
    }
    
   
    $stripper->strip();
    if($stripper->offsetExists('project_id')) $ProjectId = $stripper->offsetGet('project_id')->getFilterValue(); 
    if($stripper->offsetExists('engine_number')) $engineNumber = $stripper->offsetGet('engine_number')->getFilterValue();
    if($stripper->offsetExists('vin_number')) $vinNumber = $stripper->offsetGet('vin_number')->getFilterValue();
    if($stripper->offsetExists('km')) $km = $stripper->offsetGet('km')->getFilterValue(); 
    if($stripper->offsetExists('brand')) $brand = $stripper->offsetGet('brand')->getFilterValue(); 
    if($stripper->offsetExists('vehicle_brand')) $vehicleBrand = $stripper->offsetGet('vehicle_brand')->getFilterValue(); 
    if($stripper->offsetExists('vehicle_model')) $vehicleModel = $stripper->offsetGet('vehicle_model')->getFilterValue(); 
    if($stripper->offsetExists('license_plate')) $licensePlate = $stripper->offsetGet('license_plate')->getFilterValue();   
    if($stripper->offsetExists('model_year')) $modelyear = $stripper->offsetGet('model_year')->getFilterValue(); 
    if($stripper->offsetExists('waranty')) $waranty = $stripper->offsetGet('waranty')->getFilterValue(); 
    if($stripper->offsetExists('vehicle_location')) $vehicleLocation = $stripper->offsetGet('vehicle_location')->getFilterValue(); 
    if($stripper->offsetExists('truck_number')) $trucknumber = $stripper->offsetGet('truck_number')->getFilterValue(); 
    if($stripper->offsetExists('vehicle_up_desc')) $vehicleUpdesc = $stripper->offsetGet('vehicle_up_desc')->getFilterValue(); 
    if($stripper->offsetExists('vehicle_type_of_body')) $vehicleTypeOfBody= $stripper->offsetGet('vehicle_type_of_body')->getFilterValue(); 
    if($stripper->offsetExists('sa_description')) $saDescription = $stripper->offsetGet('sa_description')->getFilterValue(); 
    if($stripper->offsetExists('bo_description')) $boDescription = $stripper->offsetGet('bo_description')->getFilterValue();  
    if($stripper->offsetExists('isbo_confirm')) $isboConfirm = $stripper->offsetGet('isbo_confirm')->getFilterValue(); 
    if($stripper->offsetExists('ishos_confirm')) $ishosConfirm = $stripper->offsetGet('ishos_confirm')->getFilterValue(); 
    if($stripper->offsetExists('embrace_transfer_date')) $embraceTransferDate = $stripper->offsetGet('embrace_transfer_date')->getFilterValue(); 
      
    /*           
                           
      &project_id=1&vehicles_endgroup_id=1&vehicles_trade_id=1&customer_type_id=1&comfort_super_id=1&terrain_id=1&vehicle_group_id=1&hydraulics_id=1&buyback_matrix_id=1&quantity=1&is_other=1&other_month_value=1&other_milages_value=1&other_description=1&deal_tb_value=1&isbo_confirm=1&ishos_confirm=1                     
    
     */ 
     
    
    $resDataInsert = $BLL->insertAct(array(
            'ProjectId' => $ProjectId,   
            'EngineNumber' => $engineNumber,
            'VinNumber' => $vinNumber,  
            'KM' => $km,  
            'Brand' => $brand,   
            'VehicleBrand' => $vehicleBrand,   
            'VehicleModel' => $vehicleModel,   
            'LicensePlate' => $licensePlate,   
            'ModelYear' => $modelyear,   
            'Waranty' => $waranty,   
            'TruckNumber' => $trucknumber,
            'VehicleLocation' => $vehicleUpdesc,   
            'VehicleTypeOfBody' => $vehicleTypeOfBody,   
            'VehicleUpDesc' => $vehicleUpdesc,   
         
            'SaDescription' => $saDescription,   
            'BoDescription' => $boDescription,   
            'EmbraceTransferDate' => $embraceTransferDate,   
            'IsBoConfirm' => $isboConfirm,   
            'IsHosConfirm' => $ishosConfirm,   
         
            'pk' => $pk));
        
    $app->response()->header("Content-Type", "application/json"); 
    $app->response()->body(json_encode($resDataInsert));
    
}
);

/**
 *  * Okan CIRAN
 * @since 15-08-2018
 */ 
$app->get("/pkUpdateAct_infoprojecttradeinvehicle/", function () use ($app ) {
    $stripper = $app->getServiceManager()->get('filterChainerCustom');
    $stripChainerFactory = new \Services\Filter\Helper\FilterChainerFactory(); 
    $BLL = $app->getBLLManager()->get('infoProjectTradeinvehicleBLL');  
    $headerParams = $app->request()->headers();
    if(!isset($headerParams['X-Public'])) throw new Exception ('rest api "pkUpdateAct_infoprojecttradeinvehicle" end point, X-Public variable not found');    
    $pk = $headerParams['X-Public'];
    
    $vId = NULL;
    if (isset($_GET['id'])) {
         $stripper->offsetSet('id',$stripChainerFactory->get(stripChainers::FILTER_ONLY_NUMBER_ALLOWED,
                                                $app,
                                                $_GET['id']));
    } 
       $ProjectId = NULL;
    if (isset($_GET['project_id'])) {
         $stripper->offsetSet('project_id',$stripChainerFactory->get(stripChainers::FILTER_ONLY_NUMBER_ALLOWED,
                                                $app,
                                                $_GET['project_id']));
    }  
    $engineNumber = NULL;
    if (isset($_GET['engine_number'])) {
         $stripper->offsetSet('engine_number',$stripChainerFactory->get(stripChainers::FILTER_PARANOID_LEVEL2,
                                                $app,
                                                $_GET['engine_number']));
    } 
    $vinNumber = NULL;
    if (isset($_GET['vin_number'])) {
         $stripper->offsetSet('vin_number',$stripChainerFactory->get(stripChainers::FILTER_PARANOID_LEVEL2,
                                                $app,
                                                $_GET['vin_number']));
    }  
     $km = NULL;
    if (isset($_GET['km'])) {
         $stripper->offsetSet('km',$stripChainerFactory->get(stripChainers::FILTER_ONLY_NUMBER_ALLOWED,
                                                $app,
                                                $_GET['km']));
    } 
    $brand= NULL;
    if (isset($_GET['brand'])) {
         $stripper->offsetSet('brand',$stripChainerFactory->get(stripChainers::FILTER_PARANOID_LEVEL2,
                                                $app,
                                                $_GET['brand']));
    }  
    $vehicleBrand = NULL;
    if (isset($_GET['vehicle_brand'])) {
         $stripper->offsetSet('vehicle_brand',$stripChainerFactory->get(stripChainers::FILTER_PARANOID_LEVEL2,
                                                $app,
                                                $_GET['vehicle_brand']));
    }  
    $vehicleModel = NULL;
    if (isset($_GET['vehicle_model'])) {
         $stripper->offsetSet('vehicle_model',$stripChainerFactory->get(stripChainers::FILTER_PARANOID_LEVEL2,
                                                $app,
                                                $_GET['vehicle_model']));
    }  
     $licensePlate = NULL;
    if (isset($_GET['license_plate'])) {
         $stripper->offsetSet('license_plate',$stripChainerFactory->get(stripChainers::FILTER_PARANOID_LEVEL2,
                                                $app,
                                                $_GET['license_plate']));
    }  
     $modelyear = NULL;
    if (isset($_GET['model_year'])) {
         $stripper->offsetSet('model_year',$stripChainerFactory->get(stripChainers::FILTER_ONLY_NUMBER_ALLOWED,
                                                $app,
                                                $_GET['model_year']));
    }  
    $waranty = NULL;
    if (isset($_GET['waranty'])) {
         $stripper->offsetSet('waranty',$stripChainerFactory->get(stripChainers::FILTER_PARANOID_LEVEL2,
                                                $app,
                                                $_GET['waranty']));
    }  
     $trucknumber = NULL;
    if (isset($_GET['truck_number'])) {
         $stripper->offsetSet('truck_number',$stripChainerFactory->get(stripChainers::FILTER_PARANOID_LEVEL2,
                                                $app,
                                                $_GET['truck_number']));
    }  
    $vehicleLocation= NULL;
    if (isset($_GET['vehicle_location'])) {
         $stripper->offsetSet('vehicle_location',$stripChainerFactory->get(stripChainers::FILTER_PARANOID_LEVEL2,
                                                $app,
                                                $_GET['vehicle_location']));
    }  
    $vehicleUpdesc= NULL;
    if (isset($_GET['vehicle_up_desc'])) {
         $stripper->offsetSet('vehicle_up_desc',$stripChainerFactory->get(stripChainers::FILTER_PARANOID_LEVEL2,
                                                $app,
                                                $_GET['vehicle_up_desc']));
    }
    $vehicleTypeOfBody = NULL;
    if (isset($_GET['vehicle_type_of_body'])) {
         $stripper->offsetSet('vehicle_type_of_body',$stripChainerFactory->get(stripChainers::FILTER_PARANOID_LEVEL2,
                                                $app,
                                                $_GET['vehicle_type_of_body']));
    }
     $saDescription = NULL;
    if (isset($_GET['sa_description'])) {
         $stripper->offsetSet('sa_description',$stripChainerFactory->get(stripChainers::FILTER_PARANOID_LEVEL2,
                                                $app,
                                                $_GET['sa_description']));
    }
    $boDescription= NULL;
    if (isset($_GET['bo_description'])) {
         $stripper->offsetSet('bo_description',$stripChainerFactory->get(stripChainers::FILTER_PARANOID_LEVEL2,
                                                $app,
                                                $_GET['bo_description']));
    }
 
    $isboConfirm = NULL;
    if (isset($_GET['isbo_confirm'])) {
         $stripper->offsetSet('isbo_confirm',$stripChainerFactory->get(stripChainers::FILTER_ONLY_NUMBER_ALLOWED,
                                                $app,
                                                $_GET['isbo_confirm']));
    } 
    $ishosConfirm = NULL;
    if (isset($_GET['ishos_confirm'])) {
         $stripper->offsetSet('ishos_confirm',$stripChainerFactory->get(stripChainers::FILTER_ONLY_NUMBER_ALLOWED,
                                                $app,
                                                $_GET['ishos_confirm']));
    } 
    $embraceTransferDate= NULL;
    if (isset($_GET['embrace_transfer_date'])) {
         $stripper->offsetSet('embrace_transfer_date',$stripChainerFactory->get(stripChainers::FILTER_PARANOID_LEVEL2,
                                                $app,
                                                $_GET['embrace_transfer_date']));
    }
    
   
    $stripper->strip();
    if($stripper->offsetExists('project_id')) $ProjectId = $stripper->offsetGet('project_id')->getFilterValue(); 
    if($stripper->offsetExists('engine_number')) $engineNumber = $stripper->offsetGet('engine_number')->getFilterValue();
    if($stripper->offsetExists('vin_number')) $vinNumber = $stripper->offsetGet('vin_number')->getFilterValue();
    if($stripper->offsetExists('km')) $km = $stripper->offsetGet('km')->getFilterValue(); 
    if($stripper->offsetExists('brand')) $brand = $stripper->offsetGet('brand')->getFilterValue(); 
    if($stripper->offsetExists('vehicle_brand')) $vehicleBrand = $stripper->offsetGet('vehicle_brand')->getFilterValue(); 
    if($stripper->offsetExists('vehicle_model')) $vehicleModel = $stripper->offsetGet('vehicle_model')->getFilterValue(); 
    if($stripper->offsetExists('license_plate')) $licensePlate = $stripper->offsetGet('license_plate')->getFilterValue();   
    if($stripper->offsetExists('model_year')) $modelyear = $stripper->offsetGet('model_year')->getFilterValue(); 
    if($stripper->offsetExists('waranty')) $waranty = $stripper->offsetGet('waranty')->getFilterValue(); 
    if($stripper->offsetExists('vehicle_location')) $vehicleLocation = $stripper->offsetGet('vehicle_location')->getFilterValue(); 
    if($stripper->offsetExists('truck_number')) $trucknumber = $stripper->offsetGet('truck_number')->getFilterValue(); 
    if($stripper->offsetExists('vehicle_up_desc')) $vehicleUpdesc = $stripper->offsetGet('vehicle_up_desc')->getFilterValue(); 
    if($stripper->offsetExists('vehicle_type_of_body')) $vehicleTypeOfBody= $stripper->offsetGet('vehicle_type_of_body')->getFilterValue(); 
    if($stripper->offsetExists('sa_description')) $saDescription = $stripper->offsetGet('sa_description')->getFilterValue(); 
    if($stripper->offsetExists('bo_description')) $boDescription = $stripper->offsetGet('bo_description')->getFilterValue();  
    if($stripper->offsetExists('isbo_confirm')) $isboConfirm = $stripper->offsetGet('isbo_confirm')->getFilterValue(); 
    if($stripper->offsetExists('ishos_confirm')) $ishosConfirm = $stripper->offsetGet('ishos_confirm')->getFilterValue(); 
    if($stripper->offsetExists('embrace_transfer_date')) $embraceTransferDate = $stripper->offsetGet('embrace_transfer_date')->getFilterValue(); 
     
    if($stripper->offsetExists('id')) $vId = $stripper->offsetGet('id')->getFilterValue();
     
          
    $resDataInsert = $BLL->updateAct(array(
            'Id' => $vId,    
             'ProjectId' => $ProjectId,   
            'EngineNumber' => $engineNumber,
            'VinNumber' => $vinNumber,  
            'KM' => $km,  
            'Brand' => $brand,   
            'VehicleBrand' => $vehicleBrand,   
            'VehicleModel' => $vehicleModel,   
            'LicensePlate' => $licensePlate,   
            'ModelYear' => $modelyear,   
            'Waranty' => $waranty,   
            'TruckNumber' => $trucknumber,
            'VehicleLocation' => $vehicleUpdesc,   
            'VehicleTypeOfBody' => $vehicleTypeOfBody,   
            'VehicleUpDesc' => $vehicleUpdesc,   
         
            'SaDescription' => $saDescription,   
            'BoDescription' => $boDescription,   
            'EmbraceTransferDate' => $embraceTransferDate,   
            'IsBoConfirm' => $isboConfirm,   
            'IsHosConfirm' => $ishosConfirm,   
            'pk' => $pk));
        
    $app->response()->header("Content-Type", "application/json"); 
    $app->response()->body(json_encode($resDataInsert));
    
}
);
 
/**
 *  * Okan CIRAN
 * @since 15-08-2018
 */
$app->get("/pkDeletedAct_infoprojecttradeinvehicle/", function () use ($app ) {
    $stripper = $app->getServiceManager()->get('filterChainerCustom');
    $stripChainerFactory = new \Services\Filter\Helper\FilterChainerFactory();    
    $BLL = $app->getBLLManager()->get('infoProjectTradeinvehicleBLL');   
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