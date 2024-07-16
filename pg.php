<?php

$phone_number = '+918171619719';

// Remove leading '+91' from phone number if present
if (strpos($phone_number, '+91') === 0) {
    $phone_number = substr($phone_number, 3);
}

$paymentDetails = [
    'merchantOrderId' => "orderID" . rand(100, 99999),
    'requestId' => '1'
];

$encryptedData = base64_encode(json_encode($paymentDetails));

// $redirect_url = route('phonepe.confirm', ['encryptedData' => $encryptedData]);
$payload = [
    "merchantId" => "M226DWVS5EFSO",
    "merchantTransactionId" => "yehlo" . rand(1000, 99999999999),
    "merchantUserId" => "merch" . rand(1000, 99999999999),

    "amount" => 100 * 2,
    "redirectUrl" => 'https://yehlo.app/confirm',
    "redirectMode" => "POST",
    "callbackUrl" => 'https://yehlo.app/confirm',
    "mobileNumber" => "8171619719",
    "paymentInstrument" => ["type" => "PAY_PAGE"],
];

$encodedPayload = base64_encode(json_encode($payload));
$jsonData = json_encode(["request" => $encodedPayload]);


$saltKey = "0b577413-c778-48a7-b755-c4303c8c5839";

$salt_index = '1';

$checksum = hash('sha256', $encodedPayload . "/pg/v1/pay" . $saltKey) . "###" . $salt_index;

if ($checksum) {
    $headers = [
        'Content-Type: application/json',
        'X-VERIFY: ' . $checksum,
    ];

    $ch = curl_init();

    curl_setopt($ch, CURLOPT_URL, 'https://api.phonepe.com/apis/hermes/pg/v1/pay');
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_ENCODING, 1);

    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonData);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

    $response = curl_exec($ch);

    if ($curlError = curl_error($ch)) {
        curl_close($ch);
        return response()->json(['error' => $curlError], 500);
    }
    curl_close($ch);

    $response = json_decode($response);

    if ($response->success == 1 && $response->code == 'PAYMENT_INITIATED') {

        $paymentUrl = $response->data->instrumentResponse->redirectInfo->url;

        print_r($paymentUrl);
        exit();
    } else {

        return response()->json($this->response_formatter(GATEWAYS_DEFAULT_400), 400);
    }
} else {
    return response()->json($this->response_formatter(GATEWAYS_DEFAULT_204), 200);
}
