<?php

// test commit for branch slim2
require 'vendor/autoload.php';


use \Services\Filter\Helper\FilterFactoryNames as stripChainers;

/* $app = new \Slim\Slim(array(
  'mode' => 'development',
  'debug' => true,
  'log.enabled' => true,
  )); */

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
 *  * Okan CIRAN
 * @since 25-01-2016
 */
$app->get("/fillMainDefinitions_sysSpecificDefinitions/", function () use ($app ) {


    $BLL = $app->getBLLManager()->get('sysSpecificDefinitionsBLL');

    $languageCode = 'tr';
    if (isset($_GET['language_code'])) {
        $languageCode = strtolower(trim($_GET['language_code']));
    }
    $componentType = 'ddslick';
    if (isset($_GET['component_type'])) {
        $componentType = strtolower(trim($_GET['component_type']));
    }

    $resCombobox = $BLL->fillMainDefinitions(array('language_code' => $languageCode
    ));

    $menus = array();
    $menus[] = array("text" => "Lütfen Seçiniz", "value" => 0, "selected" => true, "imageSrc" => "", "description" => "Lütfen Seçiniz",); 
    if ($componentType == 'bootstrap') {
        foreach ($resCombobox as $menu) {
            $menus[] = array(
                "id" => $menu["id"],
                "text" => $menu["name"],
                "state" => $menu["state_type"], //   'closed',
                "checked" => false,
                "attributes" => array("notroot" => true, "active" => $menu["active"]),
            );
        }
    } else if ($componentType == 'ddslick') {        
        foreach ($resCombobox as $menu) {
            $menus[] = array(
                "text" => $menu["name"],
                "value" => intval($menu["id"]),
                "selected" => false,
                "description" => $menu["name_eng"],
               // "imageSrc" => ""
            );
        }
    }
 
    $app->response()->header("Content-Type", "application/json");
 

    $app->response()->body(json_encode($menus));
});
/**
 *  * Okan CIRAN
 * @since 25-01-2016
 */
$app->get("/fillFullDefinitions_sysSpecificDefinitions/", function () use ($app ) {

    $BLL = $app->getBLLManager()->get('sysSpecificDefinitionsBLL');

    $languageCode = 'tr';
    if (isset($_GET['language_code'])) {
        $languageCode = strtolower(trim($_GET['language_code']));
    }
   
    $resCombobox = $BLL->fillFullDefinitions(array('language_code' => $languageCode
    ));

    
    $menus = array();
    $menus[] = array("text" => "Lütfen Seçiniz", "value" => 0, "selected" => true, "imageSrc" => "", "description" => "Lütfen Seçiniz",); 
    if ($componentType == 'bootstrap') {
        foreach ($resCombobox as $menu) {
            $menus[] = array(
                "id" => $menu["id"],
                "text" => $menu["name"],
                "state" => $menu["state_type"], //   'closed',
                "checked" => false,
                "attributes" => array("notroot" => true, "active" => $menu["active"]),
            );
        }
    } else if ($componentType == 'ddslick') {        
        foreach ($resCombobox as $menu) {
            $menus[] = array(
                "text" => $menu["name"],
                "value" => intval($menu["id"]),
                "selected" => false,
                "description" => $menu["name_eng"],
              //  "imageSrc" => ""
            );
        }
    }

    $app->response()->header("Content-Type", "application/json");
  
    $app->response()->body(json_encode($menus));
});


/**
 *  * Okan CIRAN
 * @since 25-01-2016
 */
$app->get("/fillCommunicationsTypes_sysSpecificDefinitions/", function () use ($app ) {

    $BLL = $app->getBLLManager()->get('sysSpecificDefinitionsBLL');

    $languageCode = 'tr';
    if (isset($_GET['language_code'])) {
        $languageCode = strtolower(trim($_GET['language_code']));
    }
    $componentType = 'ddslick';
    if (isset($_GET['component_type'])) {
        $componentType = strtolower(trim($_GET['component_type']));
    }
 
    $resCombobox = $BLL->fillCommunicationsTypes(array('language_code' => $languageCode
    ));
    $menus = array();
    $menus[] = array("text" => "Lütfen Seçiniz", "value" => 0, "selected" => true, "imageSrc" => "", "description" => "Lütfen Seçiniz",); 
 
    if ($componentType == 'bootstrap') {
        foreach ($resCombobox as $menu) {
            $menus[] = array(
                "id" => $menu["id"],
                "text" => $menu["name"],
                "state" => $menu["state_type"], //   'closed',
                "checked" => false,
                "attributes" => array("notroot" => true, "active" => $menu["active"]),
            );
        }
    } else if ($componentType == 'ddslick') {
        foreach ($resCombobox as $menu) {
            $menus[] = array(
                "text" => $menu["name"],
                "value" => intval($menu["id"]),
                "selected" => false,
                "description" => $menu["name_eng"],
            //    "imageSrc" => ""
            );
        }
    }

    $app->response()->header("Content-Type", "application/json");


    $app->response()->body(json_encode($menus));

    //$app->response()->body(json_encode($flows));
});

