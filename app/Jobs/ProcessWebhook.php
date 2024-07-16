<?php
namespace App\Jobs;

use App\Models\CallbackUrl;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class ProcessWebhook implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $base64Response;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($base64Response)
    {
        $this->base64Response = $base64Response;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        // Decode the base64 encoded response
        $jsonResponse = base64_decode($this->base64Response);

        // Decode the JSON string to an associative array
        $data = json_decode($jsonResponse, true);

        // Store the decoded payload in the callback_url table
        CallbackUrl::create(['payload' => $jsonResponse]);

        // Check if the 'success' key exists in the decoded data
        if (isset($data['success']) && $data['success'] === true) {
            // Log based on the 'code' value
            switch ($data['code']) {
                case 'PAYMENT_SUCCESS':
                    Log::info("Success");
                    break;
                case 'PAYMENT_PENDING':
                    Log::info("Pending");
                    break;
                case 'PAYMENT_FAIL':
                    Log::info("Fail");
                    break;
                default:
                    // Handle unexpected 'code' values
                    Log::info("Unknown payment status");
                    break;
            }
        } else {
            // Handle the case where 'success' is false or not present
            Log::info("Invalid or unsuccessful response");
        }
    }
}
