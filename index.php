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
//ini_set('display_errors', 1);
//ini_set('display_startup_errors', 1);
//error_reporting(E_ALL);

function NewHTTP($root_url, $old_url, $new_url) {

	echo '<li>Opening connection to... '.$root_url.$old_url.'<br />';

	ob_flush();
	flush();

	$ch = curl_init($root_url.$old_url);
	curl_setopt($ch, CURLOPT_HEADER, true);
	curl_setopt($ch, CURLOPT_NOBODY, true);
	curl_setopt($ch, CURLINFO_EFFECTIVE_URL, true);
	curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
	curl_setopt($ch, CURLOPT_TIMEOUT, 0);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.8.1.13) Gecko/20080311 Firefox/2.0.0.13');
	curl_exec($ch);
	$response = curl_getinfo($ch);
	curl_close($ch);

	echo 'Closed connection to... '.$root_url.$old_url.'</li>';

	ob_flush();
	flush();

	if ($response['http_code'] == 200) {// && $response['url'] == $root_url.$new_url) {
		return array('success' => true);
	} else {
		return array('success' => false, 'response' => $response);
	}
}


if (isset($_POST['htaccess']) && $_POST['htaccess'] != '' &&
    isset($_POST['url']) && $_POST['url'] != ''):

	echo '<div class="output"><ol>';

	$root_url = 'http://'.$_POST['url'];
	$urls_to_test = explode("\n", $_POST['htaccess']);

	$urls = array();

	foreach ($urls_to_test as $url) {
		$tmp = explode(' ', $url);

		if ($tmp[0][0] != '/') {
			$tmp[0] = '/'.$tmp[0];
		}

		if ($tmp[1][0] != '/') {
			$tmp[1] = '/'.$tmp[1];
		}

		$urls[] = array('old_url' => $tmp[0], 'new_url' => $tmp[1]);
	}

	$result = array();
	$result['succeeded'] = array();
	$result['failed'] = array();

	foreach ($urls as $url) {
		$test = NewHTTP($root_url, $url['old_url'], $url['new_url']);

		if ($test['success']) {
			$result['succeeded'][] = array($url['old_url'].' '.$url['new_url']);
		} else {
			$result['failed'][] = array($url['old_url'].' '.$url['new_url'], $test['response']);
		}
	}


	echo '<h1>Successes</h1>';
	var_dump($result['succeeded']);
	echo '<h1>Failures</h1>';
	var_dump($result['failed']);

	echo '</ol></div>';
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