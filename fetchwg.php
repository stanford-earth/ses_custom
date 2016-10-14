<?php
$wg = preg_replace('/[^-a-zA-Z0-9_:]/', '', $_GET['wg']);
if (empty($wg)) {
    header("HTTP/1.1 404 Not Found");
} else {
    $url = "https://workgroupsvc.stanford.edu/v1/workgroups/" . $wg;
    $cert_file = '/WWW/sesmaind7/sites/all/modules/ses_custom/mais.crt'; //'/usr/local/apache/conf/MAIS/mais.crt';
    $key_file = '/WWW/sesmaind7/sites/all/modules/ses_custom/mais.key'; //'/usr/local/apache/conf/MAIS/mais.key';
    // create a curl session to get the workgroup data
    $ch = curl_init();

    $options = array(
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_FOLLOWLOCATION => false,
        CURLOPT_SSL_VERIFYHOST => false,
        CURLOPT_SSL_VERIFYPEER => false,
        CURLOPT_USERAGENT => 'Mozilla/4.0 (compatible; MSIE 5.01; Windows NT 5.0)',
        CURLOPT_URL => $url,
        CURLOPT_SSLCERT => $cert_file,
        CURLOPT_SSLKEY => $key_file,
        //CURLOPT_SSLVERSION => 1,
    );
    curl_setopt_array($ch, $options);
    $output = curl_exec($ch);
    curl_close($ch);
    if ($output === FALSE) {
        header("HTTP/1.1 404 Not Found");
	print $url . ' not found.';
    } else {
        print $output;
    }
}
?>
