<?php
//ini_set('display_errors', 1);
//ini_set('display_startup_errors', 1);
//error_reporting(E_ALL);

$root_directory = '.';//__DIR__;
?>
<!DOCTYPE html>
<html>
<head lang="en">
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css" rel="stylesheet" />
    <link href="<?php echo $root_directory; ?>/assets/css/style.css" rel="stylesheet" />
    <meta charset="UTF-8">
    <title></title>
</head>
<body>
    <div class="vertical-centre">
        <?php
        if (isset($_POST['htaccess']) && $_POST['htaccess'] != '' && isset($_POST['url']) && $_POST['url'] != '') {
            require_once $root_directory.'/views/view-results.php';
        } else {
            require_once $root_directory.'/views/view-form.php';
        }
        ?>
    </div>
</body>
</html>