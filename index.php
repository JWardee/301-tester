<style>
	/*body {*/
		/*position: relative;*/
	/*}*/

	/*.output {*/
		/*position: absolute;*/
		/*bottom: 0;*/
		/*left: 0;*/
		/*right: 0;*/
		/*height: 100%;*/
	/*}*/

	/*.output .btn {*/
		/*position: fixed;*/
		/*bottom: 15px;*/
	/*}*/
</style>

<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if (isset($_POST['htaccess']) && $_POST['htaccess'] != '' &&
    isset($_POST['url']) && $_POST['url'] != ''):

    require_once 'UrlTester.php';
    $test_urls = new UrlTester($_POST['url'], $_POST['htaccess']);

    echo '<h1>Succeeded</h1>';
    var_dump($test_urls->results['succeeded']);
    echo '<h1>Failed</h1>';
    var_dump($test_urls->results['failed']);

else: ?>

	<link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css" rel="stylesheet" />
	<div style="margin-top: 10%;">
		<div class="container">
			<form action="?" method="post">
				<div class="form-group">
					<p>
						http://<input name="url" class="form-control" type="text" placeholder="google.com" />
					</p>
				</div>
				<div class="form-group">
					<p>Separate old urls from new with a space and seperate each whole url with a new line</p>
				</div>
				<div class="form-group">
					<textarea name="htaccess" rows="5" class="form-control" placeholder="old-url-one/ /new-url-one/
old-url-two/ /new-url-two/"></textarea>
				</div>
				<div class="form-group">
					<input class="btn btn-primary" type="submit" value="Submit" />
				</div>
			</form>
		</div>
	</div>

<?php endif; ?>