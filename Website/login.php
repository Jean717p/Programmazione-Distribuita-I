<?php
    require_once 'config.php';
    require_once DIR_PHP_FUNCTIONS . 'force_https.php';
    require_once DIR_PHP_FUNCTIONS . 'session_manager.php';
    require_once DIR_PHP_FUNCTIONS . 'db_manager.php';
    require_once DIR_PHP_FUNCTIONS . 'lib.php';

    start_session();
    if (is_logged()) {
        redirect('index.php');
    }

    $login_error = "";
    $psw_regex = "/^(?=.*[^A-Za-z0-9])(.{3,})*$/";

    if (isset($_POST['submit_login'])) {
        try {
            if ($_POST['username_login'] == "" || $_POST['password_login'] == "") {
                throw new Exception("All fields are required for login.");
            }
            $username = $_POST['username_login'];
            $password = $_POST['password_login'];
            if (!filter_var($username, FILTER_VALIDATE_EMAIL)) {
                throw new Exception("The username is not a valid email.");
            }
            if (!preg_match($psw_regex, $password)) {
                throw new Exception("The password lenght must be at least 3 and 
                    must contain at least one non alphanumeric character");
            }

            $conn = new DatabaseInterface();
            $username = $conn->secure($username);
            $password = md5($password);
            $result_set = get_users($conn->query("SELECT * FROM users WHERE email='$username';"));
            if (count($result_set) != 1) {
                throw new Exception("The username does not exist. Want to join us? Register now!");
            }
            if ($result_set[0]->password != $password) {
                throw new Exception("Wrong password, please try again.");
            }
            session_fields($result_set[0]);
            redirect('moves.php');
        } catch (Exception $e) {
            $login_error = $e->getMessage();
        }
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <?php require_once('./php/fragments/head_tags.php'); ?>
</head>
    <body onload="onload_handler()">
        <?php require_once('./php/fragments/header.php'); ?>
        <div class="container" role="main" id="auth_main">
            <div class="row">
                <div class="col-sm-3">
                    <?php require_once('./php/fragments/sidebar.php'); ?>
                </div>
                <div class="col-sm-1">
                </div>
                <div class="col-sm-6">
                    <div id='login_panel'>
                        <h2 class="jumbotron text-white bg-info text-center">Login</h2>
                        <?php if ($login_error != ""): ?>
                            <div class="alert alert-info">
                                <strong><?php echo $login_error; ?></strong>
                            </div>
                        <?php endif; ?>
                        <form id='login' action='login.php' method='POST' onsubmit="return validate_login();">
                            <div class="form-group">
                                <label for="email">Email address:</label>
                                <input type="email" class="form-control" name='username_login' required placeholder='Username'>
                            </div>
                            <div class="form-group">
                                <label for="pwd">Password:</label>
                                <input type='password' class="form-control" name='password_login' id='password_login' required
                                       placeholder='Password'>
                            </div>
                            <button type='submit' name='submit_login' class="btn btn-primary">Login</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <?php require_once('./php/fragments/footer.php'); ?>
    </body>
</html>