/**
 *  * Okan CIRAN
 * @since 25-01-2016
 */
$app->get("/fillBuildingType_sysSpecificDefinitions/", function () use ($app ) {

    $BLL = $app->getBLLManager()->get('sysSpecificDefinitionsBLL');

    $languageCode = 'tr';
    if (isset($_GET['language_code'])) {
        $languageCode = strtolower(trim($_GET['language_code']));
    }

    $componentType = 'ddslick';
    if (isset($_GET['component_type'])) {
        $componentType = strtolower(trim($_GET['component_type']));
    }

    $resCombobox = $BLL->fillBuildingType(array('language_code' => $languageCode
    ));

        $menus = array();
        $menus[] = array("text" => "Lütfen Seçiniz", "value" => 0, "selected" => true, "imageSrc" => "", "description" => "Lütfen Seçiniz",); 
    if ($componentType == 'bootstrap') {
        foreach ($resCombobox as $menu) {
            $menus[] = array(
                "id" => $menu["id"],
                "text" => $menu["name"],
                "state" => $menu["state_type"], //   'closed',
                "checked" => false,
                "attributes" => array("notroot" => true, "active" => $menu["active"]),
            );
        }
    } else if ($componentType == 'ddslick') {     
        foreach ($resCombobox as $menu) {
            $menus[] = array(
                "text" => $menu["name"],
                "value" => intval($menu["id"]),
                "selected" => false,
                "description" => $menu["name_eng"],
                //"imageSrc" => ""
            );
        }
    }

    $app->response()->header("Content-Type", "application/json");
 

    $app->response()->body(json_encode($menus));
});

/**
 *  * Okan CIRAN
 * @since 25-01-2016
 */
$app->get("/fillOwnershipType_sysSpecificDefinitions/", function () use ($app ) {

    $BLL = $app->getBLLManager()->get('sysSpecificDefinitionsBLL');

    $languageCode = 'tr';
    if (isset($_GET['language_code'])) {
        $languageCode = strtolower(trim($_GET['language_code']));
    }
    $componentType = 'ddslick';
    if (isset($_GET['component_type'])) {
        $componentType = strtolower(trim($_GET['component_type']));
    }

    $resCombobox = $BLL->fillOwnershipType(array('language_code' => $languageCode
    ));

        $menus = array();
        $menus[] = array("text" => "Lütfen Seçiniz", "value" => 0, "selected" => true, "imageSrc" => "", "description" => "Lütfen Seçiniz",); 
    if ($componentType == 'bootstrap') {
        foreach ($resCombobox as $menu) {
            $menus[] = array(
                "id" => $menu["id"],
                "text" => $menu["name"],
                "state" => $menu["state_type"], //   'closed',
                "checked" => false,
                "attributes" => array("notroot" => true, "active" => $menu["active"]),
            );
        }
    } else if ($componentType == 'ddslick') {       
        foreach ($resCombobox as $menu) {
            $menus[] = array(
                "text" => $menu["name"],
                "value" => intval($menu["id"]),
                "selected" => false,
                "description" => $menu["name_eng"],
               // "imageSrc" => ""
            );
        }
    }
    $app->response()->header("Content-Type", "application/json");
 
    $app->response()->body(json_encode($menus));
});


/**
 *  * Okan CIRAN
 * @since 25-01-2016
 */
