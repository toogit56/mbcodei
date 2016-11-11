<?php

class Mb_Input extends CI_Input {

    public function get_setup_view_data(
        $filter = null,
        $method = "post",
        $data = null,
        $no_xss_clean_keys = array()
    )
    {
        if(is_null($data)) {
            if(strcasecmp("post", $method) == 0) {
                $data = $this->post();
            }
            else if(strcasecmp("get", $method) == 0) {
                $data = $this->get();
            }
            else {
                throw Exception("error");
            }
        }
        
        if(!is_array($data)) {
            throw Exception("error");
        }
        
        if(is_null($filter)) {
            $filter = array();
        }
        
        if(!is_array($filter)) {
            throw Exception("error");
        }

        if(!is_array($no_xss_clean_keys)) {
            mbexception('$no_xss_clean_keys is invalid');
        }
        
        $setup_data = array();
        foreach($filter as $key) {
            if(array_key_exists($key, $data)) {
                if(in_array($key, $no_xss_clean_keys)) {
                    $setup_data[$key] = $data[$key];
                }
                else {
                    $setup_data[$key] = $this->security->xss_clean($data[$key]);
                }
            }
            else {
                $setup_data[$key] = null;
            }
        }

        return $setup_data;
    }

}