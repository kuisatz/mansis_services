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
 * @since 05.08.2018
 */
$app->get("/pkProjectVehicleDdList_infoprojectacc/", function () use ($app ) { 
    $stripper = $app->getServiceManager()->get('filterChainerCustom');
    $stripChainerFactory = new \Services\Filter\Helper\FilterChainerFactory(); 
    $BLL = $app->getBLLManager()->get('infoProjectAccBLL');
    
    $componentType = 'ddslick';
    if (isset($_GET['component_type'])) {
        $componentType = strtolower(trim($_GET['component_type']));
    }
    $headerParams = $app->request()->headers();
    if(!isset($headerParams['X-Public'])) throw new Exception ('rest api "pkProjectVehicleDdList_infoprojectacc" end point, X-Public variable not found');
   // $pk = $headerParams['X-Public'];
    
    $vLanguageCode = 'en';
    if (isset($_GET['language_code'])) {
         $stripper->offsetSet('language_code',$stripChainerFactory->get(stripChainers::FILTER_ONLY_LANGUAGE_CODE,
                                                $app,
                                                $_GET['language_code']));
    }
    $lid = 385;
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
    if($stripper->offsetExists('project_id')) $ProjectId = $stripper->offsetGet('project_id')->getFilterValue();
    if($stripper->offsetExists('language_code')) $vLanguageCode = $stripper->offsetGet('language_code')->getFilterValue();
        
    $resCombobox = $BLL->ProjectVehicleDdList(array(                                   
                                    'language_code' => $vLanguageCode,
                                    'ProjectId' => $ProjectId,
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
$app->get("/pkFillProjectVehicleAccGridx_infoprojectacc/", function () use ($app ) {
    $stripper = $app->getServiceManager()->get('filterChainerCustom');
    $stripChainerFactory = new \Services\Filter\Helper\FilterChainerFactory();
    $BLL = $app->getBLLManager()->get('infoProjectAccBLL');
    $headerParams = $app->request()->headers();
    if (!isset($headerParams['X-Public']))
        throw new Exception('rest api "pkFillProjectVehicleAccGridx_infoprojectacc" end point, X-Public variable not found');
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

    $resDataGrid = $BLL->FillProjectVehicleAccGridx(array(
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
   
    $resTotalRowCount = $BLL->FillProjectVehicleAccGridxRtl(array(
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
              
                "deal_sis_key" => html_entity_decode($menu["deal_sis_key"]),
                "vehicle_gt_model_id" =>  ($menu["vehicle_gt_model_id"]),
                "vehicle_gt_model_name" => html_entity_decode($menu["vehicle_gt_model_name"]),
                "tag_name" => html_entity_decode($menu["tag_name"]),
                  
                "quantity" =>  ($menu["quantity"]),
                "list_price" =>  ($menu["list_price"]),
               
                "acc_option_id" =>  ($menu["acc_option_id"]), 
                "option_name" => html_entity_decode($menu["option_name"]), 
                "acc_supplier_id" =>  ($menu["acc_supplier_id"]), 
                "supplier_name" => html_entity_decode($menu["supplier_name"]), 
                "accessories_matrix_id" =>  ($menu["accessories_matrix_id"]), 
                "list_price" =>  ($menu["list_price"]), 
                       
                "is_other" =>  ($menu["is_other"]), 
                "is_other_name" => html_entity_decode($menu["is_other_name"]), 
                
            
                "other_acc_name" => html_entity_decode($menu["other_acc_name"]), 
                "other_acc_brand" => html_entity_decode($menu["other_acc_brand"]), 
                "other_acc_supplier" => html_entity_decode($menu["other_acc_supplier"]), 
			 
                "deal_acc_newvalue" =>  ($menu["deal_acc_newvalue"]), 
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
$app->get("/pkUpdateMakeActiveOrPassive_infoprojectacc/", function () use ($app ) {
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
    $BLL = $app->getBLLManager()->get('infoProjectAccBLL');

    
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
$app->get("/pkInsertAct_infoprojectacc/", function () use ($app ) {
    $stripper = $app->getServiceManager()->get('filterChainerCustom');
    $stripChainerFactory = new \Services\Filter\Helper\FilterChainerFactory(); 
    $BLL = $app->getBLLManager()->get('infoProjectAccBLL');  
    $headerParams = $app->request()->headers();
    if(!isset($headerParams['X-Public'])) throw new Exception ('rest api "pkInsertAct_infoprojectacc" end point, X-Public variable not found');    
     $pk =  $headerParams['X-Public'];
      
    $ProjectId = NULL;
    if (isset($_GET['project_id'])) {
         $stripper->offsetSet('project_id',$stripChainerFactory->get(stripChainers::FILTER_ONLY_NUMBER_ALLOWED,
                                                $app,
                                                $_GET['project_id']));
    }  
    
    $Quantity = NULL;
    if (isset($_GET['quantity'])) {
         $stripper->offsetSet('quantity',$stripChainerFactory->get(stripChainers::FILTER_ONLY_NUMBER_ALLOWED,
                                                $app,
                                                $_GET['quantity']));
    } 
    $isOther = NULL;
    if (isset($_GET['is_other'])) {
         $stripper->offsetSet('is_other',$stripChainerFactory->get(stripChainers::FILTER_ONLY_NUMBER_ALLOWED,
                                                $app,
                                                $_GET['is_other']));
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
    $vehiclesGroupId = NULL;
    if (isset($_GET['vehicles_group_id'])) {
         $stripper->offsetSet('vehicles_group_id',$stripChainerFactory->get(stripChainers::FILTER_ONLY_NUMBER_ALLOWED,
                                                $app,
                                                $_GET['vehicles_group_id']));
    } 
    $vehiclesgtModelId = NULL;
    if (isset($_GET['vehicle_gt_model_id'])) {
         $stripper->offsetSet('vehicle_gt_model_id',$stripChainerFactory->get(stripChainers::FILTER_ONLY_NUMBER_ALLOWED,
                                                $app,
                                                $_GET['vehicle_gt_model_id']));
    } 
    $accOptionId = NULL;
    if (isset($_GET['acc_option_id'])) {
         $stripper->offsetSet('acc_option_id',$stripChainerFactory->get(stripChainers::FILTER_ONLY_NUMBER_ALLOWED,
                                                $app,
                                                $_GET['acc_option_id']));
    } 
    $accsupplierId = NULL;
    if (isset($_GET['acc_supplier_id'])) {
         $stripper->offsetSet('acc_supplier_id',$stripChainerFactory->get(stripChainers::FILTER_ONLY_NUMBER_ALLOWED,
                                                $app,
                                                $_GET['acc_supplier_id']));
    } 
    $accessoriesMatrixId = NULL;
    if (isset($_GET['accessories_matrix_id'])) {
         $stripper->offsetSet('accessories_matrix_id',$stripChainerFactory->get(stripChainers::FILTER_ONLY_NUMBER_ALLOWED,
                                                $app,
                                                $_GET['accessories_matrix_id']));
    }  
    $dealAccNewvalue = NULL;
    if (isset($_GET['deal_acc_newvalue'])) {
         $stripper->offsetSet('deal_acc_newvalue',$stripChainerFactory->get(stripChainers::FILTER_ONLY_NUMBER_ALLOWED,
                                                $app,
                                                $_GET['deal_acc_newvalue']));
    } 
    $isOnsiteOffsite= NULL;
    if (isset($_GET['is_onsite_offsite'])) {
         $stripper->offsetSet('is_onsite_offsite',$stripChainerFactory->get(stripChainers::FILTER_ONLY_NUMBER_ALLOWED,
                                                $app,
                                                $_GET['is_onsite_offsite']));
    }  
    $detailInformation= NULL;
    if (isset($_GET['detail_information'])) {
         $stripper->offsetSet('detail_information',$stripChainerFactory->get(stripChainers::FILTER_PARANOID_LEVEL2,
                                                $app,
                                                $_GET['detail_information']));
    } 
    $saDescription= NULL;
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
    $otherAccName= NULL;
    if (isset($_GET['other_acc_name'])) {
         $stripper->offsetSet('other_acc_name',$stripChainerFactory->get(stripChainers::FILTER_PARANOID_LEVEL2,
                                                $app,
                                                $_GET['other_acc_name']));
    }
    $otherAccbrand= NULL;
    if (isset($_GET['other_acc_brand'])) {
         $stripper->offsetSet('other_acc_brand',$stripChainerFactory->get(stripChainers::FILTER_PARANOID_LEVEL2,
                                                $app,
                                                $_GET['other_acc_brand']));
    } 
     $otherAccsupplier= NULL;
    if (isset($_GET['other_acc_supplier'])) {
         $stripper->offsetSet('other_acc_supplier',$stripChainerFactory->get(stripChainers::FILTER_PARANOID_LEVEL2,
                                                $app,
                                                $_GET['other_acc_supplier']));
    } 
             
     
    $stripper->strip();
    if($stripper->offsetExists('project_id')) $ProjectId = $stripper->offsetGet('project_id')->getFilterValue(); 
    if($stripper->offsetExists('vehicles_group_id')) $vehiclesGroupId = $stripper->offsetGet('vehicles_group_id')->getFilterValue();
    if($stripper->offsetExists('vehicle_gt_model_id')) $vehiclesgtModelId = $stripper->offsetGet('vehicle_gt_model_id')->getFilterValue();
    if($stripper->offsetExists('acc_option_id')) $accOptionId = $stripper->offsetGet('acc_option_id')->getFilterValue(); 
    if($stripper->offsetExists('acc_supplier_id')) $accsupplierId = $stripper->offsetGet('acc_supplier_id')->getFilterValue(); 
    if($stripper->offsetExists('accessories_matrix_id')) $accessoriesMatrixId = $stripper->offsetGet('accessories_matrix_id')->getFilterValue(); 
    if($stripper->offsetExists('deal_acc_newvalue')) $dealAccNewvalue = $stripper->offsetGet('deal_acc_newvalue')->getFilterValue(); 
    if($stripper->offsetExists('quantity')) $Quantity = $stripper->offsetGet('quantity')->getFilterValue();   
    if($stripper->offsetExists('is_onsite_offsite')) $isOnsiteOffsite = $stripper->offsetGet('is_onsite_offsite')->getFilterValue(); 
    if($stripper->offsetExists('detail_information')) $detailInformation = $stripper->offsetGet('detail_information')->getFilterValue(); 
    if($stripper->offsetExists('is_other')) $isOther = $stripper->offsetGet('is_other')->getFilterValue(); 
    if($stripper->offsetExists('isbo_confirm')) $isboConfirm = $stripper->offsetGet('isbo_confirm')->getFilterValue(); 
    if($stripper->offsetExists('ishos_confirm')) $ishosConfirm = $stripper->offsetGet('ishos_confirm')->getFilterValue(); 
    if($stripper->offsetExists('sa_description')) $saDescription = $stripper->offsetGet('sa_description')->getFilterValue(); 
    if($stripper->offsetExists('bo_description')) $boDescription = $stripper->offsetGet('bo_description')->getFilterValue(); 
    if($stripper->offsetExists('other_acc_name')) $otherAccName = $stripper->offsetGet('other_acc_name')->getFilterValue(); 
    if($stripper->offsetExists('other_acc_brand')) $otherAccbrand = $stripper->offsetGet('other_acc_brand')->getFilterValue(); 
    if($stripper->offsetExists('other_acc_supplier')) $otherAccsupplier = $stripper->offsetGet('other_acc_supplier')->getFilterValue(); 
    
      
    /*
        
 
      &project_id=1&vehicles_group_id=1&vehicle_gt_model_id=1&acc_option_id=1&acc_supplier_id=2&accessories_matrix_id=3&deal_acc_newvalue=33&quantity=12&is_onsite_offsite=0&ishos_confirm=0&sa_description=deneme sa&bo_description=bo deneme&other_acc_name=&other_acc_brand=&other_acc_supplier=
      
           
                            
                            
                            
     
     */ 
    
    
    $resDataInsert = $BLL->insertAct(array(
            'ProjectId' => $ProjectId,   
            'VehiclesGroupId' => $vehiclesGroupId,
            'VehicleGtModelId' => $vehiclesgtModelId,  
            'AccOptionId' => $accOptionId,   
        
            'AccSupplierId' => $accsupplierId,   
            'AccessoriesMatrixId' => $accessoriesMatrixId,   
            'DealAccNewvalue' => $dealAccNewvalue,   
            'Quantity' => $Quantity,   
            'IsOnsiteOffsite' => $isOnsiteOffsite,   
            'DetailInformation' => $detailInformation,   
            'IsOther' => $isOther,
            'IsBoConfirm' => $isboConfirm,   
            'IsHosConfirm' => $ishosConfirm,   
        
            'SaDescription' => $saDescription,   
            'BoDescription' => $boDescription,   
            'OtherAccName' => $otherAccName,   
            'OtherAccBrand' => $otherAccbrand,   
            'OtherAccSupplier' => $otherAccsupplier,   
         
            'pk' => $pk));
        
    $app->response()->header("Content-Type", "application/json"); 
    $app->response()->body(json_encode($resDataInsert));
    
}
);

/**
 *  * Okan CIRAN
 * @since 15-08-2018
 */ 
$app->get("/pkUpdateAct_infoprojectacc/", function () use ($app ) {
    $stripper = $app->getServiceManager()->get('filterChainerCustom');
    $stripChainerFactory = new \Services\Filter\Helper\FilterChainerFactory(); 
    $BLL = $app->getBLLManager()->get('infoProjectAccBLL');  
    $headerParams = $app->request()->headers();
    if(!isset($headerParams['X-Public'])) throw new Exception ('rest api "pkUpdateAct_infoprojectacc" end point, X-Public variable not found');    
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
    
    $Quantity = NULL;
    if (isset($_GET['quantity'])) {
         $stripper->offsetSet('quantity',$stripChainerFactory->get(stripChainers::FILTER_ONLY_NUMBER_ALLOWED,
                                                $app,
                                                $_GET['quantity']));
    } 
    $isOther = NULL;
    if (isset($_GET['is_other'])) {
         $stripper->offsetSet('is_other',$stripChainerFactory->get(stripChainers::FILTER_ONLY_NUMBER_ALLOWED,
                                                $app,
                                                $_GET['is_other']));
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
    $vehiclesGroupId = NULL;
    if (isset($_GET['vehicles_group_id'])) {
         $stripper->offsetSet('vehicles_group_id',$stripChainerFactory->get(stripChainers::FILTER_ONLY_NUMBER_ALLOWED,
                                                $app,
                                                $_GET['vehicles_group_id']));
    } 
    $vehiclesgtModelId = NULL;
    if (isset($_GET['vehicle_gt_model_id'])) {
         $stripper->offsetSet('vehicle_gt_model_id',$stripChainerFactory->get(stripChainers::FILTER_ONLY_NUMBER_ALLOWED,
                                                $app,
                                                $_GET['vehicle_gt_model_id']));
    } 
    $accOptionId = NULL;
    if (isset($_GET['acc_option_id'])) {
         $stripper->offsetSet('acc_option_id',$stripChainerFactory->get(stripChainers::FILTER_ONLY_NUMBER_ALLOWED,
                                                $app,
                                                $_GET['acc_option_id']));
    } 
    $accsupplierId = NULL;
    if (isset($_GET['acc_supplier_id'])) {
         $stripper->offsetSet('acc_supplier_id',$stripChainerFactory->get(stripChainers::FILTER_ONLY_NUMBER_ALLOWED,
                                                $app,
                                                $_GET['acc_supplier_id']));
    } 
    $accessoriesMatrixId = NULL;
    if (isset($_GET['accessories_matrix_id'])) {
         $stripper->offsetSet('accessories_matrix_id',$stripChainerFactory->get(stripChainers::FILTER_ONLY_NUMBER_ALLOWED,
                                                $app,
                                                $_GET['accessories_matrix_id']));
    }  
    $dealAccNewvalue = NULL;
    if (isset($_GET['deal_acc_newvalue'])) {
         $stripper->offsetSet('deal_acc_newvalue',$stripChainerFactory->get(stripChainers::FILTER_ONLY_NUMBER_ALLOWED,
                                                $app,
                                                $_GET['deal_acc_newvalue']));
    } 
    $isOnsiteOffsite= NULL;
    if (isset($_GET['is_onsite_offsite'])) {
         $stripper->offsetSet('is_onsite_offsite',$stripChainerFactory->get(stripChainers::FILTER_ONLY_NUMBER_ALLOWED,
                                                $app,
                                                $_GET['is_onsite_offsite']));
    }  
    $detailInformation= NULL;
    if (isset($_GET['detail_information'])) {
         $stripper->offsetSet('detail_information',$stripChainerFactory->get(stripChainers::FILTER_PARANOID_LEVEL2,
                                                $app,
                                                $_GET['detail_information']));
    } 
    $saDescription= NULL;
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
    $otherAccName= NULL;
    if (isset($_GET['other_acc_name'])) {
         $stripper->offsetSet('other_acc_name',$stripChainerFactory->get(stripChainers::FILTER_PARANOID_LEVEL2,
                                                $app,
                                                $_GET['other_acc_name']));
    }
    $otherAccbrand= NULL;
    if (isset($_GET['other_acc_brand'])) {
         $stripper->offsetSet('other_acc_brand',$stripChainerFactory->get(stripChainers::FILTER_PARANOID_LEVEL2,
                                                $app,
                                                $_GET['other_acc_brand']));
    } 
     $otherAccsupplier= NULL;
    if (isset($_GET['other_acc_supplier'])) {
         $stripper->offsetSet('other_acc_supplier',$stripChainerFactory->get(stripChainers::FILTER_PARANOID_LEVEL2,
                                                $app,
                                                $_GET['other_acc_supplier']));
    } 
             
     
    $stripper->strip();
    if($stripper->offsetExists('project_id')) $ProjectId = $stripper->offsetGet('project_id')->getFilterValue(); 
    if($stripper->offsetExists('vehicles_group_id')) $vehiclesGroupId = $stripper->offsetGet('vehicles_group_id')->getFilterValue();
    if($stripper->offsetExists('vehicle_gt_model_id')) $vehiclesgtModelId = $stripper->offsetGet('vehicle_gt_model_id')->getFilterValue();
    if($stripper->offsetExists('acc_option_id')) $accOptionId = $stripper->offsetGet('acc_option_id')->getFilterValue(); 
    if($stripper->offsetExists('acc_supplier_id')) $accsupplierId = $stripper->offsetGet('acc_supplier_id')->getFilterValue(); 
    if($stripper->offsetExists('accessories_matrix_id')) $accessoriesMatrixId = $stripper->offsetGet('accessories_matrix_id')->getFilterValue(); 
    if($stripper->offsetExists('deal_acc_newvalue')) $dealAccNewvalue = $stripper->offsetGet('deal_acc_newvalue')->getFilterValue(); 
    if($stripper->offsetExists('quantity')) $Quantity = $stripper->offsetGet('quantity')->getFilterValue();   
    if($stripper->offsetExists('is_onsite_offsite')) $isOnsiteOffsite = $stripper->offsetGet('is_onsite_offsite')->getFilterValue(); 
    if($stripper->offsetExists('detail_information')) $detailInformation = $stripper->offsetGet('detail_information')->getFilterValue(); 
    if($stripper->offsetExists('is_other')) $isOther = $stripper->offsetGet('is_other')->getFilterValue(); 
    if($stripper->offsetExists('isbo_confirm')) $isboConfirm = $stripper->offsetGet('isbo_confirm')->getFilterValue(); 
    if($stripper->offsetExists('ishos_confirm')) $ishosConfirm = $stripper->offsetGet('ishos_confirm')->getFilterValue(); 
    if($stripper->offsetExists('sa_description')) $saDescription = $stripper->offsetGet('sa_description')->getFilterValue(); 
    if($stripper->offsetExists('bo_description')) $boDescription = $stripper->offsetGet('bo_description')->getFilterValue(); 
    if($stripper->offsetExists('other_acc_name')) $otherAccName = $stripper->offsetGet('other_acc_name')->getFilterValue(); 
    if($stripper->offsetExists('other_acc_brand')) $otherAccbrand = $stripper->offsetGet('other_acc_brand')->getFilterValue(); 
    if($stripper->offsetExists('other_acc_supplier')) $otherAccsupplier = $stripper->offsetGet('other_acc_supplier')->getFilterValue(); 
     
    if($stripper->offsetExists('id')) $vId = $stripper->offsetGet('id')->getFilterValue();
     
          
    $resDataInsert = $BLL->updateAct(array(
            'Id' => $vId,    
            'ProjectId' => $ProjectId,   
            'VehiclesGroupId' => $vehiclesGroupId,
            'VehicleGtModelId' => $vehiclesgtModelId,  
            'AccOptionId' => $accOptionId,   
        
            'AccSupplierId' => $accsupplierId,   
            'AccessoriesMatrixId' => $accessoriesMatrixId,   
            'DealAccNewvalue' => $dealAccNewvalue,   
            'Quantity' => $Quantity,   
            'IsOnsiteOffsite' => $isOnsiteOffsite,   
            'DetailInformation' => $detailInformation,   
            'IsOther' => $isOther,
            'IsBoConfirm' => $isboConfirm,   
            'IsHosConfirm' => $ishosConfirm,   
        
            'SaDescription' => $saDescription,   
            'BoDescription' => $boDescription,   
            'OtherAccName' => $otherAccName,   
            'OtherAccBrand' => $otherAccbrand,   
            'OtherAccSupplier' => $otherAccsupplier,   
            'pk' => $pk));
        
    $app->response()->header("Content-Type", "application/json"); 
    $app->response()->body(json_encode($resDataInsert));
    
}
);
 
/**
 *  * Okan CIRAN
 * @since 15-08-2018
 */
$app->get("/pkDeletedAct_infoprojectacc/", function () use ($app ) {
    $stripper = $app->getServiceManager()->get('filterChainerCustom');
    $stripChainerFactory = new \Services\Filter\Helper\FilterChainerFactory();    
    $BLL = $app->getBLLManager()->get('infoProjectAccBLL');   
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