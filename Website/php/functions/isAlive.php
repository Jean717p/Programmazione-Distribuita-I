<?php
    require_once "../../config.php";
    require_once DIR_PHP_FUNCTIONS . 'session_manager.php';

    start_session();
    if(!is_logged()){
        echo -1;
    }
    else{
        echo 0;
    }
?>