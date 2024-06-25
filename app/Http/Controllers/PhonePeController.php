<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Vendor;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\Controller;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use App\Models\PaymentRequest;
use App\Http\Controllers\Exception;
use App\Traits\Processor;
use Illuminate\Support\Facades\Redirect;

class PhonePeController extends Controller
{
    use Processor;
    private $config_values;
    private $base_url;

    private PaymentRequest $payment;

    public function __construct(PaymentRequest $payment)
    {
        $config = $this->payment_config('phonepay', 'payment_config');
        if (!is_null($config) && $config->mode == 'live') {
            $this->config_values = json_decode($config->live_values);
        } elseif (!is_null($config) && $config->mode == 'test') {
            $this->config_values = json_decode($config->test_values);
        }

        if ($config) {
            $this->base_url = ($config->mode == 'test') ? 'https://api-preprod.phonepe.com/apis/pg-sandbox/pg/v1/pay' : 'https://api.phonepe.com/apis/hermes/pg/v1/pay';
        }
        $this->payment = $payment;
    }



    public function payment(Request $request)
    {
        // Validate the request parameters
        $validator = Validator::make($request->all(), [
            'payment_id' => 'required|uuid'
        ]);

        // Check if validation fails
        if ($validator->fails()) {
            return response()->json($this->response_formatter(GATEWAYS_DEFAULT_400, null, $this->error_processor($validator)), 400);
        }

        // Retrieve payment data based on the provided payment_id
        $data = $this->payment::where(['id' => $request->input('payment_id'), 'is_paid' => 0])->first();

        // Check if payment data exists
        if (!$data) {
            return response()->json($this->response_formatter(GATEWAYS_DEFAULT_204), 200);
        }


        // Extract business name from additional data or set a default value
        $business_name = optional(json_decode($data->additional_data))->business_name ?? "my_business";

        // Get system configuration values
        $system_config = $this->config_values;

        // Retrieve payer details
        $payer_information = json_decode($data->payer_information);
        if (isset($payer_information->type)) {
            $payer_details = Vendor::where("id", $data->payer_id)->first();
        } else {
            $payer_details = User::where("id", $data->payer_id)->first();
        }
        // dd($system_config);

        $phone_number = $payer_details->phone ?? '';

        // Remove leading '+91' from phone number if present
        if (strpos($phone_number, '+91') === 0) {
            $phone_number = substr($phone_number, 3);
        }

        $paymentDetails = [
            'merchantOrderId' => "orderID".rand(100, 99999),
            'requestId' => $data->id
        ];

        $encryptedData = base64_encode(json_encode($paymentDetails));

        $redirect_url = route('phonepe.confirm', ['encryptedData' => $encryptedData]);


        // Prepare payload for PhonePe API
        // $payload = [
        //     "merchantId" => $system_config->merchant_id,
        //     "merchantTransactionId" => "yehlo" . rand(1000, 99999999999),
        //     "merchantUserId" => $payer_details->id,
        //     // "amount" => $data->payment_amount * 100,
        //     "amount" => 1,
        //     "redirectUrl" => $system_config->redirect_url,
        //     "redirectMode" => "REDIRECT",
        //     "callbackUrl" => $system_config->callbackUrl,
        //     "mobileNumber" => $phone_number,
        //     "paymentInstrument" => ["type" => "PAY_PAGE"],
        // ];




        $payload = [
            "merchantId" => "PGTESTPAYUAT86",
            "merchantTransactionId" => "yehlo" . rand(1000, 99999999999),
            "merchantUserId" => $payer_details->id,
            // "amount" => $data->payment_amount * 100,
            "amount" => 1,
            "redirectUrl" => $redirect_url,
            "redirectMode" => "POST",
            "callbackUrl" => $redirect_url,
            "mobileNumber" => "8171619719   ",
            "paymentInstrument" => ["type" => "PAY_PAGE"],
        ];

        $encodedPayload = base64_encode(json_encode($payload));
        $jsonData = json_encode(["request" => $encodedPayload]);

        // $saltKey = $system_config->salt_key;
        $saltKey = "96434309-7796-489d-8924-ab56988a6076";

        $salt_index = $system_config->salt_index;
        // Generate checksum
        $checksum = hash('sha256', $encodedPayload . "/pg/v1/pay" . $saltKey) . "###" . $salt_index;

        if ($checksum) {
            $headers = [
                'Content-Type: application/json',
                'X-VERIFY: ' . $checksum,
            ];

            $ch = curl_init();

            curl_setopt($ch, CURLOPT_URL, $this->base_url);
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

                return Redirect::away($paymentUrl);
            } else {

                return response()->json($this->response_formatter(GATEWAYS_DEFAULT_400), 400);
            }
        } else {
            return response()->json($this->response_formatter(GATEWAYS_DEFAULT_204), 200);
        }
    }
    public function success(Request $request, $encryptedData)
    {

        $validatedData = $request->validate([
            'code' => 'required|string',
            'transactionId' => 'nullable|string',
            'merchantId' => 'nullable|string',
            'providerReferenceId' => 'nullable|string',
            'merchantOrderId' => 'nullable|string',
            'checksum' => 'nullable|string',
        ], [
            'code.required' => 'The payment status code is required.',
            'code.string' => 'The payment status code must be a string.',
            'transactionId.string' => 'The transaction ID must be a string.',
            'merchantId.string' => 'The merchant ID must be a string.',
            'providerReferenceId.string' => 'The provider reference ID must be a string.',
            'merchantOrderId.string' => 'The merchant order ID must be a string.',
            'checksum.string' => 'The checksum must be a string.',
        ]);

        $decryptedData = json_decode(base64_decode($encryptedData));

        $merchantOrderId = $decryptedData->merchantOrderId;
        $requestId = $decryptedData->requestId;


        if ($request->code == 'PAYMENT_SUCCESS') {
            $transactionId = $request->transactionId ?? 'N/A';
            $merchantId = $request->merchantId ?? 'N/A';
            $providerReferenceId = $request->providerReferenceId ?? 'N/A';
            $merchantOrderId = $request->has('merchantOrderId') ? $request->merchantOrderId : 'N/A';
            $checksum = $request->checksum ?? 'N/A';
            $status = $request->code;

            $data = [
                'providerReferenceId' => $providerReferenceId,
                'checksum' => $checksum,
            ];

            if ($merchantOrderId != 'N/A') {
                $data['merchantOrderId'] = $merchantOrderId;
            }

            $update = $this->payment::where(['id' => $requestId])->update([
                'payment_method' => 'phonepe',
                'is_paid' => 1,
                'transaction_id' => $data['providerReferenceId'],
            ]);
            // dd($merchantOrderId);
            $get_data = $this->payment::where(['id' => $requestId])->first();
            $payerInformation = json_decode($get_data->payer_information);
            $payment_details = [
                'providerReferenceId' => $providerReferenceId,
                'transactionId' => $merchantId,
                'merchantOrderId' => $merchantOrderId,
                'payer_details' => [
                    'payer_details_name' => $payerInformation->name,
                    'payer_details_email' => $payerInformation->email,
                    'payer_details_number' => $payerInformation->phone
                ]

            ];
            // dd($payerInformation);


            return view('phonepe-success', compact('payment_details'));
        } else {
            // // HANDLE YOUR ERROR MESSAGE HERE
            // return "cdkvjdkF";
        }
    }


    public function checkTransactionStatus($merchantId, $transactionId)
    {
        $system_config = $this->config_values;

        $checksum = hash('sha256', "/v3/transaction/{$merchantId}/{$transactionId}/status" . $system_config->salt_key) . "###" . $system_config->salt_index;

        if ($checksum) {
            // Set request headers
            $headers = [
                'Content-Type: application/json',
                'X-VERIFY: ' . $checksum,
                'X-CLIENT-ID: ' . $merchantId,
            ];

            // Initialize cURL session
            $ch = curl_init();

            // Set cURL options
            curl_setopt($ch, CURLOPT_URL, "https://apps-uat.phonepe.com/v3/transaction/{$merchantId}/{$transactionId}/status");
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

            // Execute cURL request
            $response = curl_exec($ch);

            // Check for cURL errors
            if (curl_error($ch)) {
                echo 'Error:' . curl_error($ch);
            }

            // Close cURL session
            curl_close($ch);

            // Decode JSON response
            $response = json_decode($response);

            // Check if the request was successful
            if ($response->success) {
                // Return the transaction status
                return $response->code;
            } else {
                // Handle error response
                return "Error: " . $response->code;
            }
        } else {
            return "Error: Unable to generate checksum";
        }
    }


    public function cancel(Request $request)
    {
        // dd("cancel");
    }

    // private function computeSHA256($data)
    // {
    //     return hash('sha256', $data);
    // }

    // // Function to generate X-Verify header
    // private  function generateXVerify($payload, $endpoint, $saltKey, $saltIndex)
    // {
    //     $checksumString = $payload . $endpoint . $saltKey;
    //     $checksum = $this->computeSHA256($checksumString);
    //     return $checksum . '###' . $saltIndex;
    // }


}
