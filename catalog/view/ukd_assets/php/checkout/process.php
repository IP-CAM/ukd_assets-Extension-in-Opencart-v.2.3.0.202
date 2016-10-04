<?php

$url = $_GET['url'];

function sanitizeString($str)
{
    $str = preg_replace('![áàãâä]!ui', 'a', $str);
    $str = preg_replace('![éèêë]!ui', 'e', $str);
    $str = preg_replace('![íìîï]!ui', 'i', $str);
    $str = preg_replace('![óòõôö]!ui', 'o', $str);
    $str = preg_replace('![úùûü]!ui', 'u', $str);
    $str = preg_replace('![ç]!ui', 'c', $str);

    return $str;
}

$data = [];

foreach ($_POST as $key => $value) {

    //if (stripos($key, 'hash') === false )$value = strtoupper($value);

    if (stripos($value, 'http://') > -1 || stripos($value, 'https://') > -1) {
        $data[$key] = strtolower($value);
    } elseif (strpos($value, '@') > -1) {
        $data[$key] = sanitizeString(strtolower($value));
    } else {
        if (strpos($value, ' ') > -1) {
            $value = sanitizeString($value);
            $value = ucwords($value, ' ');
        } else {
            $value = sanitizeString($value);
        }
        $data[$key] = $value;
    }
}
// $data['email'] = 'fredukita@gmail.com';
// $data['token'] = '9E7583A0AB8F44EC9E6D72FB81C4693F';

$curl = curl_init($url);

curl_setopt($curl, CURLOPT_URL, $url);

curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);

curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);

curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

curl_setopt($curl, CURLOPT_HEADER, false);

curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, 20);

curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($data));

//ERRORS
$error = curl_errno($curl);
$errorMessage = curl_error($curl);

$result = curl_exec($curl);

curl_close($curl);

$finfo = new finfo(FILEINFO_MIME);

header('Content-Type: application/json');

if ($error || strpos($finfo->buffer($result),'application/xml') == -1 ) {
    exit(json_encode(array('error'=>$errorMessage)));
} else {
    $xml = simplexml_load_string($result);
    exit(json_encode($xml));
}
