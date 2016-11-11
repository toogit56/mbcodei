<?php

function mbexception($log_msg, $show_msg = '', $dumpvar = array()) {
    $d = debug_backtrace();
    $msg = 'file:'.$d[0]['file'].' line:'.$d[0]['line']. ' msg:'.$log_msg;

    log_message('error', $msg);
    
    if(is_array($dumpvar)) {
        foreach($dumpvar as $key => $dvar) {
            log_message('debug', $key . ' - :' . var_export($dvar, true));
        }
    }
    else {
        log_message('debug', 'dumpvar - :' . var_export($dumpvar, true));
    }

    show_error($show_msg);
}

function mblog_debug($log_msg) {
   $d = debug_backtrace();
   mblog_message('debug', $log_msg, $d);
}

function mblog_info($log_msg) {
   $d = debug_backtrace();
   mblog_message('info', $log_msg, $d);
}

function mblog_error($log_msg) {
   $d = debug_backtrace();
   mblog_message('error', $log_msg, $d);
}


function mblog_message($level, $log_msg, $d) {
    $msg = 'call, file:'.$d[0]['file'].' line:'.$d[0]['line']. ' msg:'.$log_msg;
    log_message($level, $msg);
}