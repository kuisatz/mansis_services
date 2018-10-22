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
$app->get("/pkFillProjectTIGridx_infoprojecttradein/", function () use ($app ) {
    $stripper = $app->getServiceManager()->get('filterChainerCustom');
    $stripChainerFactory = new \Services\Filter\Helper\FilterChainerFactory();
    $BLL = $app->getBLLManager()->get('infoProjectTradeinBLL');
    $headerParams = $app->request()->headers();
    if (!isset($headerParams['X-Public']))
        throw new Exception('rest api "pkFillProjectTIGridx_infoprojecttradein" end point, X-Public variable not found');
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

    $resDataGrid = $BLL->fillProjectTIGridx(array(
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
   
    $resTotalRowCount = $BLL->fillProjectTIGridxRtl(array(
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
              
                "quantity" =>  ($menu["quantity"]),
                "topused" => ($menu["topused"]), 
                "customer" => ($menu["customer"]),
                "over_allowance" => ($menu["over_allowance"]),
               
                "isbo_confirm" =>  ($menu["isbo_confirm"]),
                "ishos_confirm" =>  ($menu["ishos_confirm"]),
                "sa_description" => html_entity_decode($menu["sa_description"]),
                "bo_description" => html_entity_decode($menu["bo_description"]),
                
                  
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
$app->get("/pkUpdateMakeActiveOrPassive_infoprojecttradein/", function () use ($app ) {
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
    $BLL = $app->getBLLManager()->get('infoProjectTradeinBLL');

    
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
$app->get("/pkInsertAct_infoprojecttradein/", function () use ($app ) {
    $stripper = $app->getServiceManager()->get('filterChainerCustom');
    $stripChainerFactory = new \Services\Filter\Helper\FilterChainerFactory(); 
    $BLL = $app->getBLLManager()->get('infoProjectTradeinBLL');  
    $headerParams = $app->request()->headers();
    if(!isset($headerParams['X-Public'])) throw new Exception ('rest api "pkInsertAct_infoprojecttradein" end point, X-Public variable not found');    
     $pk =  $headerParams['X-Public'];
      
    $ProjectId = NULL;
    if (isset($_GET['project_id'])) {
         $stripper->offsetSet('project_id',$stripChainerFactory->get(stripChainers::FILTER_ONLY_NUMBER_ALLOWED,
                                                $app,
                                                $_GET['project_id']));
    }  
    $quantity = NULL;
    if (isset($_GET['quantity'])) {
         $stripper->offsetSet('quantity',$stripChainerFactory->get(stripChainers::FILTER_ONLY_NUMBER_ALLOWED,
                                                $app,
                                                $_GET['quantity']));
    } 
    $topused = NULL;
    if (isset($_GET['topused'])) {
         $stripper->offsetSet('topused',$stripChainerFactory->get(stripChainers::FILTER_ONLY_NUMBER_ALLOWED,
                                                $app,
                                                $_GET['topused']));
    }  
     $customer = NULL;
    if (isset($_GET['customer'])) {
         $stripper->offsetSet('customer',$stripChainerFactory->get(stripChainers::FILTER_ONLY_NUMBER_ALLOWED,
                                                $app,
                                                $_GET['customer']));
    } 
    $overAllowance= NULL;
    if (isset($_GET['over_allowance'])) {
         $stripper->offsetSet('over_allowance',$stripChainerFactory->get(stripChainers::FILTER_ONLY_NUMBER_ALLOWED,
                                                $app,
                                                $_GET['over_allowance']));
    }  
      
    $saDescription = NULL;
    if (isset($_GET['sa_description'])) {
         $stripper->offsetSet('sa_description',$stripChainerFactory->get(stripChainers::FILTER_PARANOID_LEVEL2,
                                                $app,
                                                $_GET['sa_description']));
    }
    $boDescription= NULL;
    if (isset($_GET['bo_description'])) {
         $stripper->offsetSet('bo_description',$stripChainerFactory->get(stripChainers::FILTER_PARANOID_LEVEL2,
                                                $app,
                                                $_GET['bo_description']));
    }
 
    $isboConfirm = NULL;
    if (isset($_GET['isbo_confirm'])) {
         $stripper->offsetSet('isbo_confirm',$stripChainerFactory->get(stripChainers::FILTER_ONLY_NUMBER_ALLOWED,
                                                $app,
                                                $_GET['isbo_confirm']));
    } 
    $ishosConfirm = NULL;
    if (isset($_GET['ishos_confirm'])) {
         $stripper->offsetSet('ishos_confirm',$stripChainerFactory->get(stripChainers::FILTER_ONLY_NUMBER_ALLOWED,
                                                $app,
                                                $_GET['ishos_confirm']));
    } 
     
                      
    $stripper->strip();
    if($stripper->offsetExists('project_id')) $ProjectId = $stripper->offsetGet('project_id')->getFilterValue(); 
    if($stripper->offsetExists('quantity')) $quantity = $stripper->offsetGet('quantity')->getFilterValue();
    if($stripper->offsetExists('topused')) $topused = $stripper->offsetGet('topused')->getFilterValue();
    if($stripper->offsetExists('customer')) $customer = $stripper->offsetGet('customer')->getFilterValue(); 
    if($stripper->offsetExists('over_allowance')) $overAllowance = $stripper->offsetGet('over_allowance')->getFilterValue();  
    if($stripper->offsetExists('sa_description')) $saDescription = $stripper->offsetGet('sa_description')->getFilterValue(); 
    if($stripper->offsetExists('bo_description')) $boDescription = $stripper->offsetGet('bo_description')->getFilterValue();  
    if($stripper->offsetExists('isbo_confirm')) $isboConfirm = $stripper->offsetGet('isbo_confirm')->getFilterValue(); 
    if($stripper->offsetExists('ishos_confirm')) $ishosConfirm = $stripper->offsetGet('ishos_confirm')->getFilterValue(); 
  
      
    /*    
                   
      &project_id=1&vehicles_endgroup_id=1&vehicles_trade_id=1&customer_type_id=1&comfort_super_id=1&terrain_id=1&vehicle_group_id=1&hydraulics_id=1&buyback_matrix_id=1&quantity=1&is_other=1&other_month_value=1&other_milages_value=1&other_description=1&deal_tb_value=1&isbo_confirm=1&ishos_confirm=1                     
    
     */  
    
    $resDataInsert = $BLL->insertAct(array(
            'ProjectId' => $ProjectId,   
            'Quantity' => $quantity,
            'Topused' => $topused,  
            'Customer' => $customer,  
            'OverAllowance' => $overAllowance,   
         
            'SaDescription' => $saDescription,   
            'BoDescription' => $boDescription,  
            'IsBoConfirm' => $isboConfirm,   
            'IsHosConfirm' => $ishosConfirm,   
         
            'pk' => $pk));
        
    $app->response()->header("Content-Type", "application/json"); 
    $app->response()->body(json_encode($resDataInsert));
    
}
);

/**
 *  * Okan CIRAN
 * @since 15-08-2018
 */ 
$app->get("/pkUpdateAct_infoprojecttradein/", function () use ($app ) {
    $stripper = $app->getServiceManager()->get('filterChainerCustom');
    $stripChainerFactory = new \Services\Filter\Helper\FilterChainerFactory(); 
    $BLL = $app->getBLLManager()->get('infoProjectTradeinBLL');  
    $headerParams = $app->request()->headers();
    if(!isset($headerParams['X-Public'])) throw new Exception ('rest api "pkUpdateAct_infoprojecttradein" end point, X-Public variable not found');    
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
    $quantity = NULL;
    if (isset($_GET['quantity'])) {
         $stripper->offsetSet('quantity',$stripChainerFactory->get(stripChainers::FILTER_ONLY_NUMBER_ALLOWED,
                                                $app,
                                                $_GET['quantity']));
    } 
    $topused = NULL;
    if (isset($_GET['topused'])) {
         $stripper->offsetSet('topused',$stripChainerFactory->get(stripChainers::FILTER_ONLY_NUMBER_ALLOWED,
                                                $app,
                                                $_GET['topused']));
    }  
     $customer = NULL;
    if (isset($_GET['customer'])) {
         $stripper->offsetSet('customer',$stripChainerFactory->get(stripChainers::FILTER_ONLY_NUMBER_ALLOWED,
                                                $app,
                                                $_GET['customer']));
    } 
    $overAllowance= NULL;
    if (isset($_GET['over_allowance'])) {
         $stripper->offsetSet('over_allowance',$stripChainerFactory->get(stripChainers::FILTER_ONLY_NUMBER_ALLOWED,
                                                $app,
                                                $_GET['over_allowance']));
    }  
      
    $saDescription = NULL;
    if (isset($_GET['sa_description'])) {
         $stripper->offsetSet('sa_description',$stripChainerFactory->get(stripChainers::FILTER_PARANOID_LEVEL2,
                                                $app,
                                                $_GET['sa_description']));
    }
    $boDescription= NULL;
    if (isset($_GET['bo_description'])) {
         $stripper->offsetSet('bo_description',$stripChainerFactory->get(stripChainers::FILTER_PARANOID_LEVEL2,
                                                $app,
                                                $_GET['bo_description']));
    }
 
    $isboConfirm = NULL;
    if (isset($_GET['isbo_confirm'])) {
         $stripper->offsetSet('isbo_confirm',$stripChainerFactory->get(stripChainers::FILTER_ONLY_NUMBER_ALLOWED,
                                                $app,
                                                $_GET['isbo_confirm']));
    } 
    $ishosConfirm = NULL;
    if (isset($_GET['ishos_confirm'])) {
         $stripper->offsetSet('ishos_confirm',$stripChainerFactory->get(stripChainers::FILTER_ONLY_NUMBER_ALLOWED,
                                                $app,
                                                $_GET['ishos_confirm']));
    } 
    
   
    $stripper->strip();
    if($stripper->offsetExists('project_id')) $ProjectId = $stripper->offsetGet('project_id')->getFilterValue(); 
    if($stripper->offsetExists('quantity')) $quantity = $stripper->offsetGet('quantity')->getFilterValue();
    if($stripper->offsetExists('topused')) $topused = $stripper->offsetGet('topused')->getFilterValue();
    if($stripper->offsetExists('customer')) $customer = $stripper->offsetGet('customer')->getFilterValue(); 
    if($stripper->offsetExists('over_allowance')) $overAllowance = $stripper->offsetGet('over_allowance')->getFilterValue();  
    if($stripper->offsetExists('sa_description')) $saDescription = $stripper->offsetGet('sa_description')->getFilterValue(); 
    if($stripper->offsetExists('bo_description')) $boDescription = $stripper->offsetGet('bo_description')->getFilterValue();  
    if($stripper->offsetExists('isbo_confirm')) $isboConfirm = $stripper->offsetGet('isbo_confirm')->getFilterValue(); 
    if($stripper->offsetExists('ishos_confirm')) $ishosConfirm = $stripper->offsetGet('ishos_confirm')->getFilterValue(); 
    if($stripper->offsetExists('id')) $vId = $stripper->offsetGet('id')->getFilterValue();
     
          
    $resDataInsert = $BLL->updateAct(array(
            'Id' => $vId,    
           'ProjectId' => $ProjectId,   
            'Quantity' => $quantity,
            'Topused' => $topused,  
            'Customer' => $customer,  
            'OverAllowance' => $overAllowance,   
         
            'SaDescription' => $saDescription,   
            'BoDescription' => $boDescription,  
            'IsBoConfirm' => $isboConfirm,   
            'IsHosConfirm' => $ishosConfirm,   
            'pk' => $pk));
        
    $app->response()->header("Content-Type", "application/json"); 
    $app->response()->body(json_encode($resDataInsert));
    
}
);
 
/**
 *  * Okan CIRAN
 * @since 15-08-2018
 */
$app->get("/pkDeletedAct_infoprojecttradein/", function () use ($app ) {
    $stripper = $app->getServiceManager()->get('filterChainerCustom');
    $stripChainerFactory = new \Services\Filter\Helper\FilterChainerFactory();    
    $BLL = $app->getBLLManager()->get('infoProjectTradeinBLL');   
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