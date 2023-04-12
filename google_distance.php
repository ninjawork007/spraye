<?php
$origin_address='1546 Trenton Ln, Cape Girardeau, MO 63701, USA';
$destination_address='338 Broadway St 202, Cape Girardeau, MO 63701, USA';
echo $url = "https://maps.googleapis.com/maps/api/distancematrix/json?origins=".$origin_address."&destinations=".$destination_address."&key=AIzaSyDofkU_nUwD_b1xWn3LBrWuay0MG3zsO9M";
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_PROXYPORT, 3128);
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
$response = curl_exec($ch);
curl_close($ch);
$response_a = json_decode($response, true);
var_dump($response_a);

?>
