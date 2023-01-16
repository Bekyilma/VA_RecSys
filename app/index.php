<?php require 'config.php'; ?>
<!doctype html>
<html>
  <head>
    <meta name="viewport" content="width=device-width, minimum-scale=1.0, maximum-scale=1.0, user-scalable=no" />
    <link rel="stylesheet" type="text/css" href="css/bootstrap.min.css" />
    <link rel="stylesheet" type="text/css" href="css/bootstrap-reboot.min.css" />
    <link rel="stylesheet" type="text/css" href="css/index.css?v=<?php echo VERSION; ?>" />
    <script type="text/javascript" src="js/jquery-2.0.2.min.js"></script>
    <script type="text/javascript" src="js/bootstrap.min.js"></script>
  </head>
  <body>
    <?php
    $has_registered = isset($_SESSION['registered']);
    $has_preferences = $has_registered && !empty($_SESSION['preferences']);
    $has_finished = $has_preferences && count($_SESSION['conditions']) == 0;

    if (!$has_registered) {
        include 'welcome.php';
    } elseif (!$has_preferences) {
        include 'study-elicitation.php';
    } elseif (!$has_finished) {
        include 'study-ratings.php';
    } else {
        // Users from Prolific must be redirected.
        if (isset($_SESSION['pid'])) {
            header('Location: https://app.prolific.co/submissions/complete?cc=C17D7A6J');
        } else {
            include 'goodbye.php';
        }
    }
    ?>
  </body>
</html>
