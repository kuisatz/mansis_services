<?php
/**
 *  Framework 
 *
 * @link       
 * @copyright Copyright (c) 2017
 * @license   
 */
namespace Utill\Forwarder;

/**
 * hash control and redirection if necessary
 * @author Mustafa Zeynel Dağlı
 */
class PrivateNotFoundForwarder extends \Utill\Forwarder\AbstractForwarder {
    
    /**
     * constructor
     */
    public function __construct() {

    }
    
    /**
     * redirect
     */
    public  function redirect() {
        //ob_end_flush();
        /*ob_end_clean();
        $newURL = 'http://localhost/slim_redirect_test/index.php/hashNotMatch';
        header("Location: {$newURL}");*/
        
        ob_end_clean();
        //$ch = curl_init('http://slimRedirect.sanalfabrika.com/index.php/hashNotMatch');
        //$ch = curl_init('http://localhost/slim_Redirect_SanalFabrika/index.php/privateNotFound');
		$ch = curl_init('http://localhost/mansis_services/mansissa_Slim_Redirect_v1/index.php/privateNotFound'); 
        //curl_setopt($ch,CURLOPT_HTTPHEADER,$headers);
        //curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
        //curl_setopt($ch,CURLOPT_POSTFIELDS,$content);

        $result = curl_exec($ch);
        curl_close($ch);
        exit();
        
    }
}
