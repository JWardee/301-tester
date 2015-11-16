<div class="container">

    <div class="progress">
        <div id="progress" class="progress-bar progress-bar-success progress-bar-striped" style="width: 0%"></div>
    </div>
    <div id="current-url">Starting...</div>

    <?php
    require_once $root_directory.'/lib/UrlTester.php';
    $test_urls = new UrlTester($_POST['url'], $_POST['htaccess']);
    ?>

    <?php if (count($test_urls->results['failed']) > 0): ?>
        <div class="accordion">
            <a href="javascript: void(0);" class="trigger">View Errors (<?php echo count($test_urls->results['failed']); ?>)</a>
            <div class="reveal">
                <pre>
                    <?php print_r($test_urls->results['failed']); ?>
                </pre>
            </div>
        </div>
    <?php else: ?>
        <p>No problems were found!</p>
    <?php endif; ?>
</div>