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
$app->get("/pkCustomeractivAtionsDdList_infocustomeractivations/", function () use ($app ) {
    $stripper = $app->getServiceManager()->get('filterChainerCustom');
    $stripChainerFactory = new \Services\Filter\Helper\FilterChainerFactory(); 
    $BLL = $app->getBLLManager()->get('infoCustomerActivationsBLL');
    
    $componentType = 'ddslick';
    if (isset($_GET['component_type'])) {
        $componentType = strtolower(trim($_GET['component_type']));
    }
    $headerParams = $app->request()->headers();
    if(!isset($headerParams['X-Public'])) throw new Exception ('rest api "pkCustomeractivAtionsDdList_infocustomeractivations" end point, X-Public variable not found');
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
      $customerId = NULL;
    if (isset($_GET['customer_id'])) {
         $stripper->offsetSet('customer_id',$stripChainerFactory->get(stripChainers::FILTER_ONLY_NUMBER_ALLOWED,
                                                $app,
                                                $_GET['customer_id']));
    } 
    $stripper->strip();
    if($stripper->offsetExists('lid')) $lid = $stripper->offsetGet('lid')->getFilterValue();
    if($stripper->offsetExists('customer_id')) $customerId = $stripper->offsetGet('customer_id')->getFilterValue();
    if($stripper->offsetExists('language_code')) $vLanguageCode = $stripper->offsetGet('language_code')->getFilterValue();
        
    $resCombobox = $BLL->customeractivAtionsDdList(array(                                   
                                    'language_code' => $vLanguageCode,
                                    'LanguageID' => $lid,
                                    'CustomerId' => $customerId,
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
$app->get("/pkFillCustomeractivationsGridx_infocustomeractivations/", function () use ($app ) {
    $stripper = $app->getServiceManager()->get('filterChainerCustom');
    $stripChainerFactory = new \Services\Filter\Helper\FilterChainerFactory();
    $BLL = $app->getBLLManager()->get('infoCustomerActivationsBLL');
    $headerParams = $app->request()->headers();
    if (!isset($headerParams['X-Public']))
        throw new Exception('rest api "pkFillCustomeractivationsGridx_infocustomeractivations" end point, X-Public variable not found');
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
     $customerId = NULL;
    if (isset($_GET['customer_id'])) {
         $stripper->offsetSet('customer_id',$stripChainerFactory->get(stripChainers::FILTER_ONLY_NUMBER_ALLOWED,
                                                $app,
                                                $_GET['customer_id']));
    } 
    $stripper->strip();
    if($stripper->offsetExists('lid')) $lid = $stripper->offsetGet('lid')->getFilterValue();
    if ($stripper->offsetExists('language_code')) $vLanguageCode = $stripper->offsetGet('language_code')->getFilterValue();    
 
    if ($stripper->offsetExists('page')) { $vPage = $stripper->offsetGet('page')->getFilterValue(); }
    if ($stripper->offsetExists('rows')) { $vRows = $stripper->offsetGet('rows')->getFilterValue(); }
    if ($stripper->offsetExists('sort')) { $vSort = $stripper->offsetGet('sort')->getFilterValue(); }
    if ($stripper->offsetExists('order')) { $vOrder = $stripper->offsetGet('order')->getFilterValue(); }
    if ($stripper->offsetExists('filterRules')) { $filterRules = $stripper->offsetGet('filterRules')->getFilterValue(); } 
    if ($stripper->offsetExists('customer_id')) { $customerId = $stripper->offsetGet('customer_id')->getFilterValue(); }

    $resDataGrid = $BLL->fillCustomeractivationsGridx(array(
        'language_code' => $vLanguageCode,
        'LanguageID' => $lid,
        'page' => $vPage,
        'rows' => $vRows,
        'sort' => $vSort,
        'order' => $vOrder,
        'CustomerId' => $customerId,
 
        'filterRules' => $filterRules,
        'pk' => $pk,
    ));
   
    $resTotalRowCount = $BLL->fillCustomeractivationsGridxRtl(array(
        'language_code' => $vLanguageCode, 
        'LanguageID' => $lid,
        'CustomerId' => $customerId,
  
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
                "registration_name" => html_entity_decode($menu["registration_name"]),
                "trading_name" => html_entity_decode($menu["trading_name"]),
             
           /*     "embrace_customer_no" => html_entity_decode($menu["embrace_customer_no"]),
                "tu_emb_customer_no" => html_entity_decode($menu["tu_emb_customer_no"]),
                "ce_emb_customer_no" => html_entity_decode($menu["ce_emb_customer_no"]),
                "other_emb_customer_no" => html_entity_decode($menu["other_emb_customer_no"]),
            */ 
                "www" => html_entity_decode($menu["www"]),
                "vatnumber" => html_entity_decode($menu["vatnumber"]),
                "registration_number" => html_entity_decode($menu["registration_number"]),
                "registration_date" =>  ($menu["registration_date"]), 
                "customer_segment_type_id" =>  ($menu["customer_segment_type_id"]),
                "segment_type_name" => html_entity_decode($menu["segment_type_name"]),
                
                "cs_activation_type_id" =>  ($menu["cs_activation_type_id"]),
                "activation_type_name" => html_entity_decode($menu["activation_type_name"]),
                "act_date" =>  ($menu["act_date"]),
                "cs_statu_types_id" =>  ($menu["cs_statu_types_id"]),
                "statu_types_name" => html_entity_decode($menu["statu_types_name"]),
                
                "cs_act_statutype_id" =>  ($menu["cs_act_statutype_id"]),
                "cs_act_statutype_name" => html_entity_decode($menu["cs_act_statutype_name"]),
                 
                "project_id" => intval($menu["project_id"]),  

                "vehicle_model_id" =>  ($menu["vehicle_model_id"]),
                "vehicle_model_name" => html_entity_decode($menu["vehicle_model_name"]),
                "description" => html_entity_decode($menu["description"]),
                "manager_description" => html_entity_decode($menu["manager_description"]),
               
                "name" => html_entity_decode($menu["name"]),
             
                "email" => html_entity_decode($menu["email"]),
                "mobile" => html_entity_decode($menu["mobile"]),
                "phone" => html_entity_decode($menu["phone"]),
                "fax" => html_entity_decode($menu["fax"]),
                 
                "activity_tracking_date" =>  ($menu["activity_tracking_date"]),
                "activty_tracking_type_id" =>  ($menu["activty_tracking_type_id"]),
                "activty_tracking_type_name" =>  (""),
                 
                "report" => html_entity_decode($menu["report"]),
                "realization_date" =>  ($menu["realization_date"]),
                "is_done" =>  ($menu["is_done"]),
           
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
$app->get("/pkUpdateMakeActiveOrPassive_infocustomeractivations/", function () use ($app ) {
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
    $BLL = $app->getBLLManager()->get('infoCustomerActivationsBLL');

    
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
$app->get("/pkInsertAct_infocustomeractivations/", function () use ($app ) { 
    $stripper = $app->getServiceManager()->get('filterChainerCustom');
    $stripChainerFactory = new \Services\Filter\Helper\FilterChainerFactory(); 
    $BLL = $app->getBLLManager()->get('infoCustomerActivationsBLL');  
    $headerParams = $app->request()->headers();
    if(!isset($headerParams['X-Public'])) throw new Exception ('rest api "pkInsertAct_infocustomeractivations" end point, X-Public variable not found');    
     $pk =  $headerParams['X-Public'];
      
    
    $ActDate = NULL;
    if (isset($_GET['act_date'])) {
         $stripper->offsetSet('act_date',$stripChainerFactory->get(stripChainers::FILTER_PARANOID_LEVEL2,
                                                $app,
                                                $_GET['act_date']));
    }  
    $CustomerId= NULL;
    if (isset($_GET['customer_id'])) {
        $stripper->offsetSet('customer_id', $stripChainerFactory->get(stripChainers::FILTER_ONLY_NUMBER_ALLOWED,  $app,   $_GET['customer_id']));
    }
    $ContactPersonId= NULL;
    if (isset($_GET['contact_person_id'])) {
        $stripper->offsetSet('contact_person_id', $stripChainerFactory->get(stripChainers::FILTER_ONLY_NUMBER_ALLOWED,  $app,   $_GET['contact_person_id']));
    }
    $CsActivationTypeId= NULL;
    if (isset($_GET['cs_activation_type_id'])) {
        $stripper->offsetSet('cs_activation_type_id', $stripChainerFactory->get(stripChainers::FILTER_ONLY_NUMBER_ALLOWED,  $app,   $_GET['cs_activation_type_id']));
    }
    $CsStatuTypesId= NULL;
    if (isset($_GET['cs_statu_types_id'])) {
        $stripper->offsetSet('cs_statu_types_id', $stripChainerFactory->get(stripChainers::FILTER_ONLY_NUMBER_ALLOWED,  $app,   $_GET['cs_statu_types_id']));
    }
    $CsActStatutypeId= NULL;
    if (isset($_GET['cs_act_statutype_id'])) {
        $stripper->offsetSet('cs_act_statutype_id', $stripChainerFactory->get(stripChainers::FILTER_ONLY_NUMBER_ALLOWED,  $app,   $_GET['cs_act_statutype_id']));
    }
    $ProjectId= NULL;
    if (isset($_GET['project_id'])) {
        $stripper->offsetSet('project_id', $stripChainerFactory->get(stripChainers::FILTER_ONLY_NUMBER_ALLOWED,  $app,   $_GET['project_id']));
    }
    $CustomerSegmentTypeId= NULL;
    if (isset($_GET['customer_segment_type_id'])) {
        $stripper->offsetSet('customer_segment_type_id', $stripChainerFactory->get(stripChainers::FILTER_ONLY_NUMBER_ALLOWED,  $app,   $_GET['customer_segment_type_id']));
    }
    $VehicleModelId= NULL;
    if (isset($_GET['vehicle_model_id'])) {
        $stripper->offsetSet('vehicle_model_id', $stripChainerFactory->get(stripChainers::FILTER_ONLY_NUMBER_ALLOWED,  $app,   $_GET['vehicle_model_id']));
    }
    $Description = NULL;
    if (isset($_GET['description'])) {
         $stripper->offsetSet('description',$stripChainerFactory->get(stripChainers::FILTER_PARANOID_LEVEL2,
                                                $app,
                                                $_GET['description']));
    }   
    $ManagerDescription = NULL;
    if (isset($_GET['manager_description'])) {
         $stripper->offsetSet('manager_description',$stripChainerFactory->get(stripChainers::FILTER_PARANOID_LEVEL2,
                                                $app,
                                                $_GET['manager_description']));
    }  
     $ActivityTrackingDate = NULL;
    if (isset($_GET['activity_tracking_date'])) {
         $stripper->offsetSet('activity_tracking_date',$stripChainerFactory->get(stripChainers::FILTER_PARANOID_LEVEL2,
                                                $app,
                                                $_GET['activity_tracking_date']));
    }  
     $activtyTrackingTypeId= NULL;
    if (isset($_GET['activty_tracking_type_id'])) {
        $stripper->offsetSet('activty_tracking_type_id', $stripChainerFactory->get(stripChainers::FILTER_ONLY_NUMBER_ALLOWED,  $app,   $_GET['activty_tracking_type_id']));
    }
     $realization = NULL;
    if (isset($_GET['realization_date'])) {
         $stripper->offsetSet('realization_date',$stripChainerFactory->get(stripChainers::FILTER_PARANOID_LEVEL2,
                                                $app,
                                                $_GET['realization_date']));
    }  
     $report = NULL;
    if (isset($_GET['report'])) {
         $stripper->offsetSet('report',$stripChainerFactory->get(stripChainers::FILTER_PARANOID_LEVEL2,
                                                $app,
                                                $_GET['report']));
    }  
     
    $stripper->strip();
  
    
    if($stripper->offsetExists('act_date')) $ActDate = $stripper->offsetGet('act_date')->getFilterValue();
    if($stripper->offsetExists('customer_id')) $CustomerId = $stripper->offsetGet('customer_id')->getFilterValue();
    if($stripper->offsetExists('contact_person_id')) $ContactPersonId = $stripper->offsetGet('contact_person_id')->getFilterValue();
    if($stripper->offsetExists('cs_activation_type_id')) $CsActivationTypeId = $stripper->offsetGet('cs_activation_type_id')->getFilterValue();
    if($stripper->offsetExists('cs_statu_types_id')) $CsStatuTypesId = $stripper->offsetGet('cs_statu_types_id')->getFilterValue();
    if($stripper->offsetExists('cs_act_statutype_id')) $CsActStatutypeId = $stripper->offsetGet('cs_act_statutype_id')->getFilterValue();
    if($stripper->offsetExists('project_id')) $ProjectId = $stripper->offsetGet('project_id')->getFilterValue();
    if($stripper->offsetExists('customer_segment_type_id')) $CustomerSegmentTypeId = $stripper->offsetGet('customer_segment_type_id')->getFilterValue();     
    if($stripper->offsetExists('vehicle_model_id')) $VehicleModelId= $stripper->offsetGet('vehicle_model_id')->getFilterValue();
    if($stripper->offsetExists('description')) $Description = $stripper->offsetGet('description')->getFilterValue();
    if($stripper->offsetExists('manager_description')) $ManagerDescription = $stripper->offsetGet('manager_description')->getFilterValue();
    if($stripper->offsetExists('activity_tracking_date')) $ActivityTrackingDate = $stripper->offsetGet('activity_tracking_date')->getFilterValue();
    if($stripper->offsetExists('activty_tracking_type_id')) $activtyTrackingTypeId = $stripper->offsetGet('activty_tracking_type_id')->getFilterValue();
    if($stripper->offsetExists('realization_date')) $realization = $stripper->offsetGet('realization_date')->getFilterValue();
    if($stripper->offsetExists('report')) $report = $stripper->offsetGet('report')->getFilterValue();
  
    $resDataInsert = $BLL->insertAct(array( 
            'CustomerId' => $CustomerId,  
            'ContactPersonId' => $ContactPersonId,  
            'CsActivationTypeId' => $CsActivationTypeId,   
            'ActDate' => $ActDate,  
            'CsStatuTypesId' => $CsStatuTypesId,  
            'CsActStatutypeId' => $CsActStatutypeId,  
            'ProjectId' => $ProjectId,    
            'CustomerSegmentTypeId' => $CustomerSegmentTypeId,     
            'VehicleModelId' => $VehicleModelId,   
            'Description' => $Description,  
            'ManagerDescription' => $ManagerDescription,   
            'ActivityTrackingDate' => $ActivityTrackingDate,   
            'ActivtyTrackingTypeId' => $activtyTrackingTypeId,   
            'RealizationDate' => $realization,   
            'report' => $report,   
          
            'pk' => $pk));
        
    $app->response()->header("Content-Type", "application/json"); 
    $app->response()->body(json_encode($resDataInsert));
    
}
);
 
/**
 *  * Okan CIRAN
 * @since 15-08-2018
 */ 
$app->get("/pkUpdateAct_infocustomeractivations/", function () use ($app ) { 
    $stripper = $app->getServiceManager()->get('filterChainerCustom');
    $stripChainerFactory = new \Services\Filter\Helper\FilterChainerFactory(); 
    $BLL = $app->getBLLManager()->get('infoCustomerActivationsBLL');  
    $headerParams = $app->request()->headers();
    if(!isset($headerParams['X-Public'])) throw new Exception ('rest api "pkUpdateAct_infocustomeractivations" end point, X-Public variable not found');    
     $pk =  $headerParams['X-Public'];
      
     $vId = NULL;
    if (isset($_GET['id'])) {
         $stripper->offsetSet('id',$stripChainerFactory->get(stripChainers::FILTER_ONLY_NUMBER_ALLOWED,
                                                $app,
                                                $_GET['id']));
    } 
    $ActDate = NULL;
    if (isset($_GET['act_date'])) {
         $stripper->offsetSet('act_date',$stripChainerFactory->get(stripChainers::FILTER_PARANOID_LEVEL2,
                                                $app,
                                                $_GET['act_date']));
    }  
    $CustomerId= NULL;
    if (isset($_GET['customer_id'])) {
        $stripper->offsetSet('customer_id', $stripChainerFactory->get(stripChainers::FILTER_ONLY_NUMBER_ALLOWED,  $app,   $_GET['customer_id']));
    }
    $ContactPersonId= NULL;
    if (isset($_GET['contact_person_id'])) {
        $stripper->offsetSet('contact_person_id', $stripChainerFactory->get(stripChainers::FILTER_ONLY_NUMBER_ALLOWED,  $app,   $_GET['contact_person_id']));
    }
    $CsActivationTypeId= NULL;
    if (isset($_GET['cs_activation_type_id'])) {
        $stripper->offsetSet('cs_activation_type_id', $stripChainerFactory->get(stripChainers::FILTER_ONLY_NUMBER_ALLOWED,  $app,   $_GET['cs_activation_type_id']));
    }
    $CsStatuTypesId= NULL;
    if (isset($_GET['cs_statu_types_id'])) {
        $stripper->offsetSet('cs_statu_types_id', $stripChainerFactory->get(stripChainers::FILTER_ONLY_NUMBER_ALLOWED,  $app,   $_GET['cs_statu_types_id']));
    }
    $CsActStatutypeId= NULL;
    if (isset($_GET['cs_act_statutype_id'])) {
        $stripper->offsetSet('cs_act_statutype_id', $stripChainerFactory->get(stripChainers::FILTER_ONLY_NUMBER_ALLOWED,  $app,   $_GET['cs_act_statutype_id']));
    }
    $ProjectId= NULL;
    if (isset($_GET['project_id'])) {
        $stripper->offsetSet('project_id', $stripChainerFactory->get(stripChainers::FILTER_ONLY_NUMBER_ALLOWED,  $app,   $_GET['project_id']));
    }
    $CustomerSegmentTypeId= NULL;
    if (isset($_GET['customer_segment_type_id'])) {
        $stripper->offsetSet('customer_segment_type_id', $stripChainerFactory->get(stripChainers::FILTER_ONLY_NUMBER_ALLOWED,  $app,   $_GET['customer_segment_type_id']));
    }
    $VehicleModelId= NULL;
    if (isset($_GET['vehicle_model_id'])) {
        $stripper->offsetSet('vehicle_model_id', $stripChainerFactory->get(stripChainers::FILTER_ONLY_NUMBER_ALLOWED,  $app,   $_GET['vehicle_model_id']));
    }
    $Description = NULL;
    if (isset($_GET['description'])) {
         $stripper->offsetSet('description',$stripChainerFactory->get(stripChainers::FILTER_PARANOID_LEVEL2,
                                                $app,
                                                $_GET['description']));
    }   
    $ManagerDescription = NULL;
    if (isset($_GET['manager_description'])) {
         $stripper->offsetSet('manager_description',$stripChainerFactory->get(stripChainers::FILTER_PARANOID_LEVEL2,
                                                $app,
                                                $_GET['manager_description']));
    }  
    $ActivityTrackingDate = NULL;
    if (isset($_GET['activity_tracking_date'])) {
         $stripper->offsetSet('activity_tracking_date',$stripChainerFactory->get(stripChainers::FILTER_PARANOID_LEVEL2,
                                                $app,
                                                $_GET['activity_tracking_date']));
    }  
     $activtyTrackingTypeId= NULL;
    if (isset($_GET['activty_tracking_type_id'])) {
        $stripper->offsetSet('activty_tracking_type_id', $stripChainerFactory->get(stripChainers::FILTER_ONLY_NUMBER_ALLOWED,  $app,   $_GET['activty_tracking_type_id']));
    }
    $realization = NULL;
    if (isset($_GET['realization_date'])) {
         $stripper->offsetSet('realization_date',$stripChainerFactory->get(stripChainers::FILTER_PARANOID_LEVEL2,
                                                $app,
                                                $_GET['realization_date']));
    }  
    $report = NULL;
    if (isset($_GET['report'])) {
         $stripper->offsetSet('report',$stripChainerFactory->get(stripChainers::FILTER_PARANOID_LEVEL2,
                                                $app,
                                                $_GET['report']));
    }  
    $IsDone= NULL;
    if (isset($_GET['is_done'])) {
        $stripper->offsetSet('is_done', $stripChainerFactory->get(stripChainers::FILTER_ONLY_NUMBER_ALLOWED,  $app,   $_GET['is_done']));
    }
    
    // &act_date=2018-10-12&customer_id=1&contact_person_id=2&cs_activation_type_id=2&cs_statu_types_id=1&cs_act_statutype_id=1&project_id=2&customer_segment_type_id=2&vehicle_model_id=3&description=32222&manager_description=
    
    
     
    $stripper->strip();
    
    
    if($stripper->offsetExists('act_date')) $ActDate = $stripper->offsetGet('act_date')->getFilterValue();
    if($stripper->offsetExists('customer_id')) $CustomerId = $stripper->offsetGet('customer_id')->getFilterValue();
    if($stripper->offsetExists('contact_person_id')) $ContactPersonId = $stripper->offsetGet('contact_person_id')->getFilterValue();
    if($stripper->offsetExists('cs_activation_type_id')) $CsActivationTypeId = $stripper->offsetGet('cs_activation_type_id')->getFilterValue();
    if($stripper->offsetExists('cs_statu_types_id')) $CsStatuTypesId = $stripper->offsetGet('cs_statu_types_id')->getFilterValue();
    if($stripper->offsetExists('cs_act_statutype_id')) $CsActStatutypeId = $stripper->offsetGet('cs_act_statutype_id')->getFilterValue();
    if($stripper->offsetExists('project_id')) $ProjectId = $stripper->offsetGet('project_id')->getFilterValue();
    if($stripper->offsetExists('customer_segment_type_id')) $CustomerSegmentTypeId = $stripper->offsetGet('customer_segment_type_id')->getFilterValue();     
    if($stripper->offsetExists('vehicle_model_id')) $VehicleModelId= $stripper->offsetGet('vehicle_model_id')->getFilterValue();
    if($stripper->offsetExists('description')) $Description = $stripper->offsetGet('description')->getFilterValue();
    if($stripper->offsetExists('manager_description')) $ManagerDescription = $stripper->offsetGet('manager_description')->getFilterValue();
    if($stripper->offsetExists('activity_tracking_date')) $ActivityTrackingDate = $stripper->offsetGet('activity_tracking_date')->getFilterValue();
    if($stripper->offsetExists('activty_tracking_type_id')) $activtyTrackingTypeId = $stripper->offsetGet('activty_tracking_type_id')->getFilterValue();
    if($stripper->offsetExists('realization_date')) $realization = $stripper->offsetGet('realization_date')->getFilterValue();
    if($stripper->offsetExists('report')) $report = $stripper->offsetGet('report')->getFilterValue();
    if($stripper->offsetExists('is_done')) $IsDone = $stripper->offsetGet('is_done')->getFilterValue();
    if($stripper->offsetExists('id')) $vId = $stripper->offsetGet('id')->getFilterValue();
  
    $resDataInsert = $BLL->updateAct(array( 
            'CustomerId' => $CustomerId,  
            'ContactPersonId' => $ContactPersonId,  
            'CsActivationTypeId' => $CsActivationTypeId,   
            'ActDate' => $ActDate,  
            'CsStatuTypesId' => $CsStatuTypesId,  
            'CsActStatutypeId' => $CsActStatutypeId,  
            'ProjectId' => $ProjectId,    
            'CustomerSegmentTypeId' => $CustomerSegmentTypeId,     
            'VehicleModelId' => $VehicleModelId,   
            'Description' => $Description,  
            'ManagerDescription' => $ManagerDescription,  
            'ActivityTrackingDate' => $ActivityTrackingDate,   
            'ActivtyTrackingTypeId' => $activtyTrackingTypeId, 
            'RealizationDate' => $realization,   
            'report' => $report,   
            'IsDone' => $IsDone,
            'Id' => $vId,
            'pk' => $pk));
        
    $app->response()->header("Content-Type", "application/json"); 
    $app->response()->body(json_encode($resDataInsert));
    
}
);
 
/**
 *  * Okan CIRAN
 * @since 15-08-2018
 */
$app->get("/pkDeletedAct_infocustomeractivations/", function () use ($app ) {
    $stripper = $app->getServiceManager()->get('filterChainerCustom');
    $stripChainerFactory = new \Services\Filter\Helper\FilterChainerFactory();    
    $BLL = $app->getBLLManager()->get('infoCustomerActivationsBLL');   
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