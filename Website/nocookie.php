<!DOCTYPE html>
<html lang="en">
<head>
    <?php require_once('./php/fragments/head_tags.php'); ?>
</head>
<body onload="onload_handler()">
    <?php require_once('./php/fragments/header.php'); ?>
    <main class="container jumbotron text-center alert-danger" id="nocookie_main">
      <h1>This website requires cookies to be enabled on your browser in order to work.</h1>
      <h3>Please enable cookies and refresh the page to access <?php echo PAGE_TITLE; ?></h3>
    </main>
    <?php require_once('./php/fragments/footer.php'); ?>
</body>
</html>
