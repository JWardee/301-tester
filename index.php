<?php
//ini_set('display_errors', 1);
//ini_set('display_startup_errors', 1);
//error_reporting(E_ALL);

// hospital-plan/hospital-plan/ /contact-us/

function NewHTTP($root_url, $old_url, $new_url) {
	$header = array(
		'User-Agent: Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.9.2.12) Gecko/20101026 Firefox/3.6.12',
		'Accept: text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8',
		'Accept-Language: en-us,en;q=0.5',
		'Accept-Encoding: gzip,deflate',
		'Accept-Charset: ISO-8859-1,utf-8;q=0.7,*;q=0.7'
	);

	$ch = curl_init($root_url.$old_url);
	curl_setopt($ch, CURLOPT_HEADER, true);
	curl_setopt($ch, CURLOPT_NOBODY, true);
	curl_setopt($ch, CURLINFO_EFFECTIVE_URL, true);
	curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
	curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
	curl_exec($ch);
	$response = curl_getinfo($ch);

	if ($response['http_code'] == 200) {// && $response['url'] == $root_url.$new_url) {
		return true;
	} else {
		return false;
	}

	ob_flush();
	flush();

	curl_close($ch);
}


if (isset($_POST['htaccess']) && $_POST['htaccess'] != '' &&
    isset($_POST['url']) && $_POST['url'] != ''):

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

		if ($test) {
			$result['succeeded'][] = $url['old_url'].' '.$url['new_url'];
		} else {
			$result['failed'][] = $url['old_url'].' '.$url['new_url'];
		}
	}


	echo '<h1>Successes</h1>';
	foreach ($result['succeeded'] as $success) {
		echo $success.'<br />';
	}
	echo '<hr />';
	echo '<h1>Failures</h1>';
	foreach ($result['failed'] as $failed) {
		echo $failed.'<br />';
	}
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