<?php
    require_once 'config.php';
    require_once DIR_PHP_FUNCTIONS . 'force_https.php';
    require_once DIR_PHP_FUNCTIONS . 'session_manager.php';
    require_once DIR_PHP_FUNCTIONS . 'db_manager.php';
    require_once DIR_PHP_FUNCTIONS . 'lib.php';

    start_session();
    if (!is_logged()) {
        redirect('index.php');
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
                    <div class="btn-group-vertical btn-group d-flex" role="group">
                        <button type="button" id='remove_last_move' class='btn btn-danger mt-4'><span class="d-none d-md-inline">Remove last move </span><span class="fa fa-reply"><span/></button>
                        <button type="button" id='cancel_move' class='btn btn-warning mt-4 text-white'><span class="d-none d-md-inline">Cancel </span><span class="fa fa-times-circle-o"><span/></button>
                        <button type="button" id='submit_move' class='btn btn-success mt-4'><span class="d-none d-md-inline">Submit move </span><span class="fa fa-send-o"><span/></button>
                        <button type="button" id='game_help' class='btn btn-info mt-4 mb-2'><span class="d-none d-md-inline">Help </span><span class="fa fa-info-circle"><span/></button>
                    </div>
                </div>
                <div class="col-sm-9">
                    <h1 class="jumbotron text-white bg-info text-center" id="game_situation">Current game situation</h1>
                    <div id="the_game">
                        <?php require_once('./php/fragments/table.php'); ?>
                    </div>
                </div>
            </div>
        </div>
        <?php require_once('./php/fragments/footer.php'); ?>
    </body>
</html>
