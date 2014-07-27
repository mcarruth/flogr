<?php
require_once('flogr.php');

class Profiler {
    
    var $_startTime;
    var $_scopeText;
    var $_warnTime;
    var $_errTime;
    
    function __construct($scopeText = '', $warnTime = 0.01, $errTime = 2) {
        $this->_scopeText = $scopeText;
        $this->_startTime   = microtime(true);
        $this->_warnTime    = $warnTime;
        $this->_errTime     = $errTime;
        
        if ($this->_scopeText == '') {
            $debug_backtrace = debug_backtrace();
            $class_name = $debug_backtrace[1]["class"];
            $function_name = $debug_backtrace[1]["function"];
            $line_number = $debug_backtrace[1]["line"];
            $this->_scopeText = $class_name . "::" . $function_name . "(" . $line_number . "):";
        }        
    }
    
    function __destruct() {
        global $flogr;
        
        $endTime = microtime(true);
        $elapsedTime = round($endTime - $this->_startTime, 2);
                
        $message = $this->_scopeText . " completed in {$elapsedTime}s";
        
        if ($elapsedTime < $this->_warnTime) {
            $flogr->logInfo($message);
        } else if ($elapsedTime < $this->_errTime) {
            $flogr->logWarning($message);
        } else {
            $flogr->logErr($message);
        }
    }
}
?>