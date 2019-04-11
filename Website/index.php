<?php
    require_once 'config.php';
    require_once DIR_PHP_FUNCTIONS.'session_manager.php';
    require_once DIR_PHP_FUNCTIONS.'db_manager.php';
    require_once DIR_PHP_FUNCTIONS.'lib.php';

    start_session();
    $conn = null;
    try {
       $conn = new DatabaseInterface();
    } catch (Exception $e) {
       echo $e->getMessage();
       exit();
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
   <?php require_once('./php/fragments/head_tags.php'); ?>
</head>
<body onload="onload_handler()">
    <?php require_once('./php/fragments/header.php'); ?>
    <div class="container" role="main">
        <div class="row">
            <div class="col-sm-3">
                <?php require_once('./php/fragments/sidebar.php'); ?>
                <?php if(is_logged()){
                    require_once DIR_PHP_FUNCTIONS . 'force_https.php';
                    echo '<div class="container mt-4">';
                    echo '<h3 class="text-left text-primary d-none d-md-inline">Welcome back!<br></h3>';
                    echo '<p class="text-left text-muted d-none d-md-inline">'.$_SESSION['username'].'</p>';
                    echo '</div>';
                }
                ?>
            </div>
            <div class="col-sm-9">
                <h1 class="jumbotron text-white bg-info text-center">Current game situation</h1>
                <?php require_once('./php/fragments/table.php'); ?>
            </div>
        </div>
    </div>
    <?php require_once('./php/fragments/footer.php'); ?>
</body>
</html>
