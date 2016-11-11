<?php

class Mb_Input extends CI_Input {

    public function get_setup_view_data($filter = null, $method = "post", $data = null) {
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
        
        $setup_data = array();
        foreach($filter as $key) {
            if(array_key_exists($key, $data)) {
                $setup_data[$key] = $data[$key];
            }
            else {
                $setup_data[$key] = null;
            }
        }

        return $setup_data;
    }

}