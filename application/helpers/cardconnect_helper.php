<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

function cardConnectInquireMerchant($data = array())
{

    $mid = $data['merchant_id'];
    $url = CARDCONNECT_URL . 'inquireMerchant/' . $mid;
    $auth = base64_encode($data['username'] . ':' . $data['password']);

    $curl = curl_init();
    curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "GET");
    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_HTTPHEADER, array(
        "Authorization: Basic " . $auth,
        "Content-Type: application/json",
    ));
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
    $response = curl_exec($curl);
    $err = curl_error($curl);
    $http_code = curl_getinfo($curl, CURLINFO_HTTP_CODE);
    curl_close($curl);
    $result = json_decode($response);

    if ($err) {
        return array('status' => 400, 'message' => $err, 'result' => array());
    } else {
        switch ($http_code) {
            case 200: # OK
                return array('status' => 200, 'message' => 'successfully', 'result' => $result);
                break;
        }
    }

}

function cardConnectAuthorize($data = array())
{
    $mid = $data['merchid'];
    $url = CARDCONNECT_URL . 'auth';
    $auth = base64_encode($data['username'] . ':' . $data['password']);
    
    $curl = curl_init();
    curl_setopt($curl, CURLOPT_POST, 1);
    curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($data['requestData']));
    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_HTTPHEADER, array(
        "Authorization: Basic " . $auth,
        "Content-Type: application/json",
    ));
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);

    $response = curl_exec($curl);
    $err = curl_error($curl);
    $http_code = curl_getinfo($curl, CURLINFO_HTTP_CODE);
    curl_close($curl);
    $result = json_decode($response);

    if ($err) {
        return array('status' => 400, 'message' => $err, 'result' => array());
    } else {
        switch ($http_code) {
            case 200: # OK
                return array('status' => 200, 'message' => 'successfully', 'result' => $result);
                break;
        }
    }

}

function cardConnectTokenizeAccount($acct_num = array())
{
    $csurl = CARDCONNECT_CSURL;

    $curl = curl_init();
    curl_setopt($curl, CURLOPT_POST, 1);
    curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($acct_num['tokenData']));
    curl_setopt($curl, CURLOPT_URL, $csurl);
    curl_setopt($curl, CURLOPT_HTTPHEADER, array(
        "Content-Type: application/json",
    ));
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);

    $response = curl_exec($curl);
    $err = curl_error($curl);
    $http_code = curl_getinfo($curl, CURLINFO_HTTP_CODE);
    curl_close($curl);
    $result = json_decode($response);

    if ($err) {
        return array('status' => 400, 'message' => $err, 'result' => array());
    } else {
        switch ($http_code) {
            case 200: # OK
                return array('status' => 200, 'message' => 'successfully', 'result' => $result);
                break;
        }
    }

}

function cardConnectCapture($data = array())
{
    $merchid = $data['merchid'];
    $url = CAPTURE_URL;
    $auth = base64_encode($data['username'] . ':' . $data['password']);

    $curl = curl_init();
    curl_setopt($curl, CURLOPT_POST, 1);
    curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($data['capData']));
    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_HTTPHEADER, array(
        "Authorization: Basic " . $auth,
        "Content-Type: application/json",
    ));
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);

    $response = curl_exec($curl);
    $err = curl_error($curl);
    $http_code = curl_getinfo($curl, CURLINFO_HTTP_CODE);
    curl_close($curl);
    $result = json_decode($response);

    if ($err) {
        return array('status' => 400, 'message' => $err, 'result' => array());
    } else {
        switch ($http_code) {
            case 200: # OK
                return array('status' => 200, 'message' => 'successfully', 'result' => $result);
                break;
        }
    }

}

function cardConnectInquiry($data = array())
{
    $merchid = $data['merchid'];
    $retref = $data['retref'];
    $url = INQUIRE_URL . $retref . '/' . $merchid;
    $auth = base64_encode($data['username'] . ':' . $data['password']);

    $curl = curl_init();
    curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "GET");
    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_HTTPHEADER, array(
        "Authorization: Basic " . $auth,
        "Content-Type: application/json",
    ));
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);

    $response = curl_exec($curl);
    $err = curl_error($curl);
    $http_code = curl_getinfo($curl, CURLINFO_HTTP_CODE);
    curl_close($curl);
    $result = json_decode($response);

    if ($err) {
        return array('status' => 400, 'message' => $err, 'result' => array());
    } else {
        switch ($http_code) {
            case 200: # OK
                return array('status' => 200, 'message' => 'successfully', 'result' => $result);
                break;
        }
    }

}

function updateCloverProfile($data = array())
{
    // die(print_r($data));
    $pro = $data['proData']['profile'];
    $merch = $data['proData']['merchid'];
    $url = PROFILE_URL;
    $auth = base64_encode($data['username'] . ':' . $data['password']);

    $curl = curl_init();
    curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "POST");
    curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($data['proData']));
    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_HTTPHEADER, array(
        "Authorization: Basic " . $auth,
        "Content-Type: application/json",
    ));
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);

    $response = curl_exec($curl);
    $err = curl_error($curl);
    $http_code = curl_getinfo($curl, CURLINFO_HTTP_CODE);
    curl_close($curl);
    $result = json_decode($response);

    if ($err) {
        return array('status' => 400, 'message' => $err, 'result' => array());
    } else {
        switch ($http_code) {
            case 200: # OK
                return array('status' => 200, 'message' => 'successfully', 'result' => $result);
                break;
        }
    }
}



function encryptPassword($pw)
{

    $method = AES_256_CBC;
    $key = OPENSSL_KEY;
    $bkey = hex2bin($key);
    $iv = openssl_random_pseudo_bytes(openssl_cipher_iv_length($method));
    $encrypted = openssl_encrypt($pw, $method, $bkey, 0, $iv);
    return base64_encode($encrypted . ':' . $iv);
}

function decryptPassword($encrypted)
{
    $method = AES_256_CBC;
    $key = OPENSSL_KEY;
    $bkey = hex2bin($key);
    $decoded = base64_decode($encrypted);
    $parts = explode(':', $decoded);
    $decrypted = openssl_decrypt($parts[0], $method, $bkey, 0, $parts[1]);
    return $decrypted;
}