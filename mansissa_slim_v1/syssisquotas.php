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
$app->get("/pkSisQuotasDdList_syssisquotas/", function () use ($app ) {
    $stripper = $app->getServiceManager()->get('filterChainerCustom');
    $stripChainerFactory = new \Services\Filter\Helper\FilterChainerFactory(); 
    $BLL = $app->getBLLManager()->get('sysSisQuotasBLL');
    
    $componentType = 'ddslick';
    if (isset($_GET['component_type'])) {
        $componentType = strtolower(trim($_GET['component_type']));
    }
    $headerParams = $app->request()->headers();
    if(!isset($headerParams['X-Public'])) throw new Exception ('rest api "pkSisQuotasDdList_syssisquotas" end point, X-Public variable not found');
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
        
    $resCombobox = $BLL->sisQuotasDdList(array(                                   
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

   
/**x
 *  * Okan CIRAN
 * @since 15-08-2018
 */
$app->get("/pkUpdateMakeActiveOrPassive_syssisquotas/", function () use ($app ) {
    $BLLUser = $app->getBLLManager()->get('infoUsersBLL');
    $RedisConnect = $app->getServiceManager()->get('redisConnectFactory');
    $headerParams = $app->request()->headers();
    
  //  print_r($headerParams) ; 
 
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
                $rid = $jsonFilter ["Id"];
         //       $resDatacontrol = $BLLUser->getUserId(array(
             //       'pk' => $rid, 
        //        )); 
                
                
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
    $BLL = $app->getBLLManager()->get('sysSisQuotasBLL');

    
    $RedisConnect = $app->getServiceManager()->get('redisConnectFactory');
      
  
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
$app->get("/pkInsertAct_syssisquotas/", function () use ($app ) {
    $stripper = $app->getServiceManager()->get('filterChainerCustom');
    $stripChainerFactory = new \Services\Filter\Helper\FilterChainerFactory(); 
    $BLL = $app->getBLLManager()->get('sysSisQuotasBLL');  
    $headerParams = $app->request()->headers();
    if(!isset($headerParams['X-Public'])) throw new Exception ('rest api "pkInsertAct_syssisquotas" end point, X-Public variable not found');    
     $pk =  $headerParams['X-Public'];
      
    
    $sisQuotaId = NULL;
    if (isset($_GET['sis_quota_id'])) {
         $stripper->offsetSet('sis_quota_id',$stripChainerFactory->get(stripChainers::FILTER_ONLY_NUMBER_ALLOWED,
                                                $app,
                                                $_GET['sis_quota_id']));
    } 
    $modelId = NULL;
    if (isset($_GET['model_id'])) {
         $stripper->offsetSet('model_id',$stripChainerFactory->get(stripChainers::FILTER_ONLY_NUMBER_ALLOWED,
                                                $app,
                                                $_GET['model_id']));
    } 
    $year = NULL;
    if (isset($_GET['year'])) {
         $stripper->offsetSet('year',$stripChainerFactory->get(stripChainers::FILTER_ONLY_NUMBER_ALLOWED,
                                                $app,
                                                $_GET['year']));
    } 
    $monthId = NULL;
    if (isset($_GET['month_id'])) {
         $stripper->offsetSet('month_id',$stripChainerFactory->get(stripChainers::FILTER_ONLY_NUMBER_ALLOWED,
                                                $app,
                                                $_GET['month_id']));
    } 
     $quantity = NULL;
    if (isset($_GET['quantity'])) {
         $stripper->offsetSet('quantity',$stripChainerFactory->get(stripChainers::FILTER_ONLY_NUMBER_ALLOWED,
                                                $app,
                                                $_GET['quantity']));
    } 
     
    $stripper->strip();
     
    if($stripper->offsetExists('sis_quota_id')) $sisQuotaId = $stripper->offsetGet('sis_quota_id')->getFilterValue();
    if($stripper->offsetExists('model_id')) $modelId = $stripper->offsetGet('model_id')->getFilterValue();
    if($stripper->offsetExists('year')) $year = $stripper->offsetGet('year')->getFilterValue();
    if($stripper->offsetExists('month_id')) $monthId = $stripper->offsetGet('month_id')->getFilterValue();
    if($stripper->offsetExists('quantity')) $quantity = $stripper->offsetGet('quantity')->getFilterValue();
 
 /* 
   &sis_quota_id=1&model_id=2&year=2018&month_id=12&quantity=12
    
  */
    
    $resDataInsert = $BLL->insertAct(array( 
            'SisQuotaId' => $sisQuotaId,  
            'ModelId' => $modelId,  
            'Year' => $year,  
            'MonthId' => $monthId,  
            'Quantity' => $quantity,  
         
            'pk' => $pk));
        
    $app->response()->header("Content-Type", "application/json"); 
    $app->response()->body(json_encode($resDataInsert));
    
}
);

/**
 *  * Okan CIRAN
 * @since 15-08-2018
 */ 
$app->get("/pkUpdateAct_syssisquotas/", function () use ($app ) {
    $stripper = $app->getServiceManager()->get('filterChainerCustom');
    $stripChainerFactory = new \Services\Filter\Helper\FilterChainerFactory(); 
    $BLL = $app->getBLLManager()->get('sysSisQuotasBLL');  
    $headerParams = $app->request()->headers();
    if(!isset($headerParams['X-Public'])) throw new Exception ('rest api "pkUpdateAct_syssisquotas" end point, X-Public variable not found');    
    $pk = $headerParams['X-Public'];
    
    $vId = NULL;
    if (isset($_GET['id'])) {
         $stripper->offsetSet('id',$stripChainerFactory->get(stripChainers::FILTER_ONLY_NUMBER_ALLOWED,
                                                $app,
                                                $_GET['id']));
    } 
     $sisQuotaId = NULL;
    if (isset($_GET['sis_quota_id'])) {
         $stripper->offsetSet('sis_quota_id',$stripChainerFactory->get(stripChainers::FILTER_ONLY_NUMBER_ALLOWED,
                                                $app,
                                                $_GET['sis_quota_id']));
    } 
    $modelId = NULL;
    if (isset($_GET['model_id'])) {
         $stripper->offsetSet('model_id',$stripChainerFactory->get(stripChainers::FILTER_ONLY_NUMBER_ALLOWED,
                                                $app,
                                                $_GET['model_id']));
    } 
    $year = NULL;
    if (isset($_GET['year'])) {
         $stripper->offsetSet('year',$stripChainerFactory->get(stripChainers::FILTER_ONLY_NUMBER_ALLOWED,
                                                $app,
                                                $_GET['year']));
    } 
    $monthId = NULL;
    if (isset($_GET['month_id'])) {
         $stripper->offsetSet('month_id',$stripChainerFactory->get(stripChainers::FILTER_ONLY_NUMBER_ALLOWED,
                                                $app,
                                                $_GET['month_id']));
    } 
     $quantity = NULL;
    if (isset($_GET['quantity'])) {
         $stripper->offsetSet('quantity',$stripChainerFactory->get(stripChainers::FILTER_ONLY_NUMBER_ALLOWED,
                                                $app,
                                                $_GET['quantity']));
    } 
     
    $stripper->strip();
     
    if($stripper->offsetExists('sis_quota_id')) $sisQuotaId = $stripper->offsetGet('sis_quota_id')->getFilterValue();
    if($stripper->offsetExists('model_id')) $modelId = $stripper->offsetGet('model_id')->getFilterValue();
    if($stripper->offsetExists('year')) $year = $stripper->offsetGet('year')->getFilterValue();
    if($stripper->offsetExists('month_id')) $monthId = $stripper->offsetGet('month_id')->getFilterValue();
    if($stripper->offsetExists('quantity')) $quantity = $stripper->offsetGet('quantity')->getFilterValue();
 
    if($stripper->offsetExists('id')) $vId = $stripper->offsetGet('id')->getFilterValue();
     
          
    $resDataInsert = $BLL->updateAct(array(
            'Id' => $vId,   
            'SisQuotaId' => $sisQuotaId,  
            'ModelId' => $modelId,  
            'Year' => $year,  
            'MonthId' => $monthId,  
            'Quantity' => $quantity,  
            'pk' => $pk));
        
    $app->response()->header("Content-Type", "application/json"); 
    $app->response()->body(json_encode($resDataInsert));
    
}
);
 
/**
 *  * Okan CIRAN
 * @since 15-08-2018
 */
$app->get("/pkDeletedAct_syssisquotas/", function () use ($app ) {
    $stripper = $app->getServiceManager()->get('filterChainerCustom');
    $stripChainerFactory = new \Services\Filter\Helper\FilterChainerFactory();    
    $BLL = $app->getBLLManager()->get('sysSisQuotasBLL');   
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