<?php

    function start_session()
    {
        if (!isset($_SESSION)) {
            session_start();
        }
    }

    /* $_SESSION global initializer */
    function session_fields($user)
    {
        $_SESSION['username'] = $user->username;
        $_SESSION['last_interaction'] = time();
    }

    function is_logged()
    {
        $expiration_time = 2 * 60; //2 minuti
        if (isset($_SESSION['username'])) {
            $now = time();
            // If more than $expiration_time passed we logout and return false
            if ($now - $_SESSION['last_interaction'] > $expiration_time) {
                logout();
                return false;
            }
            // Update timestamp
            $_SESSION['last_interaction'] = $now;
            return true;
        }
        // If we never logged in
        return false;
    }

    function is_page($toCheck){
        $current_page_name = basename($_SERVER['PHP_SELF']);
        if(strcmp($toCheck,$current_page_name)==0){
            return true;
        }
        return false;
    }

    function logout()
    {
        $_SESSION = array(); //delete all session data
        if (session_id() != "" || isset($_COOKIE[session_name()])){
            setcookie(session_name(), '', time() - 7000000, '/');
        }
        session_destroy();
    }

    function check_cookie(){
        if (!isset($_COOKIE['cookieCheck'])){
            if (isset($_GET['cookieCheck'])){
                if(!is_page('nocookie.php')){
                    redirect('nocookie.php');
                }
            }
            else{
                setcookie('cookieCheck', '1', time() + 3600);
                die(header('Location: ' . $_SERVER['PHP_SELF'] . '?cookieCheck=1'));
            }
        }
        else if(is_page('nocookie.php')){
            redirect('index.php');
        }
        else if(isset($_GET['cookieCheck'])){
            redirect($_SERVER['PHP_SELF']);
        }
    }

?>
