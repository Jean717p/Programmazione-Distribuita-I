<?php
    if(!defined('DIR_BASE')){
        require_once '../../config.php';
    }
    require_once DIR_PHP_FUNCTIONS . 'db_manager.php';
    require_once DIR_PHP_FUNCTIONS . 'lib.php';
    require_once DIR_PHP_FUNCTIONS . 'session_manager.php';
    require_once DIR_PHP_OBJECTS . 'rect.php';

    start_session();
    if(!is_logged()){
        echo -1;
        return;
    }
    if(!isset($_SESSION['username'])){
        echo -3;
        return;
    }
    $username = $_SESSION['username'];
    $result;
    $err_echoed = false;
    try {
        $conn = new DatabaseInterface();
        $username = $conn->secure($username);
        $q = "SELECT * FROM rectangles 
              WHERE email='$username'
              ORDER BY rectangles.timestamp DESC
              LIMIT 1
              FOR UPDATE;
              ";
        $conn->start_transaction();
        $transaction_started = true;
        $result = get_rects($conn->query($q));
        if(count($result)!=1){
            echo -4; //no rectangles to remove
            $err_echoed = true;
            throw new Exception();
        }
        $r = $result[0];
        $x0 = $r->x0;
        $x1 = $r->x1;
        $y0 = $r->y0;
        $y1 = $r->y1;
        $q = "DELETE FROM rectangles 
              WHERE email='$username' 
              AND x0=$x0 AND x1=$x1
              AND y0=$y0 AND y1=$y1;
              ";
        $conn->query($q);
        $conn->end_transaction();
        $format = '%d-%d_%d-%d';
        echo sprintf($format,$x0,$y0,$x1,$y1);
    } catch (Exception $e) {
        if ($transaction_started){
            $conn->rollback_transaction();
        }
        if(!$err_echoed){
            echo -11;
        }
    }
?>