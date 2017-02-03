<?php
/**
 *
 * This is a temporary fix for MAIS updating the Workgroup API to accept
 * only TLSv1.2 requests as of February 4, 2017. pangea.stanford.edu currently
 * can make only TLSv1.0 requests, so those requests to the API will be proxied
 * by this script until this system takes over as pangea.stanford.edu
 * - Ken Sharp February 01, 2017
 *
 **/

// check if requesting ip is pangea or ksharp's dev machine
if (empty($_SERVER['REMOTE_ADDR']) ||
  ($_SERVER['REMOTE_ADDR'] !== '171.64.168.31' &&
   $_SERVER['REMOTE_ADDR'] !== '171.64.169.85')) {
    http_response_code(403);
    exit('access denied');
}

// set host name and keys for either production or uat versions
//$mais_host = 'workgroupsvc-uat';
//$cert_file = '/home/ksharp/mais_keys/mais-uat.crt';
//$key_file =  '/home/ksharp/mais_keys/mais-uat.key';
$mais_host = 'workgroupsvc';
$cert_file = '/home/ksharp/mais_keys/mais-prd.crt';
$key_file =  '/home/ksharp/mais_keys/mais-prd.key';

$result = array();
if (!empty($_GET['workgroup'])) {
    $wg = htmlspecialchars($_GET['workgroup'],ENT_QUOTES);
    $result = _get_workgroup_members($wg, $mais_host, $cert_file, $key_file);
}
$output = json_encode($result);
header('Content-Type: application/json');
echo $output;
exit;

function _get_workgroup_members($workgroup_name, $mais_host = NULL, 
                               $cert_file = NULL, $key_file = NULL)
{

    // make sure we are passed paths to the cert files
    if (empty($cert_file) || empty($key_file) || empty($mais_host)) {
        return FALSE;
    }
    // create the URL to access the workgroup, from variables set in settings.php
    // $ses_mais_uat is true if we are working against the MAIS test system
    $url = "https://" . $mais_host . ".stanford.edu/v1/workgroups/" . $workgroup_name;
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
        CURLOPT_FORBID_REUSE => true, 
        CURLOPT_FRESH_CONNECT => true, 
    );
    curl_setopt_array($ch, $options);
    $members = array();
    $output = curl_exec($ch);
    if ($output === FALSE) {
        // if $output is false, report a curl error
        $members['error'] = curl_error($ch);
        curl_close($ch);
        return FALSE;
    } else {
        curl_close($ch);
        // if we have output, parse it and create an array to return
        // parse the xml
        $p = xml_parser_create();
        $vals = array();
        $index = array();
        xml_parse_into_struct($p, $output, $vals, $index);
        xml_parser_free($p);

        // for each person in the workgroup, get their SUNet ID, directory name, and display name

        // don't include workgroup administrators in our return array
        if (isset($index['ADMINISTRATORS']) && is_array($index['ADMINISTRATORS'])) {
            $first_admin = $index['ADMINISTRATORS'][0];
        } else {
            $first_admin = 10000;
        }

        // check list for members
        if (!empty($index['MEMBER']) && count($index['MEMBER']) > 0) {
            foreach ($index['MEMBER'] as $key) {
                if ($key < $first_admin) {
                    $sunet = $vals[$key]['attributes']['ID'];
                    $directory_name = $vals[$key]['attributes']['NAME'];
                    $comma = strpos($directory_name, ',');
                    if ($comma === FALSE) {
                        $account_name = $directory_name;
                    } else {
                        $account_name = substr($directory_name, $comma + 2) . ' ' . substr($directory_name, 0, $comma);
                    }
                    $members[$sunet] = array('sunet' => $sunet, 'directory_name' => $directory_name, 'account_name' => $account_name);
                }
            }
        }

        // recursively return members of nested workgroups
        if (!empty($index['WORKGROUP']) && count($index['WORKGROUP']) > 0) {
            foreach ($index['WORKGROUP'] as $key) {
                if ($key < $first_admin && isset($vals[$key]['attributes']['NAME'])) {
                    $next_members = _get_workgroup_members($vals[$key]['attributes']['NAME'],$mais_host,$cert_file, $key_file);
                    if ($next_members === FALSE) {
                        return FALSE;
                    } else {
                        $members = array_merge($members, $next_members);
                    }
                }
            }
        }
    }
    // return our list of people from the workgroup
    return $members;
}
?>