$app->get("/fillPersonnelTypes_sysSpecificDefinitions/", function () use ($app ) {

    $BLL = $app->getBLLManager()->get('sysSpecificDefinitionsBLL');

    $languageCode = 'tr';
    if (isset($_GET['language_code'])) {
        $languageCode = strtolower(trim($_GET['language_code']));
    }
    $componentType = 'ddslick';
    if (isset($_GET['component_type'])) {
        $componentType = strtolower(trim($_GET['component_type']));
    }

    $resCombobox = $BLL->fillPersonnelTypes(array('language_code' => $languageCode
    ));

        $menus = array();
        $menus[] = array("text" => "Lütfen Seçiniz", "value" => 0, "selected" => true, "imageSrc" => "", "description" => "Lütfen Seçiniz",); 
    if ($componentType == 'bootstrap') {
        foreach ($resCombobox as $menu) {
            $menus[] = array(
                "id" => $menu["id"],
                "text" => $menu["name"],
                "state" => $menu["state_type"], //   'closed',
                "checked" => false,
                "attributes" => array("notroot" => true, "active" => $menu["active"]),
            );
        }
    } else if ($componentType == 'ddslick') {      
        foreach ($resCombobox as $menu) {
            $menus[] = array(
                "text" => $menu["name"],
                "value" =>  intval($menu["id"]),
                "selected" => false,
                "description" => $menu["name_eng"],
                //"imageSrc" => ""
            );
        }
    }

    $app->response()->header("Content-Type", "application/json");
 
    $app->response()->body(json_encode($menus));
});


/**
 *  * Okan CIRAN
 * @since 25-01-2016
 */
$app->get("/fillAddressTypes_sysSpecificDefinitions/", function () use ($app ) {

    $BLL = $app->getBLLManager()->get('sysSpecificDefinitionsBLL');

    $languageCode = 'tr';
    if (isset($_GET['language_code'])) {
        $languageCode = strtolower(trim($_GET['language_code']));
    }
    $componentType = 'ddslick';
    if (isset($_GET['component_type'])) {
        $componentType = strtolower(trim($_GET['component_type']));
    }

    $resCombobox = $BLL->fillAddressTypes(array('language_code' => $languageCode
    ));

        $menus = array();
        $menus[] = array("text" => "Lütfen Seçiniz", "value" => 0, "selected" => true, "imageSrc" => "", "description" => "Lütfen Seçiniz",); 
    if ($componentType == 'bootstrap') {
        foreach ($resCombobox as $menu) {
            $menus[] = array(
                "id" => $menu["id"],
                "text" => $menu["name"],
                "state" => $menu["state_type"], //   'closed',
                "checked" => false,
                "attributes" => array("notroot" => true, "active" => $menu["active"]),
            );
        }
    } else if ($componentType == 'ddslick') {       
        foreach ($resCombobox as $menu) {
            $menus[] = array(
                "text" => $menu["name"],
                "value" =>  intval($menu["id"]),
                "selected" => false,
                "description" => $menu["name_eng"],
               // "imageSrc" => ""
            );
        }
    }

    $app->response()->header("Content-Type", "application/json");

    $app->response()->body(json_encode($menus));
});

/**
 *  * Okan CIRAN
 * @since 15-07-2016
 */
$app->get("/fillSexTypes_sysSpecificDefinitions/", function () use ($app ) {
    $BLL = $app->getBLLManager()->get('sysSpecificDefinitionsBLL');
    $languageCode = 'tr';
    if (isset($_GET['language_code'])) {
        $languageCode = strtolower(trim($_GET['language_code']));
    }
    $componentType = 'ddslick';
    if (isset($_GET['component_type'])) {
        $componentType = strtolower(trim($_GET['component_type']));
    }

    $resCombobox = $BLL->fillSexTypes(array('language_code' => $languageCode
    ));

        $menus = array();
        $menus[] = array("text" => "Lütfen Seçiniz", "value" => 0, "selected" => true, "imageSrc" => "", "description" => "Lütfen Seçiniz",); 
    if ($componentType == 'bootstrap') {
        foreach ($resCombobox as $menu) {
            $menus[] = array(
                "id" => $menu["id"],
                "text" => $menu["name"],
                "state" => $menu["state_type"], //   'closed',
                "checked" => false,
                "attributes" => array("notroot" => true, "active" => $menu["active"]),
            );
        }
    } else if ($componentType == 'ddslick') {       
        foreach ($resCombobox as $menu) {
            $menus[] = array(
                "text" => $menu["name"],
                "value" =>  intval($menu["id"]),
                "selected" => false,
                "description" => $menu["name_eng"],
               // "imageSrc" => ""
            );
        }
    }

    $app->response()->header("Content-Type", "application/json");

    $app->response()->body(json_encode($menus));
});
 
