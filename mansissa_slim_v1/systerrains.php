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
$app->get("/pkTerrainsMainDdList_systerrains/", function () use ($app ) {
    $stripper = $app->getServiceManager()->get('filterChainerCustom');
    $stripChainerFactory = new \Services\Filter\Helper\FilterChainerFactory(); 
    $BLL = $app->getBLLManager()->get('sysTerrainsBLL');
    
    $componentType = 'ddslick';
    if (isset($_GET['component_type'])) {
        $componentType = strtolower(trim($_GET['component_type']));
    }
    $headerParams = $app->request()->headers();
    if(!isset($headerParams['X-Public'])) throw new Exception ('rest api "pkTerrainsMainDdList_systerrains" end point, X-Public variable not found');
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
        
    $resCombobox = $BLL->terrainsMainDdList(array(                                   
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
$app->get("/pkTerrainsParentDdList_systerrains/", function () use ($app ) {
    $stripper = $app->getServiceManager()->get('filterChainerCustom');
    $stripChainerFactory = new \Services\Filter\Helper\FilterChainerFactory(); 
    $BLL = $app->getBLLManager()->get('sysTerrainsBLL');
    
    $componentType = 'ddslick';
    if (isset($_GET['component_type'])) {
        $componentType = strtolower(trim($_GET['component_type']));
    }
    $headerParams = $app->request()->headers();
    if(!isset($headerParams['X-Public'])) throw new Exception ('rest api "pkTerrainsParentDdList_systerrains" end point, X-Public variable not found');
    //$pk = $headerParams['X-Public'];
    
    $vLanguageCode = 'en';
    if (isset($_GET['language_code'])) {
         $stripper->offsetSet('language_code',$stripChainerFactory->get(stripChainers::FILTER_ONLY_LANGUAGE_CODE,
                                                $app,
                                                $_GET['language_code']));
    }
    $ParentId = -1;
    if (isset($_GET['parent_id'])) {
         $stripper->offsetSet('parent_id',$stripChainerFactory->get(stripChainers::FILTER_ONLY_NUMBER_ALLOWED,
                                                $app,
                                                $_GET['parent_id']));
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
    if($stripper->offsetExists('parent_id')) $ParentId = $stripper->offsetGet('parent_id')->getFilterValue();
        
    $resCombobox = $BLL->terrainsPDdList(array(                                   
                                    'language_code' => $vLanguageCode,
                                    'Parent' => $ParentId,
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
$app->get("/pkTerrainsBuybackDdList_systerrains/", function () use ($app ) {
    $stripper = $app->getServiceManager()->get('filterChainerCustom');
    $stripChainerFactory = new \Services\Filter\Helper\FilterChainerFactory(); 
    $BLL = $app->getBLLManager()->get('sysTerrainsBLL');
    
    $componentType = 'ddslick';
    if (isset($_GET['component_type'])) {
        $componentType = strtolower(trim($_GET['component_type']));
    }
    $headerParams = $app->request()->headers();
    if(!isset($headerParams['X-Public'])) throw new Exception ('rest api "pkTerrainsBuybackDdList_systerrains" end point, X-Public variable not found');
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
        
    $resCombobox = $BLL->terrainsBuybackDdList(array(                                   
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
$app->get("/pkTerrainsTradebackDdList_systerrains/", function () use ($app ) {
    $stripper = $app->getServiceManager()->get('filterChainerCustom');
    $stripChainerFactory = new \Services\Filter\Helper\FilterChainerFactory(); 
    $BLL = $app->getBLLManager()->get('sysTerrainsBLL');
    
    $componentType = 'ddslick';
    if (isset($_GET['component_type'])) {
        $componentType = strtolower(trim($_GET['component_type']));
    }
    $headerParams = $app->request()->headers();
    if(!isset($headerParams['X-Public'])) throw new Exception ('rest api "pkTerrainsTradebackDdList_systerrains" end point, X-Public variable not found');
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
        
    $resCombobox = $BLL->terrainsTradebackDdList(array(                                   
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