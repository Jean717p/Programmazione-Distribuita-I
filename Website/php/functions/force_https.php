<?php
    if(!$_SERVER['HTTPS']) {
        header("Location: https://$_SERVER[HTTP_HOST]$_SERVER[SCRIPT_NAME]?$_SERVER[QUERY_STRING]");
    }
?>
