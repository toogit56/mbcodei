<?php

class Mb_Controller extends CI_Controller {

    public function check_token($form_name = null) {
		$skey = "tstoken";
		if($form_name != null) {
			$skey = $skey . "_$form_name";
		}

        $recieve_token = $this->input->post($skey);
        if($recieve_token == null) {
             return false;  
        }

        $session_token = $this->session->$skey;

        $this->session->$skey = null;

        if(!strcmp($session_token, $recieve_token) == 0) {
            return false;
        }

        return true;
    }
}