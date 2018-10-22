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
$app->get("/pkProjectVehicleCampaignDdList_infoprojectcampaign/", function () use ($app ) { 
    $stripper = $app->getServiceManager()->get('filterChainerCustom');
    $stripChainerFactory = new \Services\Filter\Helper\FilterChainerFactory(); 
    $BLL = $app->getBLLManager()->get('infoProjectCampaignBLL');
    
    $componentType = 'ddslick';
    if (isset($_GET['component_type'])) {
        $componentType = strtolower(trim($_GET['component_type']));
    }
    $headerParams = $app->request()->headers();
    if(!isset($headerParams['X-Public'])) throw new Exception ('rest api "pkProjectVehicleCampaignDdList_infoprojectcampaign" end point, X-Public variable not found');
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
        
    $resCombobox = $BLL->projectVehicleCampaignDdList(array(                                   
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
$app->get("/pkFillProjectCampaignGridx_infoprojectcampaign/", function () use ($app ) {
    $stripper = $app->getServiceManager()->get('filterChainerCustom');
    $stripChainerFactory = new \Services\Filter\Helper\FilterChainerFactory();
    $BLL = $app->getBLLManager()->get('infoProjectCampaignBLL');
    $headerParams = $app->request()->headers();
    if (!isset($headerParams['X-Public']))
        throw new Exception('rest api "pkFillProjectCampaignGridx_infoprojectcampaign" end point, X-Public variable not found');
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

     
    
    $resDataGrid = $BLL->fillProjectCampaignGridx(array(
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
   
    $resTotalRowCount = $BLL->fillProjectCampaignGridxRtl(array(
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
              
                "vehicles_endgroup_id" =>  ($menu["vehicles_endgroup_id"]),
                "vehicle_gt_model_name" => html_entity_decode($menu["vehicle_gt_model_name"]),
                "vehicle_group_id" =>  ($menu["vehicle_group_id"]),
                "vehicle_description" => html_entity_decode($menu["vehicle_description"]),
                
                "campaign_id" =>  ($menu["campaign_id"]),
                "campaign_name" => html_entity_decode($menu["campaign_name"]),
                "campaign_vehicle_id" => html_entity_decode($menu["campaign_vehicle_id"]),
                "local_support" => html_entity_decode($menu["local_support"]),
                "germany_support" =>  ($menu["germany_support"]),
                "mfs_support" => html_entity_decode($menu["mfs_support"]),
             
                "quantity" =>  ($menu["quantity"]),
                
                "isbo_confirm" =>  ($menu["isbo_confirm"]),
                "ishos_confirm" =>  ($menu["ishos_confirm"]),
                "sa_description" => html_entity_decode($menu["sa_description"]),
                "bo_description" => html_entity_decode($menu["bo_description"]),
                
                "is_other" =>  ($menu["is_other"]),
                "other_month" => html_entity_decode($menu["other_month"]),
                "other_km" => html_entity_decode($menu["other_km"]),
                "other_desc" => html_entity_decode($menu["other_desc"]),
                        
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
$app->get("/pkUpdateMakeActiveOrPassive_infoprojectcampaign/", function () use ($app ) {
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
    $BLL = $app->getBLLManager()->get('infoProjectCampaignBLL');

    
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
$app->get("/pkInsertAct_infoprojectcampaign/", function () use ($app ) {
    $stripper = $app->getServiceManager()->get('filterChainerCustom');
    $stripChainerFactory = new \Services\Filter\Helper\FilterChainerFactory(); 
    $BLL = $app->getBLLManager()->get('infoProjectCampaignBLL');  
    $headerParams = $app->request()->headers();
    if(!isset($headerParams['X-Public'])) throw new Exception ('rest api "pkInsertAct_infoprojectcampaign" end point, X-Public variable not found');    
     $pk =  $headerParams['X-Public'];
      
    $ProjectId = NULL;
    if (isset($_GET['project_id'])) {
         $stripper->offsetSet('project_id',$stripChainerFactory->get(stripChainers::FILTER_ONLY_NUMBER_ALLOWED,
                                                $app,
                                                $_GET['project_id']));
    }  
    $vehicleGroupId= NULL;
    if (isset($_GET['vehicle_group_id'])) {
         $stripper->offsetSet('vehicle_group_id',$stripChainerFactory->get(stripChainers::FILTER_ONLY_NUMBER_ALLOWED,
                                                $app,
                                                $_GET['vehicle_group_id']));
    } 
    $vehiclesEndgroupId = NULL;
    if (isset($_GET['vehicles_endgroup_id'])) {
         $stripper->offsetSet('vehicles_endgroup_id',$stripChainerFactory->get(stripChainers::FILTER_ONLY_NUMBER_ALLOWED,
                                                $app,
                                                $_GET['vehicles_endgroup_id']));
    }  
    $quantity = NULL;
    if (isset($_GET['quantity'])) {
         $stripper->offsetSet('quantity',$stripChainerFactory->get(stripChainers::FILTER_ONLY_NUMBER_ALLOWED,
                                                $app,
                                                $_GET['quantity']));
    } 
     
    $isother = NULL;
    if (isset($_GET['is_other'])) {
         $stripper->offsetSet('is_other',$stripChainerFactory->get(stripChainers::FILTER_ONLY_NUMBER_ALLOWED,
                                                $app,
                                                $_GET['is_other']));
    }
    $otherMonth= NULL;
    if (isset($_GET['other_month'])) {
         $stripper->offsetSet('other_month',$stripChainerFactory->get(stripChainers::FILTER_PARANOID_LEVEL2,
                                                $app,
                                                $_GET['other_month']));
    }
 
    $otherKm= NULL;
    if (isset($_GET['other_km'])) {
         $stripper->offsetSet('other_km',$stripChainerFactory->get(stripChainers::FILTER_PARANOID_LEVEL2,
                                                $app,
                                                $_GET['other_km']));
    }
    $otherDesc = NULL;
    if (isset($_GET['other_desc'])) {
         $stripper->offsetSet('other_desc',$stripChainerFactory->get(stripChainers::FILTER_PARANOID_LEVEL2,
                                                $app,
                                                $_GET['other_desc']));
    } 
    $campaignVehicleId= NULL;
    if (isset($_GET['campaign_vehicle_id'])) {
         $stripper->offsetSet('campaign_vehicle_id',$stripChainerFactory->get(stripChainers::FILTER_ONLY_NUMBER_ALLOWED,
                                                $app,
                                                $_GET['campaign_vehicle_id']));
    }               
    
    $stripper->strip();
    if($stripper->offsetExists('project_id')) $ProjectId = $stripper->offsetGet('project_id')->getFilterValue(); 
    if($stripper->offsetExists('vehicle_group_id')) $vehicleGroupId = $stripper->offsetGet('vehicle_group_id')->getFilterValue();
    if($stripper->offsetExists('vehicles_endgroup_id')) $vehiclesEndgroupId = $stripper->offsetGet('vehicles_endgroup_id')->getFilterValue();
    if($stripper->offsetExists('quantity')) $quantity = $stripper->offsetGet('quantity')->getFilterValue(); 
    
    if($stripper->offsetExists('campaign_vehicle_id')) $campaignVehicleId = $stripper->offsetGet('campaign_vehicle_id')->getFilterValue();  
    if($stripper->offsetExists('is_other')) $isother = $stripper->offsetGet('is_other')->getFilterValue(); 
    if($stripper->offsetExists('other_month')) $otherMonth = $stripper->offsetGet('other_month')->getFilterValue(); 
    if($stripper->offsetExists('other_km')) $otherKm = $stripper->offsetGet('other_km')->getFilterValue(); 
    if($stripper->offsetExists('other_desc')) $otherDesc = $stripper->offsetGet('other_desc')->getFilterValue(); 
    
      
    /*                      
                                               
      &project_id=1&vehicles_endgroup_id=1&vehicles_trade_id=1&customer_type_id=1&comfort_super_id=1&terrain_id=1&vehicle_group_id=1&hydraulics_id=1&buyback_matrix_id=1&quantity=1&is_other=1&other_month_value=1&other_milages_value=1&other_description=1&deal_tb_value=1&isbo_confirm=1&ishos_confirm=1                     
    
     */ 
      
    $resDataInsert = $BLL->insertAct(array(
            'ProjectId' => $ProjectId,   
            'VehicleGroupId' => $vehicleGroupId,
            'VehiclesEndgroupId' => $vehiclesEndgroupId,  
            'Quantity' => $quantity,   
            'IsOther' => $isother,  
            'OtherMmonth' => $otherMonth,   
            'OtherKm' => $otherKm,   
            'OtherDesc' => $otherDesc,   
            'CampaignVehicleId' => $campaignVehicleId,    
            'pk' => $pk));
        
    $app->response()->header("Content-Type", "application/json"); 
    $app->response()->body(json_encode($resDataInsert));
    
}
);

/**
 *  * Okan CIRAN
 * @since 15-08-2018
 */ 
$app->get("/pkUpdateAct_infoprojectcampaign/", function () use ($app ) {
    $stripper = $app->getServiceManager()->get('filterChainerCustom');
    $stripChainerFactory = new \Services\Filter\Helper\FilterChainerFactory(); 
    $BLL = $app->getBLLManager()->get('infoProjectCampaignBLL');  
    $headerParams = $app->request()->headers();
    if(!isset($headerParams['X-Public'])) throw new Exception ('rest api "pkUpdateAct_infoprojectcampaign" end point, X-Public variable not found');    
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
    $vehicleGroupId= NULL;
    if (isset($_GET['vehicle_group_id'])) {
         $stripper->offsetSet('vehicle_group_id',$stripChainerFactory->get(stripChainers::FILTER_ONLY_NUMBER_ALLOWED,
                                                $app,
                                                $_GET['vehicle_group_id']));
    } 
    $vehiclesEndgroupId = NULL;
    if (isset($_GET['vehicles_endgroup_id'])) {
         $stripper->offsetSet('vehicles_endgroup_id',$stripChainerFactory->get(stripChainers::FILTER_ONLY_NUMBER_ALLOWED,
                                                $app,
                                                $_GET['vehicles_endgroup_id']));
    }  
    $quantity = NULL;
    if (isset($_GET['quantity'])) {
         $stripper->offsetSet('quantity',$stripChainerFactory->get(stripChainers::FILTER_ONLY_NUMBER_ALLOWED,
                                                $app,
                                                $_GET['quantity']));
    } 
     
    $isother = NULL;
    if (isset($_GET['is_other'])) {
         $stripper->offsetSet('is_other',$stripChainerFactory->get(stripChainers::FILTER_ONLY_NUMBER_ALLOWED,
                                                $app,
                                                $_GET['is_other']));
    }
    $otherMonth= NULL;
    if (isset($_GET['other_month'])) {
         $stripper->offsetSet('other_month',$stripChainerFactory->get(stripChainers::FILTER_PARANOID_LEVEL2,
                                                $app,
                                                $_GET['other_month']));
    }
 
    $otherKm= NULL;
    if (isset($_GET['other_km'])) {
         $stripper->offsetSet('other_km',$stripChainerFactory->get(stripChainers::FILTER_PARANOID_LEVEL2,
                                                $app,
                                                $_GET['other_km']));
    }
    $otherDesc = NULL;
    if (isset($_GET['other_desc'])) {
         $stripper->offsetSet('other_desc',$stripChainerFactory->get(stripChainers::FILTER_PARANOID_LEVEL2,
                                                $app,
                                                $_GET['other_desc']));
    } 
    $campaignVehicleId= NULL;
    if (isset($_GET['campaign_vehicle_id'])) {
         $stripper->offsetSet('campaign_vehicle_id',$stripChainerFactory->get(stripChainers::FILTER_ONLY_NUMBER_ALLOWED,
                                                $app,
                                                $_GET['campaign_vehicle_id']));
    }               
    
    $stripper->strip();
    if($stripper->offsetExists('project_id')) $ProjectId = $stripper->offsetGet('project_id')->getFilterValue(); 
    if($stripper->offsetExists('vehicle_group_id')) $vehicleGroupId = $stripper->offsetGet('vehicle_group_id')->getFilterValue();
    if($stripper->offsetExists('vehicles_endgroup_id')) $vehiclesEndgroupId = $stripper->offsetGet('vehicles_endgroup_id')->getFilterValue();
    if($stripper->offsetExists('quantity')) $quantity = $stripper->offsetGet('quantity')->getFilterValue(); 
    
    if($stripper->offsetExists('campaign_vehicle_id')) $campaignVehicleId = $stripper->offsetGet('campaign_vehicle_id')->getFilterValue();  
    if($stripper->offsetExists('is_other')) $isother = $stripper->offsetGet('is_other')->getFilterValue(); 
    if($stripper->offsetExists('other_month')) $otherMonth = $stripper->offsetGet('other_month')->getFilterValue(); 
    if($stripper->offsetExists('other_km')) $otherKm = $stripper->offsetGet('other_km')->getFilterValue(); 
    if($stripper->offsetExists('other_desc')) $otherDesc = $stripper->offsetGet('other_desc')->getFilterValue(); 
    if($stripper->offsetExists('id')) $vId = $stripper->offsetGet('id')->getFilterValue();
     
          
    $resDataInsert = $BLL->updateAct(array(
            'Id' => $vId,    
            'ProjectId' => $ProjectId,   
            'VehicleGroupId' => $vehicleGroupId,
            'VehiclesEndgroupId' => $vehiclesEndgroupId,  
            'Quantity' => $quantity,   
            'IsOther' => $isother,  
            'OtherMmonth' => $otherMonth,   
            'OtherKm' => $otherKm,   
            'OtherDesc' => $otherDesc,   
            'CampaignVehicleId' => $campaignVehicleId,    
            'pk' => $pk));
        
    $app->response()->header("Content-Type", "application/json"); 
    $app->response()->body(json_encode($resDataInsert));
    
}
);
 
/**
 *  * Okan CIRAN
 * @since 15-08-2018
 */
$app->get("/pkDeletedAct_infoprojectcampaign/", function () use ($app ) {
    $stripper = $app->getServiceManager()->get('filterChainerCustom');
    $stripChainerFactory = new \Services\Filter\Helper\FilterChainerFactory();    
    $BLL = $app->getBLLManager()->get('infoProjectCampaignBLL');   
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