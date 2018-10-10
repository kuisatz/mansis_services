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
$app->get("/pkProjectVehicleBBDdList_infoprojectbuybacks/", function () use ($app ) { 
    $stripper = $app->getServiceManager()->get('filterChainerCustom');
    $stripChainerFactory = new \Services\Filter\Helper\FilterChainerFactory(); 
    $BLL = $app->getBLLManager()->get('infoProjectBuybackBLL');
    
    $componentType = 'ddslick';
    if (isset($_GET['component_type'])) {
        $componentType = strtolower(trim($_GET['component_type']));
    }
    $headerParams = $app->request()->headers();
    if(!isset($headerParams['X-Public'])) throw new Exception ('rest api "pkProjectVehicleModelsDdList_infoprojectbuybacks" end point, X-Public variable not found');
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
        
    $resCombobox = $BLL->projectVehicleBBDdList(array(                                   
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
$app->get("/pkFillProjectVehicleBBGridx_infoprojectbuybacks/", function () use ($app ) {
    $stripper = $app->getServiceManager()->get('filterChainerCustom');
    $stripChainerFactory = new \Services\Filter\Helper\FilterChainerFactory();
    $BLL = $app->getBLLManager()->get('infoProjectBuybackBLL');
    $headerParams = $app->request()->headers();
    if (!isset($headerParams['X-Public']))
        throw new Exception('rest api "pkFillProjectVehicleBBGridx_infoprojectbuybacks" end point, X-Public variable not found');
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

    $resDataGrid = $BLL->fillProjectVehicleBBGridx(array(
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
   
    $resTotalRowCount = $BLL->fillProjectVehicleBBGridxRtl(array(
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
              
                "deal_sis_key" => html_entity_decode($menu["deal_sis_key"]),
                "vehicles_endgroup_id" =>  ($menu["vehicles_endgroup_id"]),
                "vehicle_gt_model_name" => html_entity_decode($menu["vehicle_gt_model_name"]),
                "tag_name" => html_entity_decode($menu["tag_name"]),
                  
                "quantity" =>  ($menu["quantity"]),
                "end_date" =>  ($menu["end_date"]),
               
                "vehicles_trade_id" =>  ($menu["vehicles_trade_id"]), 
                "vehicles_trade_name" => html_entity_decode($menu["vehicles_trade_name"]), 
                "customer_type_id" =>  ($menu["customer_type_id"]), 
                "customer_type_name" => html_entity_decode($menu["customer_type_name"]), 
                "comfort_super_id" =>  ($menu["comfort_super_id"]), 
                "comfort_super_name" => html_entity_decode($menu["comfort_super_name"]), 
                       
                "terrain_id" =>  ($menu["terrain_id"]), 
                "terrain_name" => html_entity_decode($menu["terrain_name"]), 
                
                "vehicle_group_id" =>  ($menu["vehicle_group_id"]), 
                "vahicle_description" => html_entity_decode($menu["vahicle_description"]), 
			 
                "hydraulics_id" =>  ($menu["hydraulics_id"]), 
                "hydraulics_name" => html_entity_decode($menu["hydraulics_name"]), 
                "buyback_matrix_id" =>  ($menu["buyback_matrix_id"]), 
                "price" =>  ($menu["price"]), 
                "is_other" =>  ($menu["is_other"]), 
                "is_other_name" => html_entity_decode($menu["is_other_name"]), 
                "other_month_value" =>  ($menu["other_month_value"]), 
                "other_milages_value" =>  ($menu["other_milages_value"]), 
                "other_description" => html_entity_decode($menu["other_description"]), 	 
                "end_date" =>  ($menu["end_date"]), 
                "deal_tb_value" =>  ($menu["deal_tb_value"]), 
                "isbo_confirm" =>  ($menu["isbo_confirm"]), 
                "ishos_confirm" =>  ($menu["ishos_confirm"]),  
                  
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
$app->get("/pkUpdateMakeActiveOrPassive_infoprojectbuybacks/", function () use ($app ) {
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
    $BLL = $app->getBLLManager()->get('infoProjectBuybackBLL');

    
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
$app->get("/pkInsertAct_infoprojectbuybacks/", function () use ($app ) {
    $stripper = $app->getServiceManager()->get('filterChainerCustom');
    $stripChainerFactory = new \Services\Filter\Helper\FilterChainerFactory(); 
    $BLL = $app->getBLLManager()->get('infoProjectBuybackBLL');  
    $headerParams = $app->request()->headers();
    if(!isset($headerParams['X-Public'])) throw new Exception ('rest api "pkInsertAct_infoprojectbuybacks" end point, X-Public variable not found');    
     $pk =  $headerParams['X-Public'];
      
    $ProjectId = NULL;
    if (isset($_GET['project_id'])) {
         $stripper->offsetSet('project_id',$stripChainerFactory->get(stripChainers::FILTER_ONLY_NUMBER_ALLOWED,
                                                $app,
                                                $_GET['project_id']));
    }  
    $vehiclesEndgroupId = NULL;
    if (isset($_GET['vehicles_endgroup_id'])) {
         $stripper->offsetSet('vehicles_endgroup_id',$stripChainerFactory->get(stripChainers::FILTER_ONLY_NUMBER_ALLOWED,
                                                $app,
                                                $_GET['vehicles_endgroup_id']));
    }  
    $vehiclesTradeId = NULL;
    if (isset($_GET['vehicles_trade_id'])) {
         $stripper->offsetSet('vehicles_trade_id',$stripChainerFactory->get(stripChainers::FILTER_ONLY_NUMBER_ALLOWED,
                                                $app,
                                                $_GET['vehicles_trade_id']));
    }  
    $CustomerTypeId = NULL;
    if (isset($_GET['customer_type_id'])) {
         $stripper->offsetSet('customer_type_id',$stripChainerFactory->get(stripChainers::FILTER_ONLY_NUMBER_ALLOWED,
                                                $app,
                                                $_GET['customer_type_id']));
    } 
    $comfortSuperId = NULL;
    if (isset($_GET['comfort_super_id'])) {
         $stripper->offsetSet('comfort_super_id',$stripChainerFactory->get(stripChainers::FILTER_ONLY_NUMBER_ALLOWED,
                                                $app,
                                                $_GET['comfort_super_id']));
    } 
    $terrainId = NULL;
    if (isset($_GET['terrain_id'])) {
         $stripper->offsetSet('terrain_id',$stripChainerFactory->get(stripChainers::FILTER_ONLY_NUMBER_ALLOWED,
                                                $app,
                                                $_GET['terrain_id']));
    } 
    $vehicleGroupId = NULL;
    if (isset($_GET['vehicle_group_id'])) {
         $stripper->offsetSet('vehicle_group_id',$stripChainerFactory->get(stripChainers::FILTER_ONLY_NUMBER_ALLOWED,
                                                $app,
                                                $_GET['vehicle_group_id']));
    } 
    $hydraulicsId = NULL;
    if (isset($_GET['hydraulics_id'])) {
         $stripper->offsetSet('hydraulics_id',$stripChainerFactory->get(stripChainers::FILTER_ONLY_NUMBER_ALLOWED,
                                                $app,
                                                $_GET['hydraulics_id']));
    } 
    $buybackMatrixId = NULL;
    if (isset($_GET['buyback_matrix_id'])) {
         $stripper->offsetSet('buyback_matrix_id',$stripChainerFactory->get(stripChainers::FILTER_ONLY_NUMBER_ALLOWED,
                                                $app,
                                                $_GET['buyback_matrix_id']));
    } 
    $Quantity = NULL;
    if (isset($_GET['quantity'])) {
         $stripper->offsetSet('quantity',$stripChainerFactory->get(stripChainers::FILTER_ONLY_NUMBER_ALLOWED,
                                                $app,
                                                $_GET['quantity']));
    } 
    $isOther = NULL;
    if (isset($_GET['is_other'])) {
         $stripper->offsetSet('is_other',$stripChainerFactory->get(stripChainers::FILTER_ONLY_NUMBER_ALLOWED,
                                                $app,
                                                $_GET['is_other']));
    } 
    $otherMonthValue = NULL;
    if (isset($_GET['other_month_value'])) {
         $stripper->offsetSet('other_month_value',$stripChainerFactory->get(stripChainers::FILTER_ONLY_NUMBER_ALLOWED,
                                                $app,
                                                $_GET['other_month_value']));
    } 
    $otherMilagesValue = NULL;
    if (isset($_GET['other_milages_value'])) {
         $stripper->offsetSet('other_milages_value',$stripChainerFactory->get(stripChainers::FILTER_ONLY_NUMBER_ALLOWED,
                                                $app,
                                                $_GET['other_milages_value']));
    } 
    $otherDescription = NULL;
    if (isset($_GET['other_description'])) {
         $stripper->offsetSet('other_description',$stripChainerFactory->get(stripChainers::FILTER_PARANOID_LEVEL2,
                                                $app,
                                                $_GET['other_description']));
    } 
    $endDate = NULL;
    if (isset($_GET['end_date'])) {
         $stripper->offsetSet('end_date',$stripChainerFactory->get(stripChainers::FILTER_PARANOID_LEVEL2,
                                                $app,
                                                $_GET['end_date']));
    } 
    $dealTbValue = NULL;
    if (isset($_GET['deal_tb_value'])) {
         $stripper->offsetSet('deal_tb_value',$stripChainerFactory->get(stripChainers::FILTER_ONLY_NUMBER_ALLOWED,
                                                $app,
                                                $_GET['deal_tb_value']));
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
     
    $stripper->strip();
    if($stripper->offsetExists('project_id')) $ProjectId = $stripper->offsetGet('project_id')->getFilterValue(); 
    if($stripper->offsetExists('vehicles_endgroup_id')) $vehiclesEndgroupId = $stripper->offsetGet('vehicles_endgroup_id')->getFilterValue();
    if($stripper->offsetExists('quantity')) $Quantity = $stripper->offsetGet('quantity')->getFilterValue();
    if($stripper->offsetExists('delivery_date')) $DeliveryDate = $stripper->offsetGet('delivery_date')->getFilterValue(); 
    if($stripper->offsetExists('vehicles_trade_id')) $vehiclesTradeId = $stripper->offsetGet('vehicles_trade_id')->getFilterValue(); 
    if($stripper->offsetExists('customer_type_id')) $CustomerTypeId = $stripper->offsetGet('customer_type_id')->getFilterValue(); 
    if($stripper->offsetExists('comfort_super_id')) $CustomerTypeId = $stripper->offsetGet('comfort_super_id')->getFilterValue(); 
    if($stripper->offsetExists('terrain_id')) $terrainId = $stripper->offsetGet('terrain_id')->getFilterValue();   
    if($stripper->offsetExists('vehicle_group_id')) $vehicleGroupId = $stripper->offsetGet('vehicle_group_id')->getFilterValue(); 
    if($stripper->offsetExists('hydraulics_id')) $hydraulicsId = $stripper->offsetGet('hydraulics_id')->getFilterValue(); 
    if($stripper->offsetExists('buyback_matrix_id')) $buybackMatrixId = $stripper->offsetGet('buyback_matrix_id')->getFilterValue(); 
    if($stripper->offsetExists('is_other')) $isOther = $stripper->offsetGet('is_other')->getFilterValue(); 
    if($stripper->offsetExists('other_month_value')) $otherMonthValue = $stripper->offsetGet('other_month_value')->getFilterValue(); 
    if($stripper->offsetExists('other_milages_value')) $otherMilagesValue = $stripper->offsetGet('other_milages_value')->getFilterValue(); 
    if($stripper->offsetExists('other_description')) $otherDescription = $stripper->offsetGet('other_description')->getFilterValue(); 
    if($stripper->offsetExists('end_date')) $endDate = $stripper->offsetGet('end_date')->getFilterValue(); 
    if($stripper->offsetExists('deal_tb_value')) $dealTbValue = $stripper->offsetGet('deal_tb_value')->getFilterValue(); 
    if($stripper->offsetExists('isbo_confirm')) $isboConfirm = $stripper->offsetGet('isbo_confirm')->getFilterValue(); 
    if($stripper->offsetExists('ishos_confirm')) $ishosConfirm = $stripper->offsetGet('ishos_confirm')->getFilterValue(); 
      
    /*
 
      &project_id=1&vehicles_endgroup_id=1&vehicles_trade_id=1&customer_type_id=1&comfort_super_id=1&terrain_id=1&vehicle_group_id=1&hydraulics_id=1&buyback_matrix_id=1&quantity=1&is_other=1&other_month_value=1&other_milages_value=1&other_description=1&deal_tb_value=1&isbo_confirm=1&ishos_confirm=1                     
    
     
     */ 
    
    
    $resDataInsert = $BLL->insertAct(array(
            'ProjectId' => $ProjectId,   
            'VehiclesEndgroupId' => $vehiclesEndgroupId,
            'VehiclesTradeId' => $vehiclesTradeId,  
            'CustomerTypeId' => $CustomerTypeId,   
        
            'ComfortSuperId' => $comfortSuperId,   
            'TerrainId' => $terrainId,   
            'VehicleGroupId' => $vehicleGroupId,   
            'HydraulicsId' => $hydraulicsId,   
            'BuybackMatrixId' => $buybackMatrixId,   
            'Quantity' => $Quantity,   
            'IsOther' => $isOther,
            'OtherMonthValue' => $otherMonthValue,   
            'OtherMilagesValue' => $otherMilagesValue,   
        
            'OtherDescription' => $otherDescription,   
            'EndDate' => $endDate,   
            'DealTbValue' => $dealTbValue,   
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
$app->get("/pkUpdateAct_infoprojectbuybacks/", function () use ($app ) {
    $stripper = $app->getServiceManager()->get('filterChainerCustom');
    $stripChainerFactory = new \Services\Filter\Helper\FilterChainerFactory(); 
    $BLL = $app->getBLLManager()->get('infoProjectBuybackBLL');  
    $headerParams = $app->request()->headers();
    if(!isset($headerParams['X-Public'])) throw new Exception ('rest api "pkUpdateAct_infoprojectbuybacks" end point, X-Public variable not found');    
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
    $vehiclesEndgroupId = NULL;
    if (isset($_GET['vehicles_endgroup_id'])) {
         $stripper->offsetSet('vehicles_endgroup_id',$stripChainerFactory->get(stripChainers::FILTER_ONLY_NUMBER_ALLOWED,
                                                $app,
                                                $_GET['vehicles_endgroup_id']));
    }  
    $vehiclesTradeId = NULL;
    if (isset($_GET['vehicles_trade_id'])) {
         $stripper->offsetSet('vehicles_trade_id',$stripChainerFactory->get(stripChainers::FILTER_ONLY_NUMBER_ALLOWED,
                                                $app,
                                                $_GET['vehicles_trade_id']));
    }  
    $CustomerTypeId = NULL;
    if (isset($_GET['customer_type_id'])) {
         $stripper->offsetSet('customer_type_id',$stripChainerFactory->get(stripChainers::FILTER_ONLY_NUMBER_ALLOWED,
                                                $app,
                                                $_GET['customer_type_id']));
    } 
    $comfortSuperId = NULL;
    if (isset($_GET['comfort_super_id'])) {
         $stripper->offsetSet('comfort_super_id',$stripChainerFactory->get(stripChainers::FILTER_ONLY_NUMBER_ALLOWED,
                                                $app,
                                                $_GET['comfort_super_id']));
    } 
    $terrainId = NULL;
    if (isset($_GET['terrain_id'])) {
         $stripper->offsetSet('terrain_id',$stripChainerFactory->get(stripChainers::FILTER_ONLY_NUMBER_ALLOWED,
                                                $app,
                                                $_GET['terrain_id']));
    } 
    $vehicleGroupId = NULL;
    if (isset($_GET['vehicle_group_id'])) {
         $stripper->offsetSet('vehicle_group_id',$stripChainerFactory->get(stripChainers::FILTER_ONLY_NUMBER_ALLOWED,
                                                $app,
                                                $_GET['vehicle_group_id']));
    } 
    $hydraulicsId = NULL;
    if (isset($_GET['hydraulics_id'])) {
         $stripper->offsetSet('hydraulics_id',$stripChainerFactory->get(stripChainers::FILTER_ONLY_NUMBER_ALLOWED,
                                                $app,
                                                $_GET['hydraulics_id']));
    } 
    $buybackMatrixId = NULL;
    if (isset($_GET['buyback_matrix_id'])) {
         $stripper->offsetSet('buyback_matrix_id',$stripChainerFactory->get(stripChainers::FILTER_ONLY_NUMBER_ALLOWED,
                                                $app,
                                                $_GET['buyback_matrix_id']));
    } 
    $Quantity = NULL;
    if (isset($_GET['quantity'])) {
         $stripper->offsetSet('quantity',$stripChainerFactory->get(stripChainers::FILTER_ONLY_NUMBER_ALLOWED,
                                                $app,
                                                $_GET['quantity']));
    } 
    $isOther = NULL;
    if (isset($_GET['is_other'])) {
         $stripper->offsetSet('is_other',$stripChainerFactory->get(stripChainers::FILTER_ONLY_NUMBER_ALLOWED,
                                                $app,
                                                $_GET['is_other']));
    } 
    $otherMonthValue = NULL;
    if (isset($_GET['other_month_value'])) {
         $stripper->offsetSet('other_month_value',$stripChainerFactory->get(stripChainers::FILTER_ONLY_NUMBER_ALLOWED,
                                                $app,
                                                $_GET['other_month_value']));
    } 
    $otherMilagesValue = NULL;
    if (isset($_GET['other_milages_value'])) {
         $stripper->offsetSet('other_milages_value',$stripChainerFactory->get(stripChainers::FILTER_ONLY_NUMBER_ALLOWED,
                                                $app,
                                                $_GET['other_milages_value']));
    } 
    $otherDescription = NULL;
    if (isset($_GET['other_description'])) {
         $stripper->offsetSet('other_description',$stripChainerFactory->get(stripChainers::FILTER_PARANOID_LEVEL2,
                                                $app,
                                                $_GET['other_description']));
    } 
    $endDate = NULL;
    if (isset($_GET['end_date'])) {
         $stripper->offsetSet('end_date',$stripChainerFactory->get(stripChainers::FILTER_PARANOID_LEVEL2,
                                                $app,
                                                $_GET['end_date']));
    } 
    $dealTbValue = NULL;
    if (isset($_GET['deal_tb_value'])) {
         $stripper->offsetSet('deal_tb_value',$stripChainerFactory->get(stripChainers::FILTER_ONLY_NUMBER_ALLOWED,
                                                $app,
                                                $_GET['deal_tb_value']));
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
     
    $stripper->strip();
    if($stripper->offsetExists('project_id')) $ProjectId = $stripper->offsetGet('project_id')->getFilterValue(); 
    if($stripper->offsetExists('vehicles_endgroup_id')) $vehiclesEndgroupId = $stripper->offsetGet('vehicles_endgroup_id')->getFilterValue();
    if($stripper->offsetExists('quantity')) $Quantity = $stripper->offsetGet('quantity')->getFilterValue();
    if($stripper->offsetExists('delivery_date')) $DeliveryDate = $stripper->offsetGet('delivery_date')->getFilterValue(); 
    if($stripper->offsetExists('vehicles_trade_id')) $vehiclesTradeId = $stripper->offsetGet('vehicles_trade_id')->getFilterValue(); 
    if($stripper->offsetExists('customer_type_id')) $CustomerTypeId = $stripper->offsetGet('customer_type_id')->getFilterValue(); 
    if($stripper->offsetExists('comfort_super_id')) $CustomerTypeId = $stripper->offsetGet('comfort_super_id')->getFilterValue(); 
    if($stripper->offsetExists('terrain_id')) $terrainId = $stripper->offsetGet('terrain_id')->getFilterValue();   
    if($stripper->offsetExists('vehicle_group_id')) $vehicleGroupId = $stripper->offsetGet('vehicle_group_id')->getFilterValue(); 
    if($stripper->offsetExists('hydraulics_id')) $hydraulicsId = $stripper->offsetGet('hydraulics_id')->getFilterValue(); 
    if($stripper->offsetExists('buyback_matrix_id')) $buybackMatrixId = $stripper->offsetGet('buyback_matrix_id')->getFilterValue(); 
    if($stripper->offsetExists('is_other')) $isOther = $stripper->offsetGet('is_other')->getFilterValue(); 
    if($stripper->offsetExists('other_month_value')) $otherMonthValue = $stripper->offsetGet('other_month_value')->getFilterValue(); 
    if($stripper->offsetExists('other_milages_value')) $otherMilagesValue = $stripper->offsetGet('other_milages_value')->getFilterValue(); 
    if($stripper->offsetExists('other_description')) $otherDescription = $stripper->offsetGet('other_description')->getFilterValue(); 
    if($stripper->offsetExists('end_date')) $endDate = $stripper->offsetGet('end_date')->getFilterValue(); 
    if($stripper->offsetExists('deal_tb_value')) $dealTbValue = $stripper->offsetGet('deal_tb_value')->getFilterValue(); 
    if($stripper->offsetExists('isbo_confirm')) $isboConfirm = $stripper->offsetGet('isbo_confirm')->getFilterValue(); 
    if($stripper->offsetExists('ishos_confirm')) $ishosConfirm = $stripper->offsetGet('ishos_confirm')->getFilterValue(); 
     
    if($stripper->offsetExists('id')) $vId = $stripper->offsetGet('id')->getFilterValue();
     
          
    $resDataInsert = $BLL->updateAct(array(
            'Id' => $vId,    
            'ProjectId' => $ProjectId,   
            'VehiclesEndgroupId' => $vehiclesEndgroupId,
            'VehiclesTradeId' => $vehiclesTradeId,  
            'CustomerTypeId' => $CustomerTypeId,   
        
            'ComfortSuperId' => $comfortSuperId,   
            'TerrainId' => $terrainId,   
            'VehicleGroupId' => $vehicleGroupId,   
            'HydraulicsId' => $hydraulicsId,   
            'BuybackMatrixId' => $buybackMatrixId,   
            'Quantity' => $Quantity,   
            'IsOther' => $isOther,
            'OtherMonthValue' => $otherMonthValue,   
            'OtherMilagesValue' => $otherMilagesValue,   
        
            'OtherDescription' => $otherDescription,   
            'EndDate' => $endDate,   
            'DealTbValue' => $dealTbValue,   
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
$app->get("/pkDeletedAct_infoprojectbuybacks/", function () use ($app ) {
    $stripper = $app->getServiceManager()->get('filterChainerCustom');
    $stripChainerFactory = new \Services\Filter\Helper\FilterChainerFactory();    
    $BLL = $app->getBLLManager()->get('infoProjectBuybackBLL');   
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
$app->get("/pkFillProjectBBSpecialGridx_infoprojectbuybacks/", function () use ($app ) {
    $stripper = $app->getServiceManager()->get('filterChainerCustom');
    $stripChainerFactory = new \Services\Filter\Helper\FilterChainerFactory();
    $BLL = $app->getBLLManager()->get('infoProjectBuybackBLL');
    $headerParams = $app->request()->headers();
    if (!isset($headerParams['X-Public']))
        throw new Exception('rest api "pkFillProjectBBSpecialGridx_infoprojectbuybacks" end point, X-Public variable not found');
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
    

    $resDataGrid = $BLL->fillProjectBBSpecialGridx(array(
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
                "37" => ($menu["deal_sis_key"]),
                "38" => ($menu["vehicles_endgroup_id"]),
                "39" => ($menu["vehicle_gt_model_name"]),
                "40" => ($menu["tag_name"]),
                "41" => ($menu["tag_name"]),
                "42" => ($menu["tag_name"]),
                "43" => ($menu["tag_name"]),
                "44" => ($menu["tag_name"]),
                "45" => ($menu["tag_name"]),
                "46" => ($menu["tag_name"]),  
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