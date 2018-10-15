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
$app->get("/pkSupplierLongDdList_syssupplier/", function () use ($app ) {
    $stripper = $app->getServiceManager()->get('filterChainerCustom');
    $stripChainerFactory = new \Services\Filter\Helper\FilterChainerFactory(); 
    $BLL = $app->getBLLManager()->get('sysSupplierBLL');
    
    $componentType = 'ddslick';
    if (isset($_GET['component_type'])) {
        $componentType = strtolower(trim($_GET['component_type']));
    }
    $headerParams = $app->request()->headers();
    if(!isset($headerParams['X-Public'])) throw new Exception ('rest api "pkSupplierLongDdList_syssupplier" end point, X-Public variable not found');
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
        
    $resCombobox = $BLL->supplierLongDdList(array(                                   
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

 /**
 *  * Okan CIRAN
 * @since 11.08.2018
 */
$app->get("/pkSupplierShortDdList_syssupplier/", function () use ($app ) {
    $stripper = $app->getServiceManager()->get('filterChainerCustom');
    $stripChainerFactory = new \Services\Filter\Helper\FilterChainerFactory(); 
    $BLL = $app->getBLLManager()->get('sysSupplierBLL');
    
    $componentType = 'ddslick';
    if (isset($_GET['component_type'])) {
        $componentType = strtolower(trim($_GET['component_type']));
    }
    $headerParams = $app->request()->headers();
    if(!isset($headerParams['X-Public'])) throw new Exception ('rest api "pkSupplierShortDdList_syssupplier" end point, X-Public variable not found');
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
        
    $resCombobox = $BLL->supplierShortDdList(array(                                   
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
 
/**
 *  * Okan CIRAN
 * @since 15-08-2018
 */
$app->get("/pkFillSupplierGridx_syssupplier/", function () use ($app ) {
    $stripper = $app->getServiceManager()->get('filterChainerCustom');
    $stripChainerFactory = new \Services\Filter\Helper\FilterChainerFactory();
    $BLL = $app->getBLLManager()->get('sysSupplierBLL');
    $headerParams = $app->request()->headers();
    if (!isset($headerParams['X-Public']))
        throw new Exception('rest api "pkFillSupplierGridx_syssupplier" end point, X-Public variable not found');
    $pk = $headerParams['X-Public'];

    $vLanguageCode = 'en';
    if (isset($_GET['language_code'])) {
        $stripper->offsetSet('language_code', $stripChainerFactory->get(stripChainers::FILTER_ONLY_LANGUAGE_CODE, $app, $_GET['language_code']));
    }
    $vAccBodyTypeID= NULL;
    if (isset($_GET['acc_body_type_id'])) {
        $stripper->offsetSet('acc_body_type_id', $stripChainerFactory->get(stripChainers::FILTER_ONLY_NUMBER_ALLOWED,  $app,   $_GET['acc_body_type_id']));
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
    if ($stripper->offsetExists('acc_body_type_id'))$vAccBodyTypeID = $stripper->offsetGet('acc_body_type_id')->getFilterValue();
    if ($stripper->offsetExists('page')) { $vPage = $stripper->offsetGet('page')->getFilterValue(); }
    if ($stripper->offsetExists('rows')) { $vRows = $stripper->offsetGet('rows')->getFilterValue(); }
    if ($stripper->offsetExists('sort')) { $vSort = $stripper->offsetGet('sort')->getFilterValue(); }
    if ($stripper->offsetExists('order')) { $vOrder = $stripper->offsetGet('order')->getFilterValue(); }
    if ($stripper->offsetExists('filterRules')) { $filterRules = $stripper->offsetGet('filterRules')->getFilterValue(); } 

    $resDataGrid = $BLL->fillSupplierGridx(array(
        'language_code' => $vLanguageCode,
        'LanguageID' => $lid,
        'page' => $vPage,
        'rows' => $vRows,
        'sort' => $vSort,
        'order' => $vOrder,
        'AccBodyTypeID' => $vAccBodyTypeID,
        'filterRules' => $filterRules,
        'pk' => $pk,
    ));
   
    $resTotalRowCount = $BLL->fillSupplierGridxRtl(array(
        'language_code' => $vLanguageCode, 
        'LanguageID' => $lid,
        'AccBodyTypeID' => $vAccBodyTypeID,
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
                "name" => html_entity_decode($menu["name"]), 
                "city_id" => $menu["city_id"], 
                "city_name" => html_entity_decode($menu["city_name"]),   
                
                "region_id" => $menu["region_id"], 
                "region" => html_entity_decode($menu["region"]),   
                "country_id" => $menu["country_id"], 
                "country_name" => html_entity_decode($menu["country_name"]),  
                "address1" => html_entity_decode($menu["address1"]),  
                "address2" => html_entity_decode($menu["address2"]),  
                "address3" => html_entity_decode($menu["address3"]),  
                "postalcode" => html_entity_decode($menu["postalcode"]),  
                "tel" => html_entity_decode($menu["tel"]),  
                "fax" => html_entity_decode($menu["fax"]),  
                "email" => html_entity_decode($menu["email"]),  
                "name_short" => html_entity_decode($menu["name_short"]),  
                  
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
$app->get("/pkUpdateMakeActiveOrPassive_syssupplier/", function () use ($app ) {
    $BLLUser = $app->getBLLManager()->get('infoUsersBLL');
    $RedisConnect = $app->getServiceManager()->get('redisConnectFactory');
    $headerParams = $app->request()->headers();
    
    print_r($headerParams) ; 
 
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
                $resDatacontrol = $BLLUser->getUserId(array(
                    'pk' => $rid, 
                )); 
                
                
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
    $BLL = $app->getBLLManager()->get('sysSupplierBLL');

    
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
$app->get("/pkInsertAct_syssupplier/", function () use ($app ) {
    $stripper = $app->getServiceManager()->get('filterChainerCustom');
    $stripChainerFactory = new \Services\Filter\Helper\FilterChainerFactory(); 
    $BLL = $app->getBLLManager()->get('sysSupplierBLL');  
    $headerParams = $app->request()->headers();
    if(!isset($headerParams['X-Public'])) throw new Exception ('rest api "pkInsertAct_syssupplier" end point, X-Public variable not found');    
     $pk =  $headerParams['X-Public'];
      
    $vName = NULL;
    if (isset($_GET['name'])) {
         $stripper->offsetSet('name',$stripChainerFactory->get(stripChainers::FILTER_PARANOID_LEVEL2,
                                                $app,
                                                $_GET['name']));
    }  
    $vNameshort = NULL;
    if (isset($_GET['name_short'])) {
         $stripper->offsetSet('name_short',$stripChainerFactory->get(stripChainers::FILTER_PARANOID_LEVEL2,
                                                $app,
                                                $_GET['name_short']));
    }  
    $countryId = NULL;
    if (isset($_GET['country_id'])) {
         $stripper->offsetSet('country_id',$stripChainerFactory->get(stripChainers::FILTER_ONLY_NUMBER_ALLOWED,
                                                $app,
                                                $_GET['country_id']));
    } 
     $cityId = NULL;
    if (isset($_GET['city_id'])) {
         $stripper->offsetSet('city_id',$stripChainerFactory->get(stripChainers::FILTER_ONLY_NUMBER_ALLOWED,
                                                $app,
                                                $_GET['city_id']));
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
      $email= NULL;
    if (isset($_GET['email'])) {
         $stripper->offsetSet('email',$stripChainerFactory->get(stripChainers::FILTER_PARANOID_LEVEL2,
                                                $app,
                                                $_GET['email']));
    }  
     
    $stripper->strip();
    if($stripper->offsetExists('name')) $vName = $stripper->offsetGet('name')->getFilterValue(); 
    if($stripper->offsetExists('name_short')) $vNameshort = $stripper->offsetGet('name_short')->getFilterValue(); 
    if($stripper->offsetExists('country_id')) $countryId = $stripper->offsetGet('country_id')->getFilterValue(); 
    if($stripper->offsetExists('city_id')) $cityId = $stripper->offsetGet('city_id')->getFilterValue(); 
    if($stripper->offsetExists('address1')) $address1 = $stripper->offsetGet('address1')->getFilterValue(); 
    if($stripper->offsetExists('address2')) $address2 = $stripper->offsetGet('address2')->getFilterValue(); 
    if($stripper->offsetExists('address3')) $address3 = $stripper->offsetGet('address3')->getFilterValue(); 
    if($stripper->offsetExists('postalcode')) $postalcode = $stripper->offsetGet('postalcode')->getFilterValue(); 
    if($stripper->offsetExists('tel')) $tel = $stripper->offsetGet('tel')->getFilterValue(); 
    if($stripper->offsetExists('fax')) $fax = $stripper->offsetGet('fax')->getFilterValue(); 
    if($stripper->offsetExists('email')) $email = $stripper->offsetGet('email')->getFilterValue(); 
             
    
    $resDataInsert = $BLL->insertAct(array(
            'Name' => $vName,  
            'NameShort' => $vNameshort,  
            'CountryId' => $countryId,  
            'CityId' => $cityId,  
            'Address1' => $address1,  
            'Address2' => $address2,  
            'Address3' => $address3,  
            'Postalcode' => $postalcode,  
            'Tel' => $tel,  
            'Fax' => $fax,  
            'Email' => $email,   
            'pk' => $pk));
        
    $app->response()->header("Content-Type", "application/json"); 
    $app->response()->body(json_encode($resDataInsert));
    
}
);

/**
 *  * Okan CIRAN
 * @since 15-08-2018
 */ 
$app->get("/pkUpdateAct_syssupplier/", function () use ($app ) {
    $stripper = $app->getServiceManager()->get('filterChainerCustom');
    $stripChainerFactory = new \Services\Filter\Helper\FilterChainerFactory(); 
    $BLL = $app->getBLLManager()->get('sysSupplierBLL');  
    $headerParams = $app->request()->headers();
    if(!isset($headerParams['X-Public'])) throw new Exception ('rest api "pkUpdateAct_syssupplier" end point, X-Public variable not found');    
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
    $vNameshort = NULL;
    if (isset($_GET['name_short'])) {
         $stripper->offsetSet('name_short',$stripChainerFactory->get(stripChainers::FILTER_PARANOID_LEVEL2,
                                                $app,
                                                $_GET['name_short']));
    }  
    $countryId = NULL;
    if (isset($_GET['country_id'])) {
         $stripper->offsetSet('country_id',$stripChainerFactory->get(stripChainers::FILTER_ONLY_NUMBER_ALLOWED,
                                                $app,
                                                $_GET['country_id']));
    } 
     $cityId = NULL;
    if (isset($_GET['city_id'])) {
         $stripper->offsetSet('city_id',$stripChainerFactory->get(stripChainers::FILTER_ONLY_NUMBER_ALLOWED,
                                                $app,
                                                $_GET['city_id']));
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
      $email= NULL;
    if (isset($_GET['email'])) {
         $stripper->offsetSet('email',$stripChainerFactory->get(stripChainers::FILTER_PARANOID_LEVEL2,
                                                $app,
                                                $_GET['email']));
    }  
     
    $stripper->strip();
    if($stripper->offsetExists('name')) $vName = $stripper->offsetGet('name')->getFilterValue(); 
    if($stripper->offsetExists('name_short')) $vNameshort = $stripper->offsetGet('name_short')->getFilterValue(); 
    if($stripper->offsetExists('country_id')) $countryId = $stripper->offsetGet('country_id')->getFilterValue(); 
    if($stripper->offsetExists('city_id')) $cityId = $stripper->offsetGet('city_id')->getFilterValue(); 
    if($stripper->offsetExists('address1')) $address1 = $stripper->offsetGet('address1')->getFilterValue(); 
    if($stripper->offsetExists('address2')) $address2 = $stripper->offsetGet('address2')->getFilterValue(); 
    if($stripper->offsetExists('address3')) $address3 = $stripper->offsetGet('address3')->getFilterValue(); 
    if($stripper->offsetExists('postalcode')) $postalcode = $stripper->offsetGet('postalcode')->getFilterValue(); 
    if($stripper->offsetExists('tel')) $tel = $stripper->offsetGet('tel')->getFilterValue(); 
    if($stripper->offsetExists('fax')) $fax = $stripper->offsetGet('fax')->getFilterValue(); 
    if($stripper->offsetExists('email')) $email = $stripper->offsetGet('email')->getFilterValue(); 
    if($stripper->offsetExists('id')) $vId = $stripper->offsetGet('id')->getFilterValue();
     
          
    $resDataInsert = $BLL->updateAct(array(
            'Id' => $vId,   
            'Name' => $vName,  
            'NameShort' => $vNameshort,  
            'CountryId' => $countryId,  
            'CityId' => $cityId,  
            'Address1' => $address1,  
            'Address2' => $address2,  
            'Address3' => $address3,  
            'Postalcode' => $postalcode,  
            'Tel' => $tel,  
            'Fax' => $fax,  
            'Email' => $email,  
            'pk' => $pk));
        
    $app->response()->header("Content-Type", "application/json"); 
    $app->response()->body(json_encode($resDataInsert));
    
}
);
 
/**
 *  * Okan CIRAN
 * @since 15-08-2018
 */
$app->get("/pkDeletedAct_syssupplier/", function () use ($app ) {
    $stripper = $app->getServiceManager()->get('filterChainerCustom');
    $stripChainerFactory = new \Services\Filter\Helper\FilterChainerFactory();    
    $BLL = $app->getBLLManager()->get('sysSupplierBLL');   
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