/**
 *  * Okan CIRAN
 * @since 15-07-2016
 */
$app->get("/fillVarYokGecTypes_sysSpecificDefinitions/", function () use ($app ) {
    $stripper = $app->getServiceManager()->get('filterChainerCustom');
    $stripChainerFactory = new \Services\Filter\Helper\FilterChainerFactory();
    $BLL = $app->getBLLManager()->get('sysSpecificDefinitionsBLL');
   
    $componentType = 'ddslick';
    if (isset($_GET['component_type'])) {
        $componentType = strtolower(trim($_GET['component_type']));
    }
    $vLanguageID = NULL;
    if (isset($_GET['lid'])) {
        $stripper->offsetSet('lid', $stripChainerFactory->get(stripChainers::FILTER_ONLY_NUMBER_ALLOWED, 
                                                                $app, 
                                                                $_GET['lid']));
    }
    $vSID = NULL;
    if (isset($_GET['sid'])) {
        $stripper->offsetSet('sid', $stripChainerFactory->get(stripChainers::FILTER_ONLY_NUMBER_ALLOWED, 
                                                                $app, 
                                                                $_GET['sid']));
    } 
    $stripper->strip();
    
    if ($stripper->offsetExists('sid')) 
        {$vSID = $stripper->offsetGet('sid')->getFilterValue(); }
    if ($stripper->offsetExists('lid')) 
        {$vLanguageID = $stripper->offsetGet('lid')->getFilterValue(); }   

    $resCombobox = $BLL->fillVarYokGecTypes(array( 
                    'url' => $_GET['url'],  
                    'SID' => $vSID,
                    'LanguageID' => $vLanguageID, 
        )); 

        $menus = array();
  //      $menus[] = array("text" => "Lütfen Seçiniz", "value" => 0, "selected" => true, "imageSrc" => "", "description" => "Lütfen Seçiniz",); //
    if ($componentType == 'bootstrap') {
        foreach ($resCombobox as $menu) {
            $menus[] = array(
                "id" => $menu["id"],
                "text" => $menu["name"],
                "state" => $menu["state_type"], //   'closed',
                "checked" => false,
                "attributes" => array("notroot" => true, "active" => $menu["active"]),
            );
        }
    } else if ($componentType == 'ddslick') {       
        foreach ($resCombobox as $menu) {
            $menus[] = array(
                "text" => $menu["name"],
                "value" =>  intval($menu["id"]),
                "selected" => false,
                "description" => $menu["name_eng"],
               // "imageSrc" => ""
               
            );
        }
    }

    $app->response()->header("Content-Type", "application/json");

    $app->response()->body(json_encode($menus));
});


/**
 *  * Okan CIRAN
 * @since 15-07-2016
 */
$app->get("/fillYesNoTypes_sysSpecificDefinitions/", function () use ($app ) {
    $stripper = $app->getServiceManager()->get('filterChainerCustom');
    $stripChainerFactory = new \Services\Filter\Helper\FilterChainerFactory();
    $BLL = $app->getBLLManager()->get('sysSpecificDefinitionsBLL');
   
    $componentType = 'ddslick';
    if (isset($_GET['component_type'])) {
        $componentType = strtolower(trim($_GET['component_type']));
    }
    $vLanguageID = NULL;
    if (isset($_GET['lid'])) {
        $stripper->offsetSet('lid', $stripChainerFactory->get(stripChainers::FILTER_ONLY_NUMBER_ALLOWED, 
                                                                $app, 
                                                                $_GET['lid']));
    }
    $vSID = NULL;
    if (isset($_GET['sid'])) {
        $stripper->offsetSet('sid', $stripChainerFactory->get(stripChainers::FILTER_ONLY_NUMBER_ALLOWED, 
                                                                $app, 
                                                                $_GET['sid']));
    } 
    $stripper->strip();
    
    if ($stripper->offsetExists('sid')) 
        {$vSID = $stripper->offsetGet('sid')->getFilterValue(); }
    if ($stripper->offsetExists('lid')) 
        {$vLanguageID = $stripper->offsetGet('lid')->getFilterValue(); }   

    $resCombobox = $BLL->fillYesNoTypes(array( 
                    'url' => $_GET['url'],  
                    'SID' => $vSID,
                    'LanguageID' => $vLanguageID, 
        )); 

        $menus = array();
  //      $menus[] = array("text" => "Lütfen Seçiniz", "value" => 0, "selected" => true, "imageSrc" => "", "description" => "Lütfen Seçiniz",); //
    if ($componentType == 'bootstrap') {
        foreach ($resCombobox as $menu) {
            $menus[] = array(
                "id" => $menu["id"],
                "text" => $menu["name"],
                "state" => $menu["state_type"], //   'closed',
                "checked" => false,
                 
            );
        }
    } else if ($componentType == 'ddslick') {       
        foreach ($resCombobox as $menu) {
            $menus[] = array(
                "text" => $menu["name"],
                "value" =>  intval($menu["id"]),
                "selected" => false,
                "description" => $menu["name_eng"],
               // "imageSrc" => ""
               
            );
        }
    }

    $app->response()->header("Content-Type", "application/json");

    $app->response()->body(json_encode($menus));
});


