<?php
/**
 * Class UrlTester
 */
class UrlTester {

    public $results;
    private $delimiter;
    private $root_url;
    private $old_new_urls;
    private $url_dump;

    /**
     * @param $root_url
     * @param $url_dump
     * @param string $delimiter
     */
    function __construct($root_url, $url_dump, $delimiter = ' ') {
        $this->delimiter = $delimiter;
        $this->global_url = $root_url;
        $this->url_dump = $url_dump;

        $this->ProcessRootUrl();
        $this->ProcessUrlDump();
        $this->TestAllUrls();
    }

    /**
     *
     */
    private function ProcessRootUrl() {
        $this->root_url = 'http://'.$_POST['url'];
    }

    /**
     *
     */
    private function ProcessUrlDump() {
        $urls_to_test = explode("\n", $this->url_dump);

        $this->old_new_urls = array();

        foreach ($urls_to_test as $url) {
            $tmp = explode(' ', $url);

            $tmp[0] = $this->ProcessUrlPart($tmp[0]);
            $tmp[1] = $this->ProcessUrlPart($tmp[1]);

            $this->old_new_urls[] = array('old_url' => $tmp[0], 'new_url' => $tmp[1]);
        }
    }

    /**
     * @param $url_part
     * @return string
     */
    private function ProcessUrlPart($url_part) {
        if ($url_part[0] != '/') {
            return '/'.$url_part;
        }

        return $url_part;
    }

    /**
     *
     */
    private function TestAllUrls() {
        $this->results = array();
        $this->results['succeeded'] = array();
        $this->results['failed'] = array();

        //sleep(1);


        $this->FlushOutput('<ol>');

        foreach ($this->old_new_urls as $url) {

            $this->FlushOutput('<li>Opening connection to... '.$this->root_url.$url['old_url'].'<br />');
            $test = $this->TestUrl($url['old_url'], $url['new_url']);
            $this->FlushOutput('<li>Closing connection to... '.$this->root_url.$url['old_url'].'<br />');

            if ($test['success']) {
                $this->results['succeeded'][] = array($url['old_url'].' '.$url['new_url']);
            } else {
                $this->results['failed'][] = array($url['old_url'].' '.$url['new_url'], $test['response']);
            }
        }

        $this->FlushOutput('</ol>');
    }

    /**
     * @param $old_url
     * @param $new_url
     * @return array
     */
    private function TestUrl($old_url, $new_url) {

        $ch = curl_init($this->root_url.$old_url);
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

        if ($response['http_code'] == 200) {// && $response['url'] == $root_url.$new_url) {
            return array('success' => true);
        } else {
            return array('success' => false, 'response' => $response);
        }
    }

    /**
     * @param $content_to_flush
     */
    private function FlushOutput($content_to_flush) {
        ob_start();
        echo $content_to_flush;
        ob_end_flush();
    }

} 