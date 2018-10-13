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
$app->get("/pkCustomerContactPersonDdList_infocustomercontactpersons/", function () use ($app ) {
    $stripper = $app->getServiceManager()->get('filterChainerCustom');
    $stripChainerFactory = new \Services\Filter\Helper\FilterChainerFactory(); 
    $BLL = $app->getBLLManager()->get('infoCustomerContactPersonsBLL');
    
    $componentType = 'ddslick';
    if (isset($_GET['component_type'])) {
        $componentType = strtolower(trim($_GET['component_type']));
    }
    $headerParams = $app->request()->headers();
    if(!isset($headerParams['X-Public'])) throw new Exception ('rest api "pkCustomerContactPersonDdList_infocustomercontactpersons" end point, X-Public variable not found');
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
        
    $resCombobox = $BLL->customerContactPersonDdList(array(                                   
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
        );
    }
    $app->response()->header("Content-Type", "application/json");
    $app->response()->body(json_encode($flows));
});
  

/**
 *  * Okan CIRAN
 * @since 15-08-2018
 */
$app->get("/pkCustomerContactPersonGridx_infocustomercontactpersons/", function () use ($app ) {
    $stripper = $app->getServiceManager()->get('filterChainerCustom');
    $stripChainerFactory = new \Services\Filter\Helper\FilterChainerFactory();
    $BLL = $app->getBLLManager()->get('infoCustomerContactPersonsBLL');
    $headerParams = $app->request()->headers();
    if (!isset($headerParams['X-Public']))
        throw new Exception('rest api "pkCustomerContactPersonGridx_infocustomercontactpersons" end point, X-Public variable not found');
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

    $resDataGrid = $BLL->customerContactPersonGridx(array(
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
   
    $resTotalRowCount = $BLL->customerContactPersonGridxRtl(array(
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
                "customer_id" => $menu["customer_id"],   
                "name" => html_entity_decode($menu["name"]),
                "surname" => html_entity_decode($menu["surname"]),
                "email" => html_entity_decode($menu["email"]),
                "cep" => html_entity_decode($menu["cep"]),
                "tel" => html_entity_decode($menu["tel"]),
                "fax" => html_entity_decode($menu["fax"]),
                "embrace_customer_no" => html_entity_decode($menu["embrace_customer_no"]),
                "tu_emb_customer_no" => html_entity_decode($menu["tu_emb_customer_no"]),
                "ce_emb_customer_no" => html_entity_decode($menu["ce_emb_customer_no"]),
                "other_emb_customer_no" => html_entity_decode($menu["other_emb_customer_no"]),
                "www" => html_entity_decode($menu["www"]),
                "vatnumber" => html_entity_decode($menu["vatnumber"]),
                "registration_number" => html_entity_decode($menu["registration_number"]),
                "registration_date" => html_entity_decode($menu["registration_date"]),
         
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
    $BLL = $app->getBLLManager()->get('infoCustomerContactPersonsBLL');

    
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
    $BLL = $app->getBLLManager()->get('infoCustomerContactPersonsBLL');  
    $headerParams = $app->request()->headers();
    if(!isset($headerParams['X-Public'])) throw new Exception ('rest api "pkInsertAct_infocustomeractivations" end point, X-Public variable not found');    
    $pk =  $headerParams['X-Public'];
       
    $CustomerId= NULL;
    if (isset($_GET['customer_id'])) {
        $stripper->offsetSet('customer_id', $stripChainerFactory->get(stripChainers::FILTER_ONLY_NUMBER_ALLOWED,  $app,   $_GET['customer_id']));
    }
    $name= NULL;
    if (isset($_GET['name'])) {
        $stripper->offsetSet('name', $stripChainerFactory->get(stripChainers::FILTER_PARANOID_LEVEL2,  $app,   $_GET['name']));
    }
    
    $surname = NULL;
    if (isset($_GET['surname'])) {
         $stripper->offsetSet('surname',$stripChainerFactory->get(stripChainers::FILTER_PARANOID_LEVEL2,
                                                $app,
                                                $_GET['surname']));
    }  
    $email = NULL;
    if (isset($_GET['email'])) {
         $stripper->offsetSet('email',$stripChainerFactory->get(stripChainers::FILTER_PARANOID_LEVEL2,
                                                $app,
                                                $_GET['email']));
    }  
    $cep = NULL;
    if (isset($_GET['cep'])) {
         $stripper->offsetSet('cep',$stripChainerFactory->get(stripChainers::FILTER_PARANOID_LEVEL2,
                                                $app,
                                                $_GET['cep']));
    }  
    $tel = NULL;
    if (isset($_GET['tel'])) {
         $stripper->offsetSet('tel',$stripChainerFactory->get(stripChainers::FILTER_PARANOID_LEVEL2,
                                                $app,
                                                $_GET['tel']));
    }  
    $fax = NULL;
    if (isset($_GET['fax'])) {
         $stripper->offsetSet('fax',$stripChainerFactory->get(stripChainers::FILTER_PARANOID_LEVEL2,
                                                $app,
                                                $_GET['fax']));
    }  
    
    
    $stripper->strip();
    
    
    if($stripper->offsetExists('name')) $name = $stripper->offsetGet('name')->getFilterValue();
    if($stripper->offsetExists('customer_id')) $CustomerId = $stripper->offsetGet('customer_id')->getFilterValue();
    if($stripper->offsetExists('surname')) $surname = $stripper->offsetGet('surname')->getFilterValue();
    if($stripper->offsetExists('email')) $email = $stripper->offsetGet('email')->getFilterValue();
    if($stripper->offsetExists('cep')) $cep = $stripper->offsetGet('cep')->getFilterValue();
    if($stripper->offsetExists('tel')) $tel = $stripper->offsetGet('tel')->getFilterValue();
    if($stripper->offsetExists('fax')) $fax = $stripper->offsetGet('fax')->getFilterValue(); 
  
    $resDataInsert = $BLL->insertAct(array( 
            'CustomerId' => $CustomerId,  
            'Name' => $name,  
            'Surname' => $surname,   
            'Email' => $email,  
            'Cep' => $cep,  
            'Tel' => $tel,  
            'Fax' => $fax,    
            
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
    $BLL = $app->getBLLManager()->get('infoCustomerContactPersonsBLL');  
    $headerParams = $app->request()->headers();
    if(!isset($headerParams['X-Public'])) throw new Exception ('rest api "pkInsertAct_infocustomeractivations" end point, X-Public variable not found');    
     $pk =  $headerParams['X-Public'];
      
     $vId = NULL;
    if (isset($_GET['id'])) {
         $stripper->offsetSet('id',$stripChainerFactory->get(stripChainers::FILTER_ONLY_NUMBER_ALLOWED,
                                                $app,
                                                $_GET['id']));
    } 
    $CustomerId= NULL;
    if (isset($_GET['customer_id'])) {
        $stripper->offsetSet('customer_id', $stripChainerFactory->get(stripChainers::FILTER_ONLY_NUMBER_ALLOWED,  $app,   $_GET['customer_id']));
    }
    $name= NULL;
    if (isset($_GET['name'])) {
        $stripper->offsetSet('name', $stripChainerFactory->get(stripChainers::FILTER_PARANOID_LEVEL2,  $app,   $_GET['name']));
    }
    
    $surname = NULL;
    if (isset($_GET['surname'])) {
         $stripper->offsetSet('surname',$stripChainerFactory->get(stripChainers::FILTER_PARANOID_LEVEL2,
                                                $app,
                                                $_GET['surname']));
    }  
    $email = NULL;
    if (isset($_GET['email'])) {
         $stripper->offsetSet('email',$stripChainerFactory->get(stripChainers::FILTER_PARANOID_LEVEL2,
                                                $app,
                                                $_GET['email']));
    }  
    $cep = NULL;
    if (isset($_GET['cep'])) {
         $stripper->offsetSet('cep',$stripChainerFactory->get(stripChainers::FILTER_PARANOID_LEVEL2,
                                                $app,
                                                $_GET['cep']));
    }  
    $tel = NULL;
    if (isset($_GET['tel'])) {
         $stripper->offsetSet('tel',$stripChainerFactory->get(stripChainers::FILTER_PARANOID_LEVEL2,
                                                $app,
                                                $_GET['tel']));
    }  
    $fax = NULL;
    if (isset($_GET['fax'])) {
         $stripper->offsetSet('fax',$stripChainerFactory->get(stripChainers::FILTER_PARANOID_LEVEL2,
                                                $app,
                                                $_GET['fax']));
    }  
    
    
    $stripper->strip();
    
    
    if($stripper->offsetExists('name')) $name = $stripper->offsetGet('name')->getFilterValue();
    if($stripper->offsetExists('customer_id')) $CustomerId = $stripper->offsetGet('customer_id')->getFilterValue();
    if($stripper->offsetExists('surname')) $surname = $stripper->offsetGet('surname')->getFilterValue();
    if($stripper->offsetExists('email')) $email = $stripper->offsetGet('email')->getFilterValue();
    if($stripper->offsetExists('cep')) $cep = $stripper->offsetGet('cep')->getFilterValue();
    if($stripper->offsetExists('tel')) $tel = $stripper->offsetGet('tel')->getFilterValue();
    if($stripper->offsetExists('fax')) $fax = $stripper->offsetGet('fax')->getFilterValue(); 
  
    if($stripper->offsetExists('id')) $vId = $stripper->offsetGet('id')->getFilterValue();
  
    $resDataInsert = $BLL->updateAct(array( 
            'CustomerId' => $CustomerId,  
            'Name' => $name,  
            'Surname' => $surname,   
            'Email' => $email,  
            'Cep' => $cep,  
            'Tel' => $tel,  
            'Fax' => $fax,     
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
    $BLL = $app->getBLLManager()->get('infoCustomerContactPersonsBLL');   
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