/**
 *  * Okan CIRAN
 * @since 15-07-2016
 */
$app->get("/getserverkontrol_sysSpecificDefinitions/", function () use ($app ) {
    
    $languageCode = 'tr';
    if (isset($_GET['language_code'])) {
        $languageCode = strtolower(trim($_GET['language_code']));
    } 
    //http://localhost:8880/mansis_services/mansissa_Slim_Proxy_v1/SlimProxyBoot.php?country_id=91&city_id=66&boroughs_id=738&language_code=tr&url=getserverkontrol_sysSpecificDefinitions
    $menus = array();  
    $menus[] = array(
        "id" => '647',
        "value" =>  '200', 
        "description" => 'Ok', 
    );
      
    $app->response()->header("Content-Type", "application/json"); 
    $app->response()->body(json_encode($menus));
});

/**
 *  * Okan CIRAN
 * @since 15-07-2016
 */
$app->get("/fillMaybeYesNoTypes_sysSpecificDefinitions/", function () use ($app ) {
    $stripper = $app->getServiceManager()->get('filterChainerCustom');
    $stripChainerFactory = new \Services\Filter\Helper\FilterChainerFactory();
    $BLL = $app->getBLLManager()->get('sysSpecificDefinitionsBLL');
   
    $componentType = 'ddslick';
    if (isset($_GET['component_type'])) {
        $componentType = strtolower(trim($_GET['component_type']));
    }
    $vLanguageID = NULL;
    if (isset($_GET['lid'])) {
        $stripper->offsetSet('lid', $stripChainerFactory->get(stripChainers::FILTER_ONLY_NUMBER_ALLOWED, 
                                                                $app, 
                                                                $_GET['lid']));
    }
    $vSID = NULL;
    if (isset($_GET['sid'])) {
        $stripper->offsetSet('sid', $stripChainerFactory->get(stripChainers::FILTER_ONLY_NUMBER_ALLOWED, 
                                                                $app, 
                                                                $_GET['sid']));
    } 
    $stripper->strip();
    
    if ($stripper->offsetExists('sid')) 
        {$vSID = $stripper->offsetGet('sid')->getFilterValue(); }
    if ($stripper->offsetExists('lid')) 
        {$vLanguageID = $stripper->offsetGet('lid')->getFilterValue(); }   

    $resCombobox = $BLL->fillMaybeYesNoTypes(array( 
                    'url' => $_GET['url'],  
                    'SID' => $vSID,
                    'LanguageID' => $vLanguageID, 
        )); 

        $menus = array();
  //      $menus[] = array("text" => "Lütfen Seçiniz", "value" => 0, "selected" => true, "imageSrc" => "", "description" => "Lütfen Seçiniz",); //
    if ($componentType == 'bootstrap') {
        foreach ($resCombobox as $menu) {
            $menus[] = array(
                "id" => $menu["id"],
                "text" => $menu["name"],
                "state" => $menu["state_type"], //   'closed',
                "checked" => false,
                 
            );
        }
    } else if ($componentType == 'ddslick') {       
        foreach ($resCombobox as $menu) {
            $menus[] = array(
                "text" => $menu["name"],
                "value" =>  intval($menu["id"]),
                "selected" => false,
                "description" => $menu["name_eng"],
               // "imageSrc" => ""
               
            );
        }
    }

    $app->response()->header("Content-Type", "application/json");

    $app->response()->body(json_encode($menus));
});

