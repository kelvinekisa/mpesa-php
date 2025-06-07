<?php

include 'access_token.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    header('Content-Type: application/json');
    $data = json_decode(file_get_contents("php://input"));
    
    // validate input
    if (!isset($data->phone) || !isset($data->amount)) {
        http_response_code(400); // Bad Request
        echo json_encode([
            'status_code' => 400,
            'msg' => 'Missing phone or amount in request'
        ]);
        exit();
    }
    
    $phone = $data->phone;
    $amount = $data->amount;

    // format phone number ensure it's in 2547******** format
    if (substr($phone, 0, 1) === '0') {
        $phone = '254' . substr($phone, 1);
    } elseif (substr($phone, 0, 3) !== '254') {
        $phone = '254' . $phone;
    }

    // generate access token, prepare your request data etc.
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
        'PartyA' => $phone,
        'PartyB' => $BusinessShortCode,
        'PhoneNumber' => $phone,
        'CallBackURL' => 'https://96d8-102-0-15-226.ngrok-free.app/callback_url.php',
        'AccountReference' => 'Test123',
        'TransactionDesc' => 'Payment for testing'
    ];

    $url = 'https://sandbox.safaricom.co.ke/mpesa/stkpush/v1/processrequest';
    $curl = curl_init($url);
    curl_setopt($curl, CURLOPT_HTTPHEADER, [
        'Authorization: Bearer ' . $accessToken,
        'Content-Type: application/json'
    ]);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl, CURLOPT_POST, true);
    curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($curl_post_data));

    $response = curl_exec($curl);
    curl_close($curl);

    echo json_encode(json_decode($response, true));
} else {
    http_response_code(405);
    header('Content-Type: application/json');
    echo json_encode([
        'status_code' => 405,
        'msg' => 'Method Not Allowed. Use POST with JSON body.'
    ]);
}
?>