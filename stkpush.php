<?php

include 'access_token.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $phone = $_POST['phone'] ?? '';
    $amount = $_POST['amount'] ?? '';

    // format phone number ensure it's in 2547******** format
    if (substr($phone, 0, 1) === '0') {
        $phone = '254' . substr($phone, 1);
    } elseif (substr($phone, 0, 3) === '254') {
        $phone = $phone;
    } else {
        $phone = '254' . $phone;
    }


    $accessToken = generateAccessToken();

    $BusinessShortCode = '174379'; // Replace with your Business Short Code
    $PassKey = 'bfb279f9c1b2b0c4d3f8e5a6b7c8d9e0'; // Replace with your Pass Key
    $Timestamp = date('YmdHis'); // Current timestamp in the format YYYYMMDDHHMMSS

    $Password = base64_encode($BusinessShortCode . $PassKey . $Timestamp);

    $curl_post_data = [
        'BusinessShortCode' => $BusinessShortCode,
        'Password' => $Password,
        'Timestamp' => $Timestamp,
        'TransactionType' => 'CustomerPayBillOnline',
        'Amount' => $amount,
        'PartyA' => $phone, // Customer's phone number
        'PartyB' => $BusinessShortCode, // Your Business Short Code
        'PhoneNumber' => $phone, // Customer's phone number
        'CallBackURL' => 'https://example.com/callback', // Replace with your callback URL
        'AccountReference' => 'Test123', // Replace with your account reference
        'TransactionDesc' => 'Payment for testing'
    ];
    $url = 'https://sandbox.safaricom.co.ke/mpesa/stkpush/v1/processrequest';
    $curl = curl_init($url);
    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_HTTPHEADER, [
        'Authorization: Bearer ' . $accessToken,
        'Content-Type: application/json'
    ]);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl, CURLOPT_POST, true);
    curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($curl_post_data));

    $response = curl_exec($curl);
    curl_close($curl);

    echo "<pre>";
    print_r(json_decode($response, true));
    echo "</pre>";
}
?>