<?php
    require_once 'config.php';
    require_once DIR_PHP_FUNCTIONS.'session_manager.php';
    require_once DIR_PHP_FUNCTIONS.'lib.php';
    start_session();
    check_cookie();
?>
<meta charset="UTF-8">
<meta name="description" content="Play a multiplayer game">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="keywords" content=""><?php // TODO:  ?>
<meta name="page_title" content="<?php echo PAGE_TITLE; ?>"
<meta name="author" content="Jeanpierre Francois S243920">
<link rel="stylesheet" href="./css/header.css"/>
<link rel="stylesheet" href="./css/sidebar.css"/>
<link rel="stylesheet" href="./css/footer.css"/>
<link rel="stylesheet" href="./css/bootstrap.min.css">
<link rel="stylesheet" href="./css/font-awesome.min.css">
<link rel="stylesheet" href="./css/style.css"/>
<script src="./js/jquery-1.7.2.js"></script>
<script src="./js/functions.js"></script>
