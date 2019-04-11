<?php
require_once DIR_PHP_FUNCTIONS . 'db_manager.php';
require_once DIR_PHP_OBJECTS . 'user.php';
require_once DIR_PHP_OBJECTS . 'rect.php';

function redirect($url)
{
    header('Location:' . $url, true, 303);
    exit();
}

/* Returns users from users table */
function get_users($result)
{
    if ($result instanceof mysqli_result) {
        $result_set = array();
        while ($row = $result->fetch_assoc()) {
            $u = new User($row['email'], $row['password']);
            array_push($result_set, $u);
        }
        return $result_set;
    } else {
        return array();
    }
}

/* Returns rectangles from rectangles table */
function get_rects($result)
{
    if ($result instanceof mysqli_result) {
        $result_set = array();
        while ($row = $result->fetch_assoc()) {
            $u = new Rect($row['email'], $row['x0'], $row['x1'], $row['y0'], $row['y1'],$row['timestamp']);
            array_push($result_set, $u);
        }
        return $result_set;
    } else {
        return array();
    }
}

/* DEBUG */
function console_log($data)
{
    echo '<script>';
    echo 'console.log(' . json_encode($data) . ')';
    echo '</script>';
}

?>
