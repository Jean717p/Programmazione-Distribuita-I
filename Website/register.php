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

    $register_error = "";
    $psw_regex = "/^(?=.*[^A-Za-z0-9])(.{3,})*$/";
    if (isset($_POST['submit_register'])) {
        $transaction_started = false;
        try {
            if ($_POST['username_register'] == "" || $_POST['password_register'] == "" || $_POST['password_register_confirm'] == "") {
                throw new Exception("All fields are required for registration.");
            }

            $username = $_POST['username_register'];
            $password = $_POST['password_register'];
            $confirm = $_POST['password_register_confirm'];

            if (!filter_var($username, FILTER_VALIDATE_EMAIL)) {
                throw new Exception("The username is not a valid email.");
            }

            if ($password != $confirm) {
                throw new Exception("Passwords mismatch, please try again.");
            }

            if (!preg_match($psw_regex, $password)) {
                throw new Exception("The password lenght must be at least 3 and 
                    must contain at least one non alphanumeric character");
            }

            $conn = new DatabaseInterface();
            $username = $conn->secure($username);
            $password = md5($password);

            $conn->start_transaction();
            $transaction_started = true;

            $previous_user = get_users($conn->query("SELECT * FROM users WHERE email='$username' FOR UPDATE;"));
            if (count($previous_user) != 0) {
                throw new Exception("This username is already use. Please choose another.");
            }
            $conn->query("INSERT INTO users (email, password) VALUES ('$username','$password');");
            $new_user = get_users($conn->query("SELECT * FROM users WHERE email='$username';"))[0];
            session_fields($new_user);
            $conn->end_transaction();

            redirect('moves.php');
        } catch (Exception $e) {
            $register_error = $e->getMessage();
            if ($transaction_started)
                $conn->rollback_transaction();
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
                    <div id='register_panel'>
                        <h2 class="jumbotron text-white bg-info text-center">Sign Up!</h2>
                        <?php if ($register_error != ""): ?>
                            <div class="alert alert-info">
                                <strong><?php echo $register_error; ?></strong>
                            </div>
                        <?php endif; ?>
                        <form id='register' action='register.php' method='POST' onsubmit="return validate_register();">
                            <div class="form-group">
                                <label for="email">Email address:</label>
                                <input type="email" class="form-control" name='username_register' maxlength=45 required
                                       placeholder='Email (this will be the username)'>
                            </div>
                            <div class="form-group">
                                <label for="pwd">Password:</label>
                                <input type='password' class="form-control" name='password_register' id='password_register' maxlength=45
                                       required
                                       placeholder='Password'>
                            </div>
                            <div class="form-group">
                                <input type='password' class="form-control mt-2" name='password_register_confirm' id='password_register_confirm'
                                       maxlength=45 required
                                       placeholder='Repeat password'>
                            </div>
                            <button type='submit' name='submit_register' class='btn btn-primary'>Register</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <?php require_once('./php/fragments/footer.php'); ?>
    </body>
</html>
