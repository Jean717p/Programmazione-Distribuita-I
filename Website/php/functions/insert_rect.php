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
    if(!isset($_POST['x0'])|| !isset($_POST['x1'])
        || !isset($_POST['y0'])|| !isset($_POST['y1'])
        || !isset($_SESSION['username'])){
        echo -3;
        return;
    }
    $username = $_SESSION['username'];
    $x0 = (int)min((int)$_POST['x0'],(int)$_POST['x1']);
    $x1 = (int)max((int)$_POST['x0'],(int)$_POST['x1']);
    $y0 = (int)min((int)$_POST['y0'],(int)$_POST['y1']);
    $y1 = (int)max((int)$_POST['y0'],(int)$_POST['y1']);
    $result;
    try {
        $conn = new DatabaseInterface();
        if(is_nan($x0)||is_nan($y0)){
            echo -5;
            throw new Exception();
        }
        $x0 = $conn->secure($x0);
        $x1 = $conn->secure($x1);
        $y0 = $conn->secure($y0);
        $y1 = $conn->secure($y1);
        $conn->start_transaction();
        $transaction_started = true;
        $q = "SELECT COUNT(*) FROM rectangles FOR UPDATE;";
        $conn->query($q); //Lock table
        if(RECT_LENGHT ==1){
            if($x0!==$y0 || $x0<0 || $x0 > TABLE_X || $y0>TABLE_Y || is_nan($x0)){
                echo -6;
                throw new Exception();
            }
            $q = "SELECT * FROM rectangles WHERE 
                      (x0>=$x0-1 AND x0<=$x0+1) 
                    AND 
                      (y0>=$y0-1 AND y0<=$y0+1)
                    LIMIT 1
                    ;";
            $result = get_rects($conn->query($q));
        }
        else if($x0<0 || $y0 < 0 || $x1 >= TABLE_X || $y1 >= TABLE_Y
            || is_nan($x1) || is_nan($y1)){
            echo -7;
            throw new Exception();
        }
        else if($x0!=$x1 && $y0==$y1){
            if(($x1-$x0+1)!=RECT_LENGHT){
                echo -8;
                throw new Exception();
            }
            $q = "SELECT * FROM rectangles WHERE 
                  (x0=x1 AND ( 
                    (x0>=$x0-1 AND x0<=$x1+1) 
                    AND 
                    (
                      (y0>=$y0-1 AND y0<=$y0+1) OR (y1>=$y0-1 AND y1<=$y0+1))
                    )
                  ) 
                  OR 
                  (y0=y1 AND (
                    (y0>=$y0-1 AND y0<=$y0+1) 
                    AND 
                    (
                      (x0>=$x0-1 AND x0<=$x1+1) OR (x1>=$x0-1 AND x1<=$x1+1))
                    )
                  )
                  LIMIT 1
                  ;";
            $result = get_rects($conn->query($q));
        }
        else if($y0!=$y1 && $x0 == $x1){
            if(($y1-$y0+1)!=RECT_LENGHT){
                echo -9;
                throw new Exception();
            }
            $q = "SELECT * FROM rectangles WHERE 
                  (x0=x1 AND ( 
                    (x0>=$x0-1 AND x0<=$x0+1) 
                    AND 
                    (
                      (y0>=$y0-1 AND y0<=$y1+1) OR (y1>=$y0-1 AND y1<=$y1+1))
                    )
                  ) 
                  OR 
                  (y0=y1 AND (
                    (y0>=$y0-1 AND y0<=$y1+1) 
                    AND 
                    (
                      (x0>=$x0-1 AND x0<=$x0+1) OR (x1>=$x0-1 AND x1<=$x0+1))
                    )
                  )
                  LIMIT 1
                  ;";
            $result = get_rects($conn->query($q));
        }
        else{
            echo -10;
            throw new Exception();
        }
        if(count($result)!=0){
            echo -11;
            throw new Exception();
        }
        $t = date("Y-m-d H:i:s",time());
        $conn->query("INSERT INTO rectangles VALUES ('$username','$x0','$x1','$y0','$y1','$t');");
        $conn->end_transaction();
        echo 0;
    } catch (Exception $e) {
        if ($transaction_started){
            $conn->rollback_transaction();
        }
    }
?>