/**
 *  * Okan CIRAN
 * @since 15-07-2016
 */
$app->get("/testfileupload_sysSpecificDefinitions/", function () use ($app ) {
    $stripper = $app->getServiceManager()->get('filterChainerCustom');
    $stripChainerFactory = new \Services\Filter\Helper\FilterChainerFactory();
    $BLL = $app->getBLLManager()->get('sysSpecificDefinitionsBLL');
   
    
    //////////////////////////////////////////////
    
      $app->post('/uploadTest', function() {
    
        if(!empty($_FILES['file']['name'])){
         $uploadedFile = '';
         if(!empty($_FILES["file"]["type"])){
             $fileName = time().'_'.$_FILES['file']['name'];
             $valid_extensions = array("jpeg", "jpg", "png","pdf");
             $temporary = explode(".", $_FILES["file"]["name"]);
             $file_extension = end($temporary);
             if((($_FILES["file"]["type"] == "application/pdf") ||  
                 ($_FILES["file"]["type"] == "image/png") || 
                 ($_FILES["file"]["type"] == "image/jpg") || 
                 ($_FILES["file"]["type"] == "image/jpeg")) 
                     && in_array($file_extension, $valid_extensions)){
                 $sourcePath = $_FILES['file']['tmp_name'];
                 $targetPath = "C:/app/uploads/".$fileName;
                 if(move_uploaded_file($sourcePath,$targetPath)){
                     $uploadedFile = $fileName;
                 }
             }
         }

            $name = $_POST['name'];
            $email = $_POST['email'];

            //include database configuration file
            //include_once 'dbConfig.php';

            //insert form data in the database
            //$insert = $db->query("INSERT form_data (name,email,file_name) VALUES ('".$name."','".$email."','".$uploadedFile."')");

            //echo $insert?'ok':'err';
            echo $uploadedFile?'ok':'err';
            } 

        });   
    
     
    
    ///////////////////////////////////////////
    
    $componentType = 'ddslick';
    if (isset($_GET['component_type'])) {
        $componentType = strtolower(trim($_GET['component_type']));
    }
    $vLanguageID = NULL;
    if (isset($_GET['lid'])) {
        $stripper->offsetSet('lid', $stripChainerFactory->get(stripChainers::FILTER_ONLY_NUMBER_ALLOWED, 
                                                                $app, 
                                                                $_GET['lid']));
    }
    $vSID = NULL;
    if (isset($_GET['sid'])) {
        $stripper->offsetSet('sid', $stripChainerFactory->get(stripChainers::FILTER_ONLY_NUMBER_ALLOWED, 
                                                                $app, 
                                                                $_GET['sid']));
    } 
    $stripper->strip();
    
    if ($stripper->offsetExists('sid')) 
        {$vSID = $stripper->offsetGet('sid')->getFilterValue(); }
    if ($stripper->offsetExists('lid')) 
        {$vLanguageID = $stripper->offsetGet('lid')->getFilterValue(); }   

   /* $resCombobox = $BLL->fillMaybeYesNoTypes(array( 
                    'url' => $_GET['url'],  
                    'SID' => $vSID,
                    'LanguageID' => $vLanguageID, 
        )); 
*/
        $menus = array();
  //      $menus[] = array("text" => "Lütfen Seçiniz", "value" => 0, "selected" => true, "imageSrc" => "", "description" => "Lütfen Seçiniz",); //
 /*   if ($componentType == 'bootstrap') {
        foreach ($resCombobox as $menu) {
            $menus[] = array(
                "id" => $menu["id"],
                "text" => $menu["name"],
                "state" => $menu["state_type"], //   'closed',
                "checked" => false,
                 
            );
        }
    } else if ($componentType == 'ddslick') {       
        foreach ($resCombobox as $menu) {
            $menus[] = array(
                "text" => $menu["name"],
                "value" =>  intval($menu["id"]),
                "selected" => false,
                "description" => $menu["name_eng"],
               // "imageSrc" => ""
               
            );
        }
    }
*/
    $app->response()->header("Content-Type", "application/json"); 
    $app->response()->body(json_encode($menus));
});



$app->run();
