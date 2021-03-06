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
                "deal_name" => html_entity_decode($menu["deal_name"]),
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
      
    $customer = NULL;
    if (isset($_GET['customer_id'])) {
         $stripper->offsetSet('customer_id',$stripChainerFactory->get(stripChainers::FILTER_ONLY_NUMBER_ALLOWED,
                                                $app,
                                                $_GET['customer_id']));
    }  
    $isHouseDeal = NULL;
    if (isset($_GET['is_house_deal'])) {
         $stripper->offsetSet('is_house_deal',$stripChainerFactory->get(stripChainers::FILTER_ONLY_NUMBER_ALLOWED,
                                                $app,
                                                $_GET['is_house_deal']));
    }  
    $probabilityId = NULL;
    if (isset($_GET['probability_id'])) {
         $stripper->offsetSet('probability_id',$stripChainerFactory->get(stripChainers::FILTER_ONLY_NUMBER_ALLOWED,
                                                $app,
                                                $_GET['probability_id']));
    }  
    $description = NULL;
    if (isset($_GET['description'])) {
         $stripper->offsetSet('description',$stripChainerFactory->get(stripChainers::FILTER_PARANOID_LEVEL2,
                                                $app,
                                                $_GET['description']));
    }   
    $reliabilityId = NULL;
    if (isset($_GET['reliability_id'])) {
         $stripper->offsetSet('reliability_id',$stripChainerFactory->get(stripChainers::FILTER_ONLY_NUMBER_ALLOWED,
                                                $app,
                                                $_GET['reliability_id']));
    } 
    $discountRate = NULL;
    if (isset($_GET['discount_rate'])) {
         $stripper->offsetSet('discount_rate',$stripChainerFactory->get(stripChainers::FILTER_ONLY_NUMBER_ALLOWED,
                                                $app,
                                                $_GET['discount_rate']));
    } 
    $dealName = NULL;
    if (isset($_GET['deal_name'])) {
         $stripper->offsetSet('deal_name',$stripChainerFactory->get(stripChainers::FILTER_PARANOID_LEVEL2,
                                                $app,
                                                $_GET['deal_name']));
    }  
   
    // &customer_id=1&is_house_deal=0&probability_id=2&reliability_id=2&description=denemeemememe&discount_rate=1234
    
    
     
    $stripper->strip();
    if($stripper->offsetExists('customer_id')) $customer = $stripper->offsetGet('customer_id')->getFilterValue(); 
    if($stripper->offsetExists('is_house_deal')) $isHouseDeal = $stripper->offsetGet('is_house_deal')->getFilterValue();
    if($stripper->offsetExists('probability_id')) $probabilityId = $stripper->offsetGet('probability_id')->getFilterValue();
    if($stripper->offsetExists('description')) $description = $stripper->offsetGet('description')->getFilterValue(); 
    if($stripper->offsetExists('discount_rate')) $discountRate = $stripper->offsetGet('discount_rate')->getFilterValue(); 
    if($stripper->offsetExists('deal_name')) $dealName = $stripper->offsetGet('deal_name')->getFilterValue(); 
    if($stripper->offsetExists('reliability_id')) $reliabilityId = $stripper->offsetGet('reliability_id')->getFilterValue();
 
  
    $resDataInsert = $BLL->insertAct(array(
            'CustomerId' => $customer,   
            'IsHouseDeal' => $isHouseDeal,  
            'ProbabilityId' => $probabilityId,  
            'Description' => $description,  
            'DiscountRate' => $discountRate,  
            'ReliabilityId' => $reliabilityId,   
            'ReliabilityId' => $reliabilityId,   
            'DealName' => $dealName,   
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
    
    $customer = NULL;
    if (isset($_GET['customer_id'])) {
         $stripper->offsetSet('customer_id',$stripChainerFactory->get(stripChainers::FILTER_ONLY_NUMBER_ALLOWED,
                                                $app,
                                                $_GET['customer_id']));
    }  
    $isHouseDeal = NULL;
    if (isset($_GET['is_house_deal'])) {
         $stripper->offsetSet('is_house_deal',$stripChainerFactory->get(stripChainers::FILTER_ONLY_NUMBER_ALLOWED,
                                                $app,
                                                $_GET['is_house_deal']));
    }  
    $probabilityId = NULL;
    if (isset($_GET['probability_id'])) {
         $stripper->offsetSet('probability_id',$stripChainerFactory->get(stripChainers::FILTER_ONLY_NUMBER_ALLOWED,
                                                $app,
                                                $_GET['probability_id']));
    }  
    $description = NULL;
    if (isset($_GET['description'])) {
         $stripper->offsetSet('description',$stripChainerFactory->get(stripChainers::FILTER_PARANOID_LEVEL2,
                                                $app,
                                                $_GET['description']));
    }   
    $reliabilityId = NULL;
    if (isset($_GET['reliability_id'])) {
         $stripper->offsetSet('reliability_id',$stripChainerFactory->get(stripChainers::FILTER_ONLY_NUMBER_ALLOWED,
                                                $app,
                                                $_GET['reliability_id']));
    } 
    $discountRate = NULL;
    if (isset($_GET['discount_rate'])) {
         $stripper->offsetSet('discount_rate',$stripChainerFactory->get(stripChainers::FILTER_ONLY_NUMBER_ALLOWED,
                                                $app,
                                                $_GET['discount_rate']));
    } 
     $dealName = NULL;
    if (isset($_GET['deal_name'])) {
         $stripper->offsetSet('deal_name',$stripChainerFactory->get(stripChainers::FILTER_PARANOID_LEVEL2,
                                                $app,
                                                $_GET['deal_name']));
    }  
   
    // &customer_id=1&is_house_deal=0&probability_id=2&reliability_id=2&description=denemeemememe&discount_rate=15
    
    
     
    $stripper->strip();
    
    if($stripper->offsetExists('customer_id')) $customer = $stripper->offsetGet('customer_id')->getFilterValue(); 
    if($stripper->offsetExists('is_house_deal')) $isHouseDeal = $stripper->offsetGet('is_house_deal')->getFilterValue();
    if($stripper->offsetExists('probability_id')) $probabilityId = $stripper->offsetGet('probability_id')->getFilterValue();
    if($stripper->offsetExists('description')) $description = $stripper->offsetGet('description')->getFilterValue(); 
    if($stripper->offsetExists('discount_rate')) $discountRate = $stripper->offsetGet('discount_rate')->getFilterValue(); 
    if($stripper->offsetExists('reliability_id')) $reliabilityId = $stripper->offsetGet('reliability_id')->getFilterValue();
    if($stripper->offsetExists('deal_name')) $dealName = $stripper->offsetGet('deal_name')->getFilterValue();
    if($stripper->offsetExists('id')) $vId = $stripper->offsetGet('id')->getFilterValue();
     
          
    $resDataInsert = $BLL->updateAct(array(
            'Id' => $vId,    
            'CustomerId' => $customer,   
            'IsHouseDeal' => $isHouseDeal,  
            'ProbabilityId' => $probabilityId,  
            'Description' => $description,  
            'DiscountRate' => $discountRate,  
            'ReliabilityId' => $reliabilityId,   
            'DealName' => $dealName,   
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