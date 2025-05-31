<?php
function generateAccessToken() {
    $consumerKey = '8LijhgYEWhbjvCODfsPSOE12dAwPrpUeGZWBR89OA67MY4JX';
    $consumerSecret = 'Kp5Vci4RWqwUVCaqeUqG4YtEKNfy6ZAH8qiVkbIbI4SrKGQlfwERamkv4cABP2vj';


    $credentials = base64_encode($consumerKey . ':' . $consumerSecret);

    $url = 'https://sandbox.safaricom.co.ke/oauth/v1/generate?grant_type=client_credentials';

    $curl = curl_init($url);
    curl_setopt($curl, CURLOPT_HTTPHEADER, [
        'Authorization: Basic ' . $credentials,
        'Content-Type: application/json'
    ]);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

    $response = curl_exec($curl);
    $result = json_decode($response);

    curl_close($curl);

    return $result->access_token ?? null;

}
?>