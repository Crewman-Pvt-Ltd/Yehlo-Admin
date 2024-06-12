<?php

function generatePhonePeChecksum($payload, $saltKey, $saltIndex, $endpoint) {
    $base64Payload = base64_encode($payload);
    $checksum = hash('sha256', $base64Payload . $endpoint . $saltKey) . '###' . $saltIndex;
    return array($checksum, $base64Payload);
}

function initiatePhonePePayment($merchantId, $merchantTransactionId, $merchantUserId, $amount, $redirectUrl, $callbackUrl, $mobileNumber, $saltKey, $saltIndex) {
    $url = "https://api-preprod.phonepe.com/apis/pg-sandbox/pg/v1/pay";
    $endpoint = "/pg/v1/pay";

    $payload = json_encode(array(
        "merchantId" => $merchantId,
        "merchantTransactionId" => $merchantTransactionId,
        "merchantUserId" => $merchantUserId,
        "amount" => $amount,
        "redirectUrl" => $redirectUrl,
        "redirectMode" => "REDIRECT",
        "callbackUrl" => $callbackUrl,
        "mobileNumber" => $mobileNumber,
        "paymentInstrument" => array(
            "type" => "PAY_PAGE"
        )
    ));

    list($checksum, $base64Payload) = generatePhonePeChecksum($payload, $saltKey, $saltIndex, $endpoint);

    $headers = array(
        'Content-Type: application/json',
        'X-VERIFY: ' . $checksum
    );

    $requestBody = json_encode(array("request" => $base64Payload));

    $maxRetries = 5;
    $retryCount = 0;
    $retryDelay = 1; // in seconds

    while ($retryCount < $maxRetries) {
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $requestBody);

        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

        if ($response === false) {
            throw new Exception('Curl error: ' . curl_error($ch));
        }

        if ($httpCode == 429) {
            // If we get a 429 response, wait and retry
            $retryCount++;
            sleep($retryDelay);
            $retryDelay *= 2; // Exponential backoff
        } else {
            curl_close($ch);
            return json_decode($response, true);
        }

        curl_close($ch);
    }

    throw new Exception('Max retries exceeded');
}

try {
    $merchantId = "PGTESTPAYUAT";
    $merchantTransactionId = "MT7850590068188104";
    $merchantUserId = "MUID123";
    $amount = 10000; // Amount in paise
    $redirectUrl = "https://webhook.site/redirect-url";
    $callbackUrl = "https://webhook.site/callback-url";
    $mobileNumber = "9999999999";
    $saltKey = "099eb0cd-02cf-4e2a-8aca-3e6c6aff0399";
    $saltIndex = "1";

    $response = initiatePhonePePayment($merchantId, $merchantTransactionId, $merchantUserId, $amount, $redirectUrl, $callbackUrl, $mobileNumber, $saltKey, $saltIndex);

    json_decode($response);
} catch (Exception $e) {
    echo 'Error: ' . $e->getMessage();
}

?>
