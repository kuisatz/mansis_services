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
$app->get("/pkCustomerDdList_infocustomer/", function () use ($app ) {
    $stripper = $app->getServiceManager()->get('filterChainerCustom');
    $stripChainerFactory = new \Services\Filter\Helper\FilterChainerFactory(); 
    $BLL = $app->getBLLManager()->get('infoCustomerBLL');
    
    $componentType = 'ddslick';
    if (isset($_GET['component_type'])) {
        $componentType = strtolower(trim($_GET['component_type']));
    }
    $headerParams = $app->request()->headers();
    if(!isset($headerParams['X-Public'])) throw new Exception ('rest api "pkCustomerDdList_infocustomer" end point, X-Public variable not found');
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
        
    $resCombobox = $BLL->customerDdList(array(                                   
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
 * @since 11.08.2018
 */
$app->get("/pkCustomerNoConfirmDdList_infocustomer/", function () use ($app ) {
    $stripper = $app->getServiceManager()->get('filterChainerCustom');
    $stripChainerFactory = new \Services\Filter\Helper\FilterChainerFactory(); 
    $BLL = $app->getBLLManager()->get('infoCustomerBLL');
    
    $componentType = 'ddslick';
    if (isset($_GET['component_type'])) {
        $componentType = strtolower(trim($_GET['component_type']));
    }
    $headerParams = $app->request()->headers();
    if(!isset($headerParams['X-Public'])) throw new Exception ('rest api "pkCustomerNoConfirmDdList_infocustomer" end point, X-Public variable not found');
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
        
    $resCombobox = $BLL->customerNoConfirmDdList(array(                                   
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
 * @since 11.08.2018
 */
$app->get("/pkCustomerConfirmDdList_infocustomer/", function () use ($app ) {
    $stripper = $app->getServiceManager()->get('filterChainerCustom');
    $stripChainerFactory = new \Services\Filter\Helper\FilterChainerFactory(); 
    $BLL = $app->getBLLManager()->get('infoCustomerBLL');
    
    $componentType = 'ddslick';
    if (isset($_GET['component_type'])) {
        $componentType = strtolower(trim($_GET['component_type']));
    }
    $headerParams = $app->request()->headers();
    if(!isset($headerParams['X-Public'])) throw new Exception ('rest api "pkCustomerConfirmDdList_infocustomer" end point, X-Public variable not found');
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
        
    $resCombobox = $BLL->customerConfirmDdList(array(                                   
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
$app->get("/pkFillCustomerGridx_infocustomer/", function () use ($app ) {
    $stripper = $app->getServiceManager()->get('filterChainerCustom');
    $stripChainerFactory = new \Services\Filter\Helper\FilterChainerFactory();
    $BLL = $app->getBLLManager()->get('infoCustomerBLL');
    $headerParams = $app->request()->headers();
    if (!isset($headerParams['X-Public']))
        throw new Exception('rest api "pkFillCustomerGridx_infocustomer" end point, X-Public variable not found');
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

    $resDataGrid = $BLL->fillCustomerGridx(array(
        'language_code' => $vLanguageCode,
        'LanguageID' => $lid,
        'page' => $vPage,
        'rows' => $vRows,
        'sort' => $vSort,
        'order' => $vOrder,
 
        'filterRules' => $filterRules,
        'pk' => $pk,
    ));
   
    $resTotalRowCount = $BLL->fillCustomerGridxRtl(array(
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
                
                "cust_sis_key" => html_entity_decode($menu["cust_sis_key"]),
                "registration_name" => html_entity_decode($menu["registration_name"]),
                "trading_name" => html_entity_decode($menu["trading_name"]),
                "name_short" => html_entity_decode($menu["name_short"]),
                "embrace_customer_no" => html_entity_decode($menu["embrace_customer_no"]),
                "tu_emb_customer_no" => html_entity_decode($menu["tu_emb_customer_no"]),
                "ce_emb_customer_no" => html_entity_decode($menu["ce_emb_customer_no"]),
                "other_emb_customer_no" => html_entity_decode($menu["other_emb_customer_no"]),
                "www" => html_entity_decode($menu["www"]),
                "email" => html_entity_decode($menu["email"]),
                "phonenumber" => html_entity_decode($menu["phonenumber"]),
                
                
                "vatnumber" => html_entity_decode($menu["vatnumber"]),
                "registration_number" => html_entity_decode($menu["registration_number"]),
                "registration_date" =>  ($menu["registration_date"]),
                "ne_count_type_id" =>  ($menu["ne_count_type_id"]),
                "numberofemployees" => html_entity_decode($menu["numberofemployees"]),
                "nv_count_type_id" =>  ($menu["nv_count_type_id"]),
                "numberofvehicles" => html_entity_decode($menu["numberofvehicles"]),
                "customer_category_id" =>  ($menu["customer_category_id"]),
                "customer_category_name" => html_entity_decode($menu["customer_category_name"]),
                "reliability_id" => ($menu["reliability_id"]),
                "reliability_name" => html_entity_decode($menu["reliability_name"]),
                "turnover_rate_id" => ($menu["turnover_rate_id"]),
                "turnover_rate_name" => html_entity_decode($menu["turnover_rate_name"]),
                "sector_type_id" => ($menu["sector_type_id"]),
                "sector_type_name" => html_entity_decode($menu["sector_type_name"]),
                "application_type_id" =>  ($menu["application_type_id"]),
                "application_type_name" => html_entity_decode($menu["application_type_name"]),
                "segment_type_id" =>  ($menu["segment_type_id"]),
                "segment_type_name" => html_entity_decode($menu["segment_type_name"]),
                "is_bo_confirm" =>  ($menu["is_bo_confirm"]),
                "firm_country_id" =>  ($menu["firm_country_id"]),
                "firm_country_name" => html_entity_decode($menu["firm_country_name"]),
                "address1" => html_entity_decode($menu["address1"]),
                "address2" => html_entity_decode($menu["address2"]),
                "address3" => html_entity_decode($menu["address3"]),
                
                "postalcode" => html_entity_decode($menu["postalcode"]),
                 
                "country_id" =>  ($menu["country_id"]),
                "country_name" => html_entity_decode($menu["country_name"]),
                "city_id" =>  ($menu["city_id"]),
                "city_name" => html_entity_decode($menu["city_name"]),
                "region_id" =>  ($menu["region_id"]),
                "region" => html_entity_decode($menu["region"]),
                  
                
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
$app->get("/pkUpdateMakeActiveOrPassive_infocustomer/", function () use ($app ) {
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
    $BLL = $app->getBLLManager()->get('infoCustomerBLL');

    
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
$app->get("/pkInsertAct_infocustomer/", function () use ($app ) {
    $stripper = $app->getServiceManager()->get('filterChainerCustom');
    $stripChainerFactory = new \Services\Filter\Helper\FilterChainerFactory(); 
    $BLL = $app->getBLLManager()->get('infoCustomerBLL');  
    $headerParams = $app->request()->headers();
    if(!isset($headerParams['X-Public'])) throw new Exception ('rest api "pkInsertAct_infocustomer" end point, X-Public variable not found');    
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
    $credibilityId = NULL;
    if (isset($_GET['credibilityId'])) {
         $stripper->offsetSet('credibilityId',$stripChainerFactory->get(stripChainers::FILTER_ONLY_NUMBER_ALLOWED,
                                                $app,
                                                $_GET['credibilityId']));
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
    $address1 = NULL;
    if (isset($_GET['address1'])) {
         $stripper->offsetSet('address1',$stripChainerFactory->get(stripChainers::FILTER_PARANOID_LEVEL2,
                                                $app,
                                                $_GET['address1']));
    }  
     $address2 = NULL;
    if (isset($_GET['address2'])) {
         $stripper->offsetSet('address2',$stripChainerFactory->get(stripChainers::FILTER_PARANOID_LEVEL2,
                                                $app,
                                                $_GET['address2']));
    }  
     $address3 = NULL;
    if (isset($_GET['address3'])) {
         $stripper->offsetSet('address3',$stripChainerFactory->get(stripChainers::FILTER_PARANOID_LEVEL2,
                                                $app,
                                                $_GET['address3']));
    }  
     $postalcode = NULL;
    if (isset($_GET['postalcode'])) {
         $stripper->offsetSet('postalcode',$stripChainerFactory->get(stripChainers::FILTER_PARANOID_LEVEL2,
                                                $app,
                                                $_GET['postalcode']));
    }  
    $phonenumber = NULL;
    if (isset($_GET['phonenumber'])) {
         $stripper->offsetSet('phonenumber',$stripChainerFactory->get(stripChainers::FILTER_ONLY_NUMBER_ALLOWED,
                                                $app,
                                                $_GET['phonenumber']));
    } 
     $email = NULL;
    if (isset($_GET['email'])) {
         $stripper->offsetSet('email',$stripChainerFactory->get(stripChainers::FILTER_PARANOID_LEVEL2,
                                                $app,
                                                $_GET['email']));
    } 
     $city = NULL;
    if (isset($_GET['city_id'])) {
         $stripper->offsetSet('city_id',$stripChainerFactory->get(stripChainers::FILTER_ONLY_NUMBER_ALLOWED,
                                                $app,
                                                $_GET['city_id']));
    } 
     $country = NULL;
    if (isset($_GET['country_id'])) {
         $stripper->offsetSet('country_id',$stripChainerFactory->get(stripChainers::FILTER_ONLY_NUMBER_ALLOWED,
                                                $app,
                                                $_GET['country_id']));
    } 
    
     $country2 = NULL;
    if (isset($_GET['country2_id'])) {
         $stripper->offsetSet('country2_id',$stripChainerFactory->get(stripChainers::FILTER_ONLY_NUMBER_ALLOWED,
                                                $app,
                                                $_GET['country2_id']));
    } 
     
    
    
  //&embrace_customer_no=TYU5675&tu_emb_customer_no= &ce_emb_customer_no=&other_emb_customer_no=&registration_name=denemefirm&trading_name=denemefirm&name_short=denemefirm&www=denemefirm.com&vatnumber=1231231&registration_number=321321321&registration_date=2018-10-10&ne_count_type_id=1&nv_count_type_id= 2&customer_category_id=2&reliability_id=1&turnover_rate_id=2&sector_type_id=3&application_type_id=1&segment_type_id=1&country2_id=107&address1=asd cad&address2=11 str&address3=5&postalcode=1231&city_id=151&country_id=107
    
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
    
    if($stripper->offsetExists('address1')) $address1 = $stripper->offsetGet('address1')->getFilterValue();
    if($stripper->offsetExists('address2')) $address2 = $stripper->offsetGet('address2')->getFilterValue();
    if($stripper->offsetExists('address3')) $address3 = $stripper->offsetGet('address3')->getFilterValue();
    if($stripper->offsetExists('postalcode')) $postalcode = $stripper->offsetGet('postalcode')->getFilterValue();
    if($stripper->offsetExists('phonenumber')) $phonenumber = $stripper->offsetGet('phonenumber')->getFilterValue();
    if($stripper->offsetExists('email')) $email = $stripper->offsetGet('email')->getFilterValue();
    if($stripper->offsetExists('turnover_rate_id')) $turnoverRateId = $stripper->offsetGet('turnover_rate_id')->getFilterValue();
    
     if($stripper->offsetExists('country2_id')) $country2 = $stripper->offsetGet('country2_id')->getFilterValue();
      if($stripper->offsetExists('country_id')) $country = $stripper->offsetGet('country_id')->getFilterValue();
       if($stripper->offsetExists('city_id')) $city = $stripper->offsetGet('city_id')->getFilterValue();
     //  if($stripper->offsetExists('turnover_rate_id')) $turnoverRateId = $stripper->offsetGet('turnover_rate_id')->getFilterValue();
    if($stripper->offsetExists('credibilityId')) $credibilityId = $stripper->offsetGet('credibilityId')->getFilterValue();
    
  
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
        
         'Address1' => $address1,  
         'Address2' => $address2,  
         'Address3' => $address3,  
         'PostalCode' => $postalcode,  
         'PhoneNumber' => $phonenumber,  
         'Email' => $email,  
        
         'CityId' => $city,  
         'CountryId' => $country,  
         'TurnoverRateId' => $turnoverRateId,  
         'FirmCountryId' => $country2,  
         'CredibilityId' => $credibilityId,  
        
          
            'pk' => $pk));
        
    $app->response()->header("Content-Type", "application/json"); 
    $app->response()->body(json_encode($resDataInsert));
    
}
);

/**
 *  * Okan CIRAN
 * @since 15-08-2018
 */ 
$app->get("/pkUpdateAct_infocustomer/", function () use ($app ) {
    $stripper = $app->getServiceManager()->get('filterChainerCustom');
    $stripChainerFactory = new \Services\Filter\Helper\FilterChainerFactory(); 
    $BLL = $app->getBLLManager()->get('infoCustomerBLL');  
    $headerParams = $app->request()->headers();
    if(!isset($headerParams['X-Public'])) throw new Exception ('rest api "pkUpdateAct_infocustomer" end point, X-Public variable not found');    
    $pk = $headerParams['X-Public'];
    
    $vId = NULL;
    if (isset($_GET['id'])) {
         $stripper->offsetSet('id',$stripChainerFactory->get(stripChainers::FILTER_ONLY_NUMBER_ALLOWED,
                                                $app,
                                                $_GET['id']));
    } 
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
    $address1 = NULL;
    if (isset($_GET['address1'])) {
         $stripper->offsetSet('address1',$stripChainerFactory->get(stripChainers::FILTER_PARANOID_LEVEL2,
                                                $app,
                                                $_GET['address1']));
    }  
     $address2 = NULL;
    if (isset($_GET['address2'])) {
         $stripper->offsetSet('address2',$stripChainerFactory->get(stripChainers::FILTER_PARANOID_LEVEL2,
                                                $app,
                                                $_GET['address2']));
    }  
     $address3 = NULL;
    if (isset($_GET['address3'])) {
         $stripper->offsetSet('address3',$stripChainerFactory->get(stripChainers::FILTER_PARANOID_LEVEL2,
                                                $app,
                                                $_GET['address3']));
    }  
     $postalcode = NULL;
    if (isset($_GET['postalcode'])) {
         $stripper->offsetSet('postalcode',$stripChainerFactory->get(stripChainers::FILTER_PARANOID_LEVEL2,
                                                $app,
                                                $_GET['postalcode']));
    }  
    $phonenumber = NULL;
    if (isset($_GET['phonenumber'])) {
         $stripper->offsetSet('phonenumber',$stripChainerFactory->get(stripChainers::FILTER_ONLY_NUMBER_ALLOWED,
                                                $app,
                                                $_GET['phonenumber']));
    } 
     $email = NULL;
    if (isset($_GET['email'])) {
         $stripper->offsetSet('email',$stripChainerFactory->get(stripChainers::FILTER_PARANOID_LEVEL2,
                                                $app,
                                                $_GET['email']));
    } 
     $city = NULL;
    if (isset($_GET['city_id'])) {
         $stripper->offsetSet('city_id',$stripChainerFactory->get(stripChainers::FILTER_ONLY_NUMBER_ALLOWED,
                                                $app,
                                                $_GET['city_id']));
    } 
     $country = NULL;
    if (isset($_GET['country_id'])) {
         $stripper->offsetSet('country_id',$stripChainerFactory->get(stripChainers::FILTER_ONLY_NUMBER_ALLOWED,
                                                $app,
                                                $_GET['country_id']));
    } 
    
     $country2 = NULL;
    if (isset($_GET['country2_id'])) {
         $stripper->offsetSet('country2_id',$stripChainerFactory->get(stripChainers::FILTER_ONLY_NUMBER_ALLOWED,
                                                $app,
                                                $_GET['country2_id']));
    } 
     
    
    
  //&embrace_customer_no=TYU5675&tu_emb_customer_no= &ce_emb_customer_no=&other_emb_customer_no=&registration_name=denemefirm&trading_name=denemefirm&name_short=denemefirm&www=denemefirm.com&vatnumber=1231231&registration_number=321321321&registration_date=2018-10-10&ne_count_type_id=1&nv_count_type_id= 2&customer_category_id=2&reliability_id=1&turnover_rate_id=2&sector_type_id=3&application_type_id=1&segment_type_id=1&country2_id=107&address1=asd cad&address2=11 str&address3=5&postalcode=1231&city_id=151&country_id=107
    
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
    
    if($stripper->offsetExists('address1')) $address1 = $stripper->offsetGet('address1')->getFilterValue();
    if($stripper->offsetExists('address2')) $address2 = $stripper->offsetGet('address2')->getFilterValue();
    if($stripper->offsetExists('address3')) $address3 = $stripper->offsetGet('address3')->getFilterValue();
    if($stripper->offsetExists('postalcode')) $postalcode = $stripper->offsetGet('postalcode')->getFilterValue();
    if($stripper->offsetExists('phonenumber')) $phonenumber = $stripper->offsetGet('phonenumber')->getFilterValue();
    if($stripper->offsetExists('email')) $email = $stripper->offsetGet('email')->getFilterValue();
    if($stripper->offsetExists('turnover_rate_id')) $turnoverRateId = $stripper->offsetGet('turnover_rate_id')->getFilterValue();
    
    if($stripper->offsetExists('country2_id')) $country2 = $stripper->offsetGet('country2_id')->getFilterValue();
    if($stripper->offsetExists('country_id')) $country = $stripper->offsetGet('country_id')->getFilterValue();
    if($stripper->offsetExists('city_id')) $city = $stripper->offsetGet('city_id')->getFilterValue();
     //  if($stripper->offsetExists('turnover_rate_id')) $turnoverRateId = $stripper->offsetGet('turnover_rate_id')->getFilterValue();
    if($stripper->offsetExists('id')) $vId = $stripper->offsetGet('id')->getFilterValue();
     
          
    $resDataInsert = $BLL->updateAct(array(
            'Id' => $vId,   
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
        
         'Address1' => $address1,  
         'Address2' => $address2,  
         'Address1' => $address3,  
         'PostalCode' => $postalcode,  
         'PhoneNumber' => $phonenumber,  
         'Email' => $email,  
        
         'CityId' => $city,  
         'CountryId' => $country,  
         'TurnoverRateId' => $turnoverRateId,  
         'FirmCountryId' => $country2,  
            'pk' => $pk));
        
    $app->response()->header("Content-Type", "application/json"); 
    $app->response()->body(json_encode($resDataInsert));
    
}
);
 
/**
 *  * Okan CIRAN
 * @since 15-08-2018
 */
$app->get("/pkDeletedAct_infocustomer/", function () use ($app ) {
    $stripper = $app->getServiceManager()->get('filterChainerCustom');
    $stripChainerFactory = new \Services\Filter\Helper\FilterChainerFactory();    
    $BLL = $app->getBLLManager()->get('infoCustomerBLL');   
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