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
$app->get("/pkFillAccMatrixGridx_sysaccessoriesmatrix/", function () use ($app ) {
    $stripper = $app->getServiceManager()->get('filterChainerCustom');
    $stripChainerFactory = new \Services\Filter\Helper\FilterChainerFactory();
    $BLL = $app->getBLLManager()->get('sysAccessoriesMatrixBLL');
    $headerParams = $app->request()->headers();
    if (!isset($headerParams['X-Public']))
        throw new Exception('rest api "pkFillAccMatrixGridx_sysaccessoriesmatrix" end point, X-Public variable not found');
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
     
 
    $resDataGrid = $BLL->fillAccMatrixGridx(array(
        'language_code' => $vLanguageCode,
        'LanguageID' => $lid,
        'page' => $vPage,
        'rows' => $vRows,
        'sort' => $vSort,
        'order' => $vOrder,  
        'filterRules' => $filterRules,
        'pk' => $pk,
    ));
 
 
    $resTotalRowCount = $BLL->fillAccMatrixGridxRtl(array(
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
                "vehicle_group_id" => $menu["vehicle_group_id"],  
                "vehicle_group" => html_entity_decode($menu["vehicle_group"]), 
                "kpnumber_id" => $menu["kpnumber_id"], 
                "kp" => html_entity_decode($menu["kp"]),  
                "accessory_option_id" => $menu["accessory_option_id"], 
                "name_acc_opt" => html_entity_decode($menu["name_acc_opt"]), 
                "acc_deff_id" => $menu["acc_deff_id"], 
                "name_acc_deff_sm" => html_entity_decode($menu["name_acc_deff_sm"]), 
                "name_acc_deff_bo" => html_entity_decode($menu["name_acc_deff_bo"]), 
                 
                "supplier_id" => $menu["supplier_id"],   
                "supplier_name" => html_entity_decode($menu["supplier_name"]), 
                
                "cost_local" => $menu["cost_local"],   
                "cost_national" => $menu["cost_national"],   
           
                "part_num_local" => html_entity_decode($menu["part_num_local"]), 
                "part_num_nat" => html_entity_decode($menu["part_num_nat"]), 
                 
                "op_username" => html_entity_decode($menu["op_user_name"]),
                "active" => $menu["active"],   
                "state_active" => html_entity_decode($menu["state_active"]),       
                "date_saved" => $menu["date_saved"],
                "date_modified" => $menu["date_modified"],  
                "language_code" => $menu["language_code"],
                "language_name" =>html_entity_decode( $menu["language_name"]), 
                "language_id" => $menu["language_id"],
                "op_user_id" => $menu["op_user_id"], 
                
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
$app->get("/pkUpdateMakeActiveOrPassive_sysaccessoriesmatrix/", function () use ($app ) {
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
    $BLL = $app->getBLLManager()->get('sysAccessoriesMatrixBLL');

    
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
$app->get("/pkInsertAct_sysaccessoriesmatrix/", function () use ($app ) {
    $stripper = $app->getServiceManager()->get('filterChainerCustom');
    $stripChainerFactory = new \Services\Filter\Helper\FilterChainerFactory(); 
    $BLL = $app->getBLLManager()->get('sysAccessoriesMatrixBLL');  
    $headerParams = $app->request()->headers();
    if(!isset($headerParams['X-Public'])) throw new Exception ('rest api "pkInsertAct_sysaccessoriesmatrix" end point, X-Public variable not found');    
     $pk =  $headerParams['X-Public'];
       
    $supplierId = NULL;
    if (isset($_GET['supplier_id'])) {
         $stripper->offsetSet('supplier_id',$stripChainerFactory->get(stripChainers::FILTER_ONLY_NUMBER_ALLOWED,
                                                $app,
                                                $_GET['supplier_id']));
    } 
    $vehicleGroupId = NULL;
    if (isset($_GET['vehicle_group_id'])) {
         $stripper->offsetSet('vehicle_group_id',$stripChainerFactory->get(stripChainers::FILTER_ONLY_NUMBER_ALLOWED,
                                                $app,
                                                $_GET['vehicle_group_id']));
    } 
    $kpnumberId = NULL;
    if (isset($_GET['kpnumber_id'])) {
         $stripper->offsetSet('kpnumber_id',$stripChainerFactory->get(stripChainers::FILTER_ONLY_NUMBER_ALLOWED,
                                                $app,
                                                $_GET['kpnumber_id']));
    } 
    $accessoryOptionId = NULL;
    if (isset($_GET['accessory_option_id'])) {
         $stripper->offsetSet('accessory_option_id',$stripChainerFactory->get(stripChainers::FILTER_ONLY_NUMBER_ALLOWED,
                                                $app,
                                                $_GET['accessory_option_id']));
    } 
    $accDeffId = NULL;
    if (isset($_GET['acc_deff_id'])) {
         $stripper->offsetSet('acc_deff_id',$stripChainerFactory->get(stripChainers::FILTER_ONLY_NUMBER_ALLOWED,
                                                $app,
                                                $_GET['acc_deff_id']));
    } 
    $costLocal = NULL;
    if (isset($_GET['cost_local'])) {
         $stripper->offsetSet('cost_local',$stripChainerFactory->get(stripChainers::FILTER_PARANOID_LEVEL2,
                                                $app,
                                                $_GET['cost_local']));
    } 
    $costNational = NULL;
    if (isset($_GET['cost_national'])) {
         $stripper->offsetSet('cost_national',$stripChainerFactory->get(stripChainers::FILTER_PARANOID_LEVEL2,
                                                $app,
                                                $_GET['cost_national']));
    }  
    $partNumLocal = NULL;
    if (isset($_GET['part_num_local'])) {
         $stripper->offsetSet('part_num_local',$stripChainerFactory->get(stripChainers::FILTER_PARANOID_LEVEL2,
                                                $app,
                                                $_GET['part_num_local']));
    } 
    $partNumNat = NULL;
    if (isset($_GET['part_num_nat'])) {
         $stripper->offsetSet('part_num_nat',$stripChainerFactory->get(stripChainers::FILTER_PARANOID_LEVEL2,
                                                $app,
                                                $_GET['part_num_nat']));
    } 
          
 //&vehicle_group_id=2,&kpnumber_id=2,&supplier_id=2,&acc_deff_id=1,&accessory_option_id=1,&cost_local=123,&cost_national=144,&part_num_local=fff,&part_num_nat=ddd,

    
     
    $stripper->strip(); 
    if($stripper->offsetExists('supplier_id')) $supplierId = $stripper->offsetGet('supplier_id')->getFilterValue();
    if($stripper->offsetExists('vehicle_group_id')) $vehicleGroupId = $stripper->offsetGet('vehicle_group_id')->getFilterValue();
    if($stripper->offsetExists('kpnumber_id')) $kpnumberId = $stripper->offsetGet('kpnumber_id')->getFilterValue();
    if($stripper->offsetExists('acc_deff_id')) $accDeffId = $stripper->offsetGet('acc_deff_id')->getFilterValue();
    if($stripper->offsetExists('accessory_option_id')) $accessoryOptionId = $stripper->offsetGet('accessory_option_id')->getFilterValue(); 
    if($stripper->offsetExists('cost_local')) $costLocal = $stripper->offsetGet('cost_local')->getFilterValue();
    if($stripper->offsetExists('cost_national')) $costNational = $stripper->offsetGet('cost_national')->getFilterValue();
    if($stripper->offsetExists('part_num_local')) $partNumLocal = $stripper->offsetGet('part_num_local')->getFilterValue();
    if($stripper->offsetExists('part_num_nat')) $partNumNat = $stripper->offsetGet('part_num_nat')->getFilterValue();
     
    
    
    $resDataInsert = $BLL->insertAct(array(
            'SupplierId' => $supplierId,   
            'VehicleGroupId' => $vehicleGroupId,  
            'KpnumberId' => $kpnumberId, 
            'AccDeffId' => $accDeffId, 
            'AccessoryOptionId' => $accessoryOptionId,   
        
            'CostLocal' => $costLocal, 
            'CostNational' => $costNational,   
        
            'PartNumLocal' => $partNumLocal, 
            'PartNumNat' => $partNumNat,   
            'pk' => $pk));
        
    $app->response()->header("Content-Type", "application/json"); 
    $app->response()->body(json_encode($resDataInsert));
    
}
);

/**
 *  * Okan CIRAN
 * @since 15-08-2018
 */ 
$app->get("/pkUpdateAct_sysaccessoriesmatrix/", function () use ($app ) {
    $stripper = $app->getServiceManager()->get('filterChainerCustom');
    $stripChainerFactory = new \Services\Filter\Helper\FilterChainerFactory(); 
    $BLL = $app->getBLLManager()->get('sysAccessoriesMatrixBLL');  
    $headerParams = $app->request()->headers();
    if(!isset($headerParams['X-Public'])) throw new Exception ('rest api "pkUpdateAct_sysaccessoriesmatrix" end point, X-Public variable not found');    
    $pk = $headerParams['X-Public'];
    
    $vId = NULL;
    if (isset($_GET['id'])) {
         $stripper->offsetSet('id',$stripChainerFactory->get(stripChainers::FILTER_ONLY_NUMBER_ALLOWED,
                                                $app,
                                                $_GET['id']));
    } 
    $supplierId = NULL;
    if (isset($_GET['supplier_id'])) {
         $stripper->offsetSet('supplier_id',$stripChainerFactory->get(stripChainers::FILTER_ONLY_NUMBER_ALLOWED,
                                                $app,
                                                $_GET['supplier_id']));
    } 
    $vehicleGroupId = NULL;
    if (isset($_GET['vehicle_group_id'])) {
         $stripper->offsetSet('vehicle_group_id',$stripChainerFactory->get(stripChainers::FILTER_ONLY_NUMBER_ALLOWED,
                                                $app,
                                                $_GET['vehicle_group_id']));
    } 
    $kpnumberId = NULL;
    if (isset($_GET['kpnumber_id'])) {
         $stripper->offsetSet('kpnumber_id',$stripChainerFactory->get(stripChainers::FILTER_ONLY_NUMBER_ALLOWED,
                                                $app,
                                                $_GET['kpnumber_id']));
    } 
    $accessoryOptionId = NULL;
    if (isset($_GET['accessory_option_id'])) {
         $stripper->offsetSet('accessory_option_id',$stripChainerFactory->get(stripChainers::FILTER_ONLY_NUMBER_ALLOWED,
                                                $app,
                                                $_GET['accessory_option_id']));
    } 
    $accDeffId = NULL;
    if (isset($_GET['acc_deff_id'])) {
         $stripper->offsetSet('acc_deff_id',$stripChainerFactory->get(stripChainers::FILTER_ONLY_NUMBER_ALLOWED,
                                                $app,
                                                $_GET['acc_deff_id']));
    } 
    $costLocal = NULL;
    if (isset($_GET['cost_local'])) {
         $stripper->offsetSet('cost_local',$stripChainerFactory->get(stripChainers::FILTER_PARANOID_LEVEL2,
                                                $app,
                                                $_GET['cost_local']));
    } 
    $costNational = NULL;
    if (isset($_GET['cost_national'])) {
         $stripper->offsetSet('cost_national',$stripChainerFactory->get(stripChainers::FILTER_PARANOID_LEVEL2,
                                                $app,
                                                $_GET['cost_national']));
    }  
    $partNumLocal = NULL;
    if (isset($_GET['part_num_local'])) {
         $stripper->offsetSet('part_num_local',$stripChainerFactory->get(stripChainers::FILTER_PARANOID_LEVEL2,
                                                $app,
                                                $_GET['part_num_local']));
    } 
    $partNumNat = NULL;
    if (isset($_GET['part_num_nat'])) {
         $stripper->offsetSet('part_num_nat',$stripChainerFactory->get(stripChainers::FILTER_PARANOID_LEVEL2,
                                                $app,
                                                $_GET['part_num_nat']));
    } 
              
     
    $stripper->strip(); 
    if($stripper->offsetExists('supplier_id')) $supplierId = $stripper->offsetGet('supplier_id')->getFilterValue();
    if($stripper->offsetExists('vehicle_group_id')) $vehicleGroupId = $stripper->offsetGet('vehicle_group_id')->getFilterValue();
    if($stripper->offsetExists('kpnumber_id')) $kpnumberId = $stripper->offsetGet('kpnumber_id')->getFilterValue();
    if($stripper->offsetExists('acc_deff_id')) $accDeffId = $stripper->offsetGet('acc_deff_id')->getFilterValue();
    if($stripper->offsetExists('accessory_option_id')) $accessoryOptionId = $stripper->offsetGet('accessory_option_id')->getFilterValue(); 
    if($stripper->offsetExists('cost_local')) $costLocal = $stripper->offsetGet('cost_local')->getFilterValue();
    if($stripper->offsetExists('cost_national')) $costNational = $stripper->offsetGet('cost_national')->getFilterValue();
    if($stripper->offsetExists('part_num_local')) $partNumLocal = $stripper->offsetGet('part_num_local')->getFilterValue();
    if($stripper->offsetExists('part_num_nat')) $partNumNat = $stripper->offsetGet('part_num_nat')->getFilterValue(); 
    if($stripper->offsetExists('id')) $vId = $stripper->offsetGet('id')->getFilterValue();
     
          
    $resDataInsert = $BLL->updateAct(array(
            'Id' => $vId,   
            'SupplierId' => $supplierId,   
            'VehicleGroupId' => $vehicleGroupId,  
            'KpnumberId' => $kpnumberId, 
            'AccDeffId' => $accDeffId, 
            'AccessoryOptionId' => $accessoryOptionId,  
            'CostLocal' => $costLocal, 
            'CostNational' => $costNational,  
            'PartNumLocal' => $partNumLocal, 
            'PartNumNat' => $partNumNat,   
            'pk' => $pk));
        
    $app->response()->header("Content-Type", "application/json"); 
    $app->response()->body(json_encode($resDataInsert));
    
}
);
 
/**
 *  * Okan CIRAN
 * @since 15-08-2018
 */
$app->get("/pkDeletedAct_sysaccessoriesmatrix/", function () use ($app ) {
    $stripper = $app->getServiceManager()->get('filterChainerCustom');
    $stripChainerFactory = new \Services\Filter\Helper\FilterChainerFactory();    
    $BLL = $app->getBLLManager()->get('sysAccessoriesMatrixBLL');   
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