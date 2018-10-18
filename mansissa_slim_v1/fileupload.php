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
/**
$app->add(new \Slim\Middleware\MiddlewareInsertUpdateDeleteLog());
$app->add(new \Slim\Middleware\MiddlewareHMAC());
$app->add(new \Slim\Middleware\MiddlewareSecurity());
$app->add(new \Slim\Middleware\MiddlewareMQManager());
$app->add(new \Slim\Middleware\MiddlewareBLLManager());
$app->add(new \Slim\Middleware\MiddlewareDalManager());
$app->add(new \Slim\Middleware\MiddlewareServiceManager());
$app->add(new \Slim\Middleware\MiddlewareMQManager());
 * *
 */

 
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

 

$app->run();
