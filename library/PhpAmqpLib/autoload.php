<?php

function __autoload($classname) {
    $fname = str_replace("\\","/", $classname) . '.php';
    require_once($fname);
}

?>