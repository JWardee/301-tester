<div class="container">
    <?php
    require_once $root_directory.'/lib/UrlTester.php';
    $test_urls = new UrlTester($_POST['url'], $_POST['htaccess']);

    echo '<h1>Succeeded</h1>';
    var_dump($test_urls->results['succeeded']);
    echo '<h1>Failed</h1>';
    var_dump($test_urls->results['failed']);
    ?>
</div>