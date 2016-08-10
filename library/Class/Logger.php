<?php
class Logger{  
    static public function log($msg){
        $th = new Zend_Log(new Zend_Log_Writer_Stream('../../application/logs/'.date('Ymd').'.log', 'a+'));
        $th->log(!is_string($msg) ? print_r($msg, true) : $msg);
        return true;
    }
}
?>