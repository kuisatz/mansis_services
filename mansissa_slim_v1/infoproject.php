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
$app->get("/pkFillProjectGridx_infoproject/", function () use ($app ) {
    $stripper = $app->getServiceManager()->get('filterChainerCustom');
    $stripChainerFactory = new \Services\Filter\Helper\FilterChainerFactory();
    $BLL = $app->getBLLManager()->get('infoProjectBLL');
    $headerParams = $app->request()->headers();
    if (!isset($headerParams['X-Public']))
        throw new Exception('rest api "pkFillProjectGridx_infoproject" end point, X-Public variable not found');
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

    $resDataGrid = $BLL->fillProjectGridx(array(
        'language_code' => $vLanguageCode,
        'LanguageID' => $lid,
        'page' => $vPage,
        'rows' => $vRows,
        'sort' => $vSort,
        'order' => $vOrder,
 
        'filterRules' => $filterRules,
        'pk' => $pk,
    ));
   
    $resTotalRowCount = $BLL->fillProjectGridxRtl(array(
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
                "deal_sis_key" =>  ($menu["deal_sis_key"]),
               "customer_id" =>  ($menu["customer_id"]),
                "registration_name" => html_entity_decode($menu["registration_name"]),
                "registration_number" => html_entity_decode($menu["registration_number"]),
                  "is_house_deal" =>  ($menu["is_house_deal"]),
                "probability_id" =>  ($menu["probability_id"]),
                "reliability_id" =>  ($menu["reliability_id"]),
                
                "probability_name" => html_entity_decode($menu["probability_name"]),
                "reliability_name" => html_entity_decode($menu["reliability_name"]),
                
                
             //    "deal_statu_type_id" => ($menu["deal_statu_type_id"]),
                "deal_statu" => html_entity_decode($menu["deal_statu"]),
                
             //    "body_statu_type_id" => ($menu["body_statu_type_id"]),
                "body_statu" => html_entity_decode($menu["body_statu"]),
                
              //   "accessory_statu_type_id" => ($menu["accessory_statu_type_id"]),
                "accessory_statu" => html_entity_decode($menu["accessory_statu"]),
                
              //   "tradein_statu_type_id" => ($menu["tradein_statu_type_id"]),
                "tradein_statu" => html_entity_decode($menu["tradein_statu"]),
                
              //   "buyback_statu_type_id" => ($menu["buyback_statu_type_id"]),
                "buyback_statu" => html_entity_decode($menu["buyback_statu"]),
                
             //    "tradeback_statu_type_id" => ($menu["tradeback_statu_type_id"]),
                "tradeback_statu" => html_entity_decode($menu["tradeback_statu"]),
                
           //      "rm_statu_type_id" => ($menu["rm_statu_type_id"]),
                "rm_statu" => html_entity_decode($menu["rm_statu"]),
                
            //     "waranty_statu_type_id" => ($menu["waranty_statu_type_id"]),
                "waranty_statu" => html_entity_decode($menu["waranty_statu"]),
                
           //      "invoice_statu_type_id" => ($menu["invoice_statu_type_id"]),
                "invoice_statu" => html_entity_decode($menu["invoice_statu"]),
                
           //       "delivery_statu_type_id" => ($menu["delivery_statu_type_id"]),
                "delivery_statu" => html_entity_decode($menu["delivery_statu"]),
                
                
           //     "credit_statu_type_id" => ($menu["credit_statu_type_id"]),
                "credit_statu" => html_entity_decode($menu["credit_statu"]),
                "discount_rate" => ($menu["discount_rate"]),
                "description" => html_entity_decode($menu["description"]),
           
                
             
                 
                
                
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
$app->get("/pkUpdateMakeActiveOrPassive_infoproject/", function () use ($app ) {
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
    $BLL = $app->getBLLManager()->get('infoProjectBLL');

    
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
$app->get("/pkInsertAct_infoproject/", function () use ($app ) {
    $stripper = $app->getServiceManager()->get('filterChainerCustom');
    $stripChainerFactory = new \Services\Filter\Helper\FilterChainerFactory(); 
    $BLL = $app->getBLLManager()->get('infoProjectBLL');  
    $headerParams = $app->request()->headers();
    if(!isset($headerParams['X-Public'])) throw new Exception ('rest api "pkInsertAct_infoproject" end point, X-Public variable not found');    
     $pk =  $headerParams['X-Public'];
      
    $embraceCustomerNo = NULL;
    if (isset($_GET['embrace_customer_no'])) {
         $stripper->offsetSet('embrace_customer_no',$stripChainerFactory->get(stripChainers::FILTER_PARANOID_LEVEL2,
                                                $app,
                                                $_GET['embrace_customer_no']));
    }  
    $tuEmbCustomerNo = NULL;
    if (isset($_GET['tu_emb_customer_no'])) {
         $stripper->offsetSet('tu_emb_customer_no',$stripChainerFactory->get(stripChainers::FILTER_PARANOID_LEVEL2,
                                                $app,
                                                $_GET['tu_emb_customer_no']));
    }  
    $ceEmbCustomerNo = NULL;
    if (isset($_GET['ce_emb_customer_no'])) {
         $stripper->offsetSet('ce_emb_customer_no',$stripChainerFactory->get(stripChainers::FILTER_PARANOID_LEVEL2,
                                                $app,
                                                $_GET['ce_emb_customer_no']));
    }  
    $otherEmbCustomerNo = NULL;
    if (isset($_GET['other_emb_customer_no'])) {
         $stripper->offsetSet('other_emb_customer_no',$stripChainerFactory->get(stripChainers::FILTER_PARANOID_LEVEL2,
                                                $app,
                                                $_GET['other_emb_customer_no']));
    }  
    $registrationName = NULL;
    if (isset($_GET['registration_name'])) {
         $stripper->offsetSet('registration_name',$stripChainerFactory->get(stripChainers::FILTER_PARANOID_LEVEL2,
                                                $app,
                                                $_GET['registration_name']));
    }  
    $tradingName = NULL;
    if (isset($_GET['trading_name'])) {
         $stripper->offsetSet('trading_name',$stripChainerFactory->get(stripChainers::FILTER_PARANOID_LEVEL2,
                                                $app,
                                                $_GET['trading_name']));
    }   
    $nameShort = NULL;
    if (isset($_GET['name_short'])) {
         $stripper->offsetSet('name_short',$stripChainerFactory->get(stripChainers::FILTER_PARANOID_LEVEL2,
                                                $app,
                                                $_GET['name_short']));
    }   
    $www = NULL;
    if (isset($_GET['www'])) {
         $stripper->offsetSet('www',$stripChainerFactory->get(stripChainers::FILTER_PARANOID_LEVEL2,
                                                $app,
                                                $_GET['www']));
    } 
    $vatnumber = NULL;
    if (isset($_GET['vatnumber'])) {
         $stripper->offsetSet('vatnumber',$stripChainerFactory->get(stripChainers::FILTER_PARANOID_LEVEL2,
                                                $app,
                                                $_GET['vatnumber']));
    } 
    $registrationNumber = NULL;
    if (isset($_GET['registration_number'])) {
         $stripper->offsetSet('registration_number',$stripChainerFactory->get(stripChainers::FILTER_PARANOID_LEVEL2,
                                                $app,
                                                $_GET['registration_number']));
    } 
    $registrationDate = NULL;
    if (isset($_GET['registration_date'])) {
         $stripper->offsetSet('registration_date',$stripChainerFactory->get(stripChainers::FILTER_PARANOID_LEVEL2,
                                                $app,
                                                $_GET['registration_date']));
    }  
    
    $neCountTypeId = NULL;
    if (isset($_GET['ne_count_type_id'])) {
         $stripper->offsetSet('ne_count_type_id',$stripChainerFactory->get(stripChainers::FILTER_ONLY_NUMBER_ALLOWED,
                                                $app,
                                                $_GET['ne_count_type_id']));
    } 
    $nvCountTypeId = NULL;
    if (isset($_GET['nv_count_type_id'])) {
         $stripper->offsetSet('nv_count_type_id',$stripChainerFactory->get(stripChainers::FILTER_ONLY_NUMBER_ALLOWED,
                                                $app,
                                                $_GET['nv_count_type_id']));
    } 
    $customerCategoryId = NULL;
    if (isset($_GET['customer_category_id'])) {
         $stripper->offsetSet('customer_category_id',$stripChainerFactory->get(stripChainers::FILTER_ONLY_NUMBER_ALLOWED,
                                                $app,
                                                $_GET['customer_category_id']));
    } 
    $reliabilityId = NULL;
    if (isset($_GET['reliability_id'])) {
         $stripper->offsetSet('reliability_id',$stripChainerFactory->get(stripChainers::FILTER_ONLY_NUMBER_ALLOWED,
                                                $app,
                                                $_GET['reliability_id']));
    } 
    $turnoverRateId = NULL;
    if (isset($_GET['turnover_rate_id'])) {
         $stripper->offsetSet('turnover_rate_id',$stripChainerFactory->get(stripChainers::FILTER_ONLY_NUMBER_ALLOWED,
                                                $app,
                                                $_GET['turnover_rate_id']));
    } 
    $sectorTypeId = NULL;
    if (isset($_GET['sector_type_id'])) {
         $stripper->offsetSet('sector_type_id',$stripChainerFactory->get(stripChainers::FILTER_ONLY_NUMBER_ALLOWED,
                                                $app,
                                                $_GET['sector_type_id']));
    } 
    $applicationTypeId = NULL;
    if (isset($_GET['application_type_id'])) {
         $stripper->offsetSet('application_type_id',$stripChainerFactory->get(stripChainers::FILTER_ONLY_NUMBER_ALLOWED,
                                                $app,
                                                $_GET['application_type_id']));
    } 
    $segmentTypeId = NULL;
    if (isset($_GET['segment_type_id'])) {
         $stripper->offsetSet('segment_type_id',$stripChainerFactory->get(stripChainers::FILTER_ONLY_NUMBER_ALLOWED,
                                                $app,
                                                $_GET['segment_type_id']));
    } 
     
    $stripper->strip();
    if($stripper->offsetExists('embrace_customer_no')) $embraceCustomerNo = $stripper->offsetGet('embrace_customer_no')->getFilterValue(); 
    if($stripper->offsetExists('tu_emb_customer_no')) $tuEmbCustomerNo = $stripper->offsetGet('tu_emb_customer_no')->getFilterValue();
    if($stripper->offsetExists('ce_emb_customer_no')) $ceEmbCustomerNo = $stripper->offsetGet('ce_emb_customer_no')->getFilterValue();
    if($stripper->offsetExists('other_emb_customer_no')) $otherEmbCustomerNo = $stripper->offsetGet('other_emb_customer_no')->getFilterValue();
    if($stripper->offsetExists('registration_name')) $registrationName = $stripper->offsetGet('registration_name')->getFilterValue();
    if($stripper->offsetExists('trading_name')) $tradingName = $stripper->offsetGet('trading_name')->getFilterValue();
    if($stripper->offsetExists('name_short')) $nameShort = $stripper->offsetGet('name_short')->getFilterValue();
    if($stripper->offsetExists('www')) $www = $stripper->offsetGet('www')->getFilterValue();
    if($stripper->offsetExists('vatnumber')) $vatnumber = $stripper->offsetGet('vatnumber')->getFilterValue();
    if($stripper->offsetExists('registration_number')) $registrationNumber = $stripper->offsetGet('registration_number')->getFilterValue();
    if($stripper->offsetExists('registration_date')) $registrationDate = $stripper->offsetGet('registration_date')->getFilterValue();
    if($stripper->offsetExists('ne_count_type_id')) $neCountTypeId = $stripper->offsetGet('ne_count_type_id')->getFilterValue();
    if($stripper->offsetExists('nv_count_type_id')) $nvCountTypeId = $stripper->offsetGet('nv_count_type_id')->getFilterValue();     
    if($stripper->offsetExists('customer_category_id')) $customerCategoryId= $stripper->offsetGet('customer_category_id')->getFilterValue();
    if($stripper->offsetExists('reliability_id')) $reliabilityId = $stripper->offsetGet('reliability_id')->getFilterValue();
    if($stripper->offsetExists('sector_type_id')) $sectorTypeId  = $stripper->offsetGet('sector_type_id')->getFilterValue();
    if($stripper->offsetExists('application_type_id')) $applicationTypeId = $stripper->offsetGet('application_type_id')->getFilterValue();
    if($stripper->offsetExists('segment_type_id')) $segmentTypeId = $stripper->offsetGet('segment_type_id')->getFilterValue();
  
    $resDataInsert = $BLL->insertAct(array(
            'EmbraceCustomerNo' => $embraceCustomerNo,   
            'TuEmbCustomerNo' => $tuEmbCustomerNo,  
            'CeEmbCustomerNo' => $ceEmbCustomerNo,  
            'OtherEmbCustomerNo' => $otherEmbCustomerNo,  
            'RegistrationName' => $registrationName,  
            'TradingName' => $tradingName,  
            'NameShort' => $nameShort,  
            'www' => $www,  
            'Vatnumber' => $vatnumber,  
            'RegistrationNumber' => $registrationNumber,  
            'RegistrationDate' => $registrationDate,  
            'NeCountTypeId' => $neCountTypeId,   
            'NvCountTypeId' => $nvCountTypeId,  
            'CustomerCategoryId' => $customerCategoryId,  
            'ReliabilityId' => $reliabilityId,  
            'SectorTypeId' => $sectorTypeId,   
            'ApplicationTypeId' => $applicationTypeId,  
            'SegmentTypeId' => $segmentTypeId,  
          
            'pk' => $pk));
        
    $app->response()->header("Content-Type", "application/json"); 
    $app->response()->body(json_encode($resDataInsert));
    
}
);

/**
 *  * Okan CIRAN
 * @since 15-08-2018
 */ 
$app->get("/pkUpdateAct_infoproject/", function () use ($app ) {
    $stripper = $app->getServiceManager()->get('filterChainerCustom');
    $stripChainerFactory = new \Services\Filter\Helper\FilterChainerFactory(); 
    $BLL = $app->getBLLManager()->get('infoProjectBLL');  
    $headerParams = $app->request()->headers();
    if(!isset($headerParams['X-Public'])) throw new Exception ('rest api "pkUpdateAct_infoproject" end point, X-Public variable not found');    
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
    $vehicleGroupId = NULL;
    if (isset($_GET['vehicle_group_id'])) {
         $stripper->offsetSet('vehicle_group_id',$stripChainerFactory->get(stripChainers::FILTER_ONLY_NUMBER_ALLOWED,
                                                $app,
                                                $_GET['vehicle_group_id']));
    } 
     
    $stripper->strip();
    if($stripper->offsetExists('name')) $vName = $stripper->offsetGet('name')->getFilterValue(); 
    if($stripper->offsetExists('vehicle_group_id')) $vehicleGroupId = $stripper->offsetGet('vehicle_group_id')->getFilterValue();
    if($stripper->offsetExists('id')) $vId = $stripper->offsetGet('id')->getFilterValue();
     
          
    $resDataInsert = $BLL->updateAct(array(
            'Id' => $vId,   
            'Name' => $vName,   
            'VehicleGroupId' => $vehicleGroupId,  
            'pk' => $pk));
        
    $app->response()->header("Content-Type", "application/json"); 
    $app->response()->body(json_encode($resDataInsert));
    
}
);
 
/**
 *  * Okan CIRAN
 * @since 15-08-2018
 */
$app->get("/pkDeletedAct_infoproject/", function () use ($app ) {
    $stripper = $app->getServiceManager()->get('filterChainerCustom');
    $stripChainerFactory = new \Services\Filter\Helper\FilterChainerFactory();    
    $BLL = $app->getBLLManager()->get('infoProjectBLL');   
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