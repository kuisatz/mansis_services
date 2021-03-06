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
class PublicNotFoundForwarder extends \Utill\Forwarder\AbstractForwarder {
    
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
        //$ch = curl_init('http://slimRedirect.uretimosb.com/index.php/hashNotMatch');
        $ch = curl_init('http://localhost/Slim_Redirect_codebase_v2/index.php/publicNotFoundSlim?test='.  serialize($this->parameters).'&test2='.$_SERVER['REMOTE_ADDR'].'');
        //curl_setopt($ch,CURLOPT_HTTPHEADER,$headers);
        //curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
        //curl_setopt($ch,CURLOPT_POSTFIELDS,$this->parameters);

        $result = curl_exec($ch);
        curl_close($ch);
        exit();
        
    }
}
