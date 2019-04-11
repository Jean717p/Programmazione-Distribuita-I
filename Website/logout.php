<?php
    require_once 'config.php';
    require_once DIR_PHP_FUNCTIONS.'session_manager.php';

	start_session();
    if (isset($_SESSION['username'])) {
		logout();
    }
	header('Location: index.php');
?>
