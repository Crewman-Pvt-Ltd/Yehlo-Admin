<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Payment Success</title>
    <!-- BOOTSTRAP -->
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" rel="stylesheet" />
    <!-- Styles -->
    <style>
        body {
            background: #f7f7f7;
        }
        .success-box {
            max-width: 500px;
            margin: auto;
            padding: 50px;
            background: #ffffff;
            border: 10px solid #f2f2f2;
            margin-top: 100px;
        }
        h1, p {
            text-align: center;
        }
    </style>
</head>
<body>
<div class="success-box">
    <h4>PaymentSuccessful!</h4>
    {{-- {{  dd($payment_details) }} --}}
    <p>Transaction ID: {{ $payment_details['transactionId']}}</p>
    <p>Provider Reference ID: {{ $payment_details['providerReferenceId'] }}</p>
    <p>Merchant Order ID: {{ $payment_details['merchantOrderId'] }}</p>
</div>
</body>
</html>
