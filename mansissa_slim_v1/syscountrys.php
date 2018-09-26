<?php
// test commit for branch slim2
require 'vendor/autoload.php';

 
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
$app->add(new \Slim\Middleware\MiddlewareMQManager());



  

/**
 *  *  
  *  Okan CIRAN
 * @since 11-09-2014
 */
$app->get("/fillComboBox_syscountrys/", function () use ($app ) {
    
    $BLL = $app->getBLLManager()->get('sysCountrysBLL'); 
   
    $componentType = 'ddslick';
     if (isset($_GET['component_type'])) {
      $componentType =$_GET['component_type'] ;
    }
    $vLanguageCode = 'tr';
    if (isset($_GET['language_code'])) {
        $vLanguageCode = $_GET['language_code'];
    }
 
    $resCombobox = $BLL->fillComboBox(array('language_code' =>$vLanguageCode));
    
    if ($componentType == 'bootstrap') {
        $menus = array();
        foreach ($resCombobox as $menu) {
            $menus[] = array(
                "id" => $menu["id"],
                "name" => $menu["name"],
                "name_eng" =>$menu["name_eng"],
            );
        }
    } else if ($componentType == 'ddslick') {
        $menus = array();
        $menus[] = array("text" => "Lütfen Seçiniz", "value" => 0, "selected" => true, "imageSrc" => "", "description" => "Lütfen Seçiniz",); 
        foreach ($resCombobox as $menu) {
            $menus[] = array(
                "text" => $menu["name"],
                "value" => intval($menu["id"]),
                "selected" => false,
                "description" => $menu["name_eng"],
                "attributes" => array("citylist" => $menu["citylist"], "active" => $menu["active"],
                 
                ),
              //  "imageSrc" => ""
            );
        }
    }
 

    $app->response()->header("Content-Type", "application/json");
   /*
   if($tableType == 'bootstrap'){
        $app->response()->body(json_encode($flows));
    }else if($tableType == 'easyui'){
        $app->response()->body(json_encode($resultArray));
    }
    */
    
    
  //$app->response()->body(json_encode($menus));

    
    $app->response()->header("Content-Type", "application/json");
    
   if($componentType == 'ddslick'){
        $app->response()->body(json_encode($menus));
    }else if($componentType == 'bootstrap'){
        $app->response()->body(json_encode($resCombobox));
    }
   
  
});


 /**
 *  * Okan CIRAN
 * @since 11.08.2018
 */
$app->get("/pkCountryDdList_syscountrys/", function () use ($app ) {
    $stripper = $app->getServiceManager()->get('filterChainerCustom');
    $stripChainerFactory = new \Services\Filter\Helper\FilterChainerFactory(); 
    $BLL = $app->getBLLManager()->get('sysCountrysBLL');
    
    $componentType = 'ddslick';
    if (isset($_GET['component_type'])) {
        $componentType = strtolower(trim($_GET['component_type']));
    }
    $headerParams = $app->request()->headers();
    if(!isset($headerParams['X-Public'])) throw new Exception ('rest api "pkCountryDdList_syscountrys" end point, X-Public variable not found');
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
        
    $resCombobox = $BLL->CountryDdList(array(                                   
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


$app->run();