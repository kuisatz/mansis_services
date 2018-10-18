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
$app->get("/pkFillCustomerPurchaseGridx_infocustomerpurchaseplan/", function () use ($app ) {
    $stripper = $app->getServiceManager()->get('filterChainerCustom');
    $stripChainerFactory = new \Services\Filter\Helper\FilterChainerFactory();
    $BLL = $app->getBLLManager()->get('infoCustomerPurchasePlanBLL');
    $headerParams = $app->request()->headers();
    if (!isset($headerParams['X-Public']))
        throw new Exception('rest api "pkFillCustomerPurchaseGridx_infocustomerpurchaseplan" end point, X-Public variable not found');
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
    if ($stripper->offsetExists('customer_id')) { $customerId = $stripper->offsetGet('customer_id')->getFilterValue(); }
    if ($stripper->offsetExists('page')) { $vPage = $stripper->offsetGet('page')->getFilterValue(); }
    if ($stripper->offsetExists('rows')) { $vRows = $stripper->offsetGet('rows')->getFilterValue(); }
    if ($stripper->offsetExists('sort')) { $vSort = $stripper->offsetGet('sort')->getFilterValue(); }
    if ($stripper->offsetExists('order')) { $vOrder = $stripper->offsetGet('order')->getFilterValue(); }
    if ($stripper->offsetExists('filterRules')) { $filterRules = $stripper->offsetGet('filterRules')->getFilterValue(); } 

    $resDataGrid = $BLL->FillCustomerPurchaseGridx(array(
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
    
  
    $menu = array();            
    if (isset($resDataGrid[0]['id'])) {      
        foreach ($resDataGrid as $menu) {
            $menus[] = array(
               "id" => $menu["id"],
                "apid" => intval($menu["apid"]),  
                "customer_id" =>  ($menu["customer_id"]),
                "last_purchase_date" =>  ($menu["last_purchase_date"]),
             
                "last_brand_id" =>  ($menu["last_brand_id"]),
                "description" => html_entity_decode($menu["description"]),
            //    "date_of_purchase" =>  ($menu["date_of_purchase"]),
                "quantity" =>  ($menu["quantity"]),
                
                
                 "purchase_decision_id" =>  ($menu["purchase_decision_id"]), 
                "purchase_decision" => html_entity_decode($menu["purchase_decision"]),
                 "date_of_plan_id" =>  ($menu["date_of_plan_id"]), 
                "date_of_plan" => html_entity_decode($menu["date_of_plan"]),
              
                 
                "op_username" => html_entity_decode($menu["op_user_name"]),  
                "date_saved" => $menu["date_saved"],
                "date_modified" => $menu["date_modified"],  
                "op_user_id" => $menu["op_user_id"], 
               
            );
        }
     
      } ELSE  $menus = array();       

    $app->response()->header("Content-Type", "application/json"); 
    $app->response()->body(json_encode($menus));
});
  
/**x
 *  * Okan CIRAN
 * @since 15-08-2018
 */
$app->get("/pkUpdateMakeActiveOrPassive_infocustomerpurchaseplan/", function () use ($app ) {
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
    $BLL = $app->getBLLManager()->get('infoCustomerPurchasePlanBLL');

    
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
$app->get("/pkInsertAct_infocustomerpurchaseplan/", function () use ($app ) { 
    $stripper = $app->getServiceManager()->get('filterChainerCustom');
    $stripChainerFactory = new \Services\Filter\Helper\FilterChainerFactory(); 
    $BLL = $app->getBLLManager()->get('infoCustomerPurchasePlanBLL');  
    $headerParams = $app->request()->headers();
    if(!isset($headerParams['X-Public'])) throw new Exception ('rest api "pkInsertAct_infocustomerpurchaseplan" end point, X-Public variable not found');    
     $pk =  $headerParams['X-Public'];
     
    $lastPurchaseDate = NULL;
    if (isset($_GET['last_purchase_date'])) {
         $stripper->offsetSet('last_purchase_date',$stripChainerFactory->get(stripChainers::FILTER_PARANOID_LEVEL2,
                                                $app,
                                                $_GET['last_purchase_date']));
    }  
     $dateOfPurchase = NULL;
    if (isset($_GET['date_of_purchase'])) {
         $stripper->offsetSet('date_of_purchase',$stripChainerFactory->get(stripChainers::FILTER_PARANOID_LEVEL2,
                                                $app,
                                                $_GET['date_of_purchase']));
    } 
    $CustomerId= NULL;
    if (isset($_GET['customer_id'])) {
        $stripper->offsetSet('customer_id', $stripChainerFactory->get(stripChainers::FILTER_ONLY_NUMBER_ALLOWED,  $app,   $_GET['customer_id']));
    }
    $quantity= NULL;
    if (isset($_GET['quantity'])) {
        $stripper->offsetSet('quantity', $stripChainerFactory->get(stripChainers::FILTER_ONLY_NUMBER_ALLOWED,  $app,   $_GET['quantity']));
    } 
    $Description = NULL;
    if (isset($_GET['description'])) {
         $stripper->offsetSet('description',$stripChainerFactory->get(stripChainers::FILTER_PARANOID_LEVEL2,
                                                $app,
                                                $_GET['description']));
    }   
    $lastBrand = NULL;
    if (isset($_GET['last_brand_id'])) {
         $stripper->offsetSet('last_brand_id',$stripChainerFactory->get(stripChainers::FILTER_ONLY_NUMBER_ALLOWED,
                                                $app,
                                                $_GET['last_brand_id']));
    }  
    $purchaseDecisionId= NULL;
    if (isset($_GET['purchase_decision_id'])) {
        $stripper->offsetSet('purchase_decision_id', $stripChainerFactory->get(stripChainers::FILTER_ONLY_NUMBER_ALLOWED,  $app,   $_GET['purchase_decision_id']));
    } 
     $dateOfPlanId= NULL;
    if (isset($_GET['date_of_plan_id'])) {
        $stripper->offsetSet('date_of_plan_id', $stripChainerFactory->get(stripChainers::FILTER_ONLY_NUMBER_ALLOWED,  $app,   $_GET['date_of_plan_id']));
    } 
   
    //  &customer_id=1&last_purchase_date=2016-04-10&date_of_purchase=2018-10-10&quantity=22&description=asdasd&last_brand=VW
     
    $stripper->strip(); 
    
    if($stripper->offsetExists('last_purchase_date')) $lastPurchaseDate = $stripper->offsetGet('last_purchase_date')->getFilterValue();
    if($stripper->offsetExists('customer_id')) $CustomerId = $stripper->offsetGet('customer_id')->getFilterValue();
    if($stripper->offsetExists('date_of_purchase')) $dateOfPurchase = $stripper->offsetGet('date_of_purchase')->getFilterValue();
    if($stripper->offsetExists('quantity')) $quantity = $stripper->offsetGet('quantity')->getFilterValue();
    if($stripper->offsetExists('description')) $Description = $stripper->offsetGet('description')->getFilterValue();
    if($stripper->offsetExists('last_brand_id')) $lastBrand = $stripper->offsetGet('last_brand_id')->getFilterValue();
     if($stripper->offsetExists('purchase_decision_id')) $purchaseDecisionId = $stripper->offsetGet('purchase_decision_id')->getFilterValue();
      if($stripper->offsetExists('date_of_plan_id')) $dateOfPlanId = $stripper->offsetGet('date_of_plan_id')->getFilterValue();
  
    $resDataInsert = $BLL->insertAct(array( 
            'CustomerId' => $CustomerId,  
            'LastPurchaseDate' => $lastPurchaseDate,  
            'DateOfPurchase' => $dateOfPurchase,   
            'Quantity' => $quantity,  
            'LastBrandId' => $lastBrand,  
            'Description' => $Description,  
            'PurchaseDecisionId' => $purchaseDecisionId,  
            'DateOfPlanId' => $dateOfPlanId,  
          
            'pk' => $pk));
        
    $app->response()->header("Content-Type", "application/json"); 
    $app->response()->body(json_encode($resDataInsert));
    
}
);
 
/**
 *  * Okan CIRAN
 * @since 15-08-2018
 */ 
$app->get("/pkUpdateAct_infocustomerpurchaseplan/", function () use ($app ) { 
    $stripper = $app->getServiceManager()->get('filterChainerCustom');
    $stripChainerFactory = new \Services\Filter\Helper\FilterChainerFactory(); 
    $BLL = $app->getBLLManager()->get('infoCustomerPurchasePlanBLL');  
    $headerParams = $app->request()->headers();
    if(!isset($headerParams['X-Public'])) throw new Exception ('rest api "pkInsertAct_infocustomerpurchaseplan" end point, X-Public variable not found');    
     $pk =  $headerParams['X-Public'];
      
     $vId = NULL;
    if (isset($_GET['id'])) {
         $stripper->offsetSet('id',$stripChainerFactory->get(stripChainers::FILTER_ONLY_NUMBER_ALLOWED,
                                                $app,
                                                $_GET['id']));
    } 
        $lastPurchaseDate = NULL;
    if (isset($_GET['last_purchase_date'])) {
         $stripper->offsetSet('last_purchase_date',$stripChainerFactory->get(stripChainers::FILTER_PARANOID_LEVEL2,
                                                $app,
                                                $_GET['last_purchase_date']));
    }  
     $dateOfPurchase = NULL;
    if (isset($_GET['date_of_purchase'])) {
         $stripper->offsetSet('date_of_purchase',$stripChainerFactory->get(stripChainers::FILTER_PARANOID_LEVEL2,
                                                $app,
                                                $_GET['date_of_purchase']));
    } 
    $CustomerId= NULL;
    if (isset($_GET['customer_id'])) {
        $stripper->offsetSet('customer_id', $stripChainerFactory->get(stripChainers::FILTER_ONLY_NUMBER_ALLOWED,  $app,   $_GET['customer_id']));
    }
    $quantity= NULL;
    if (isset($_GET['quantity'])) {
        $stripper->offsetSet('quantity', $stripChainerFactory->get(stripChainers::FILTER_ONLY_NUMBER_ALLOWED,  $app,   $_GET['quantity']));
    } 
    $Description = NULL;
    if (isset($_GET['description'])) {
         $stripper->offsetSet('description',$stripChainerFactory->get(stripChainers::FILTER_PARANOID_LEVEL2,
                                                $app,
                                                $_GET['description']));
    }   
    $lastBrand = NULL;
    if (isset($_GET['last_brand_id'])) {
         $stripper->offsetSet('last_brand_id',$stripChainerFactory->get(stripChainers::FILTER_ONLY_NUMBER_ALLOWED,
                                                $app,
                                                $_GET['last_brand_id']));
    }  
    $purchaseDecisionId= NULL;
    if (isset($_GET['purchase_decision_id'])) {
        $stripper->offsetSet('purchase_decision_id', $stripChainerFactory->get(stripChainers::FILTER_ONLY_NUMBER_ALLOWED,  $app,   $_GET['purchase_decision_id']));
    } 
     $dateOfPlanId= NULL;
    if (isset($_GET['date_of_plan_id'])) {
        $stripper->offsetSet('date_of_plan_id', $stripChainerFactory->get(stripChainers::FILTER_ONLY_NUMBER_ALLOWED,  $app,   $_GET['date_of_plan_id']));
    } 
   
    //  &customer_id=1&last_purchase_date=2016-04-10&date_of_purchase=2018-10-10&quantity=22&description=asdasd&last_brand=VW
     
    $stripper->strip(); 
    
    if($stripper->offsetExists('last_purchase_date')) $lastPurchaseDate = $stripper->offsetGet('last_purchase_date')->getFilterValue();
    if($stripper->offsetExists('customer_id')) $CustomerId = $stripper->offsetGet('customer_id')->getFilterValue();
    if($stripper->offsetExists('date_of_purchase')) $dateOfPurchase = $stripper->offsetGet('date_of_purchase')->getFilterValue();
    if($stripper->offsetExists('quantity')) $quantity = $stripper->offsetGet('quantity')->getFilterValue();
    if($stripper->offsetExists('description')) $Description = $stripper->offsetGet('description')->getFilterValue();
    if($stripper->offsetExists('last_brand_id')) $lastBrand = $stripper->offsetGet('last_brand_id')->getFilterValue();
    if($stripper->offsetExists('purchase_decision_id')) $purchaseDecisionId = $stripper->offsetGet('purchase_decision_id')->getFilterValue();
    if($stripper->offsetExists('date_of_plan_id')) $dateOfPlanId = $stripper->offsetGet('date_of_plan_id')->getFilterValue();
 
    if($stripper->offsetExists('id')) $vId = $stripper->offsetGet('id')->getFilterValue();
  
    $resDataInsert = $BLL->updateAct(array( 
            'CustomerId' => $CustomerId,  
            'LastPurchaseDate' => $lastPurchaseDate,  
            'DateOfPurchase' => $dateOfPurchase,   
            'Quantity' => $quantity,  
            'LastBrandId' => $lastBrand,  
            'Description' => $Description,  
            'PurchaseDecisionId' => $purchaseDecisionId,  
            'DateOfPlanId' => $dateOfPlanId,  
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
$app->get("/pkDeletedAct_infocustomerpurchaseplan/", function () use ($app ) {
    $stripper = $app->getServiceManager()->get('filterChainerCustom');
    $stripChainerFactory = new \Services\Filter\Helper\FilterChainerFactory();    
    $BLL = $app->getBLLManager()->get('infoCustomerPurchasePlanBLL');   
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