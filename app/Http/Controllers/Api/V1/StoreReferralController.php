<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\RefferalCode;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;


class StoreReferralController extends Controller
{
    public function create(Request $request)
    {
        // Validation rules
        $validator = Validator::make($request->all(), [
            'referral_prefix' => 'required|string',
        ]);

        // If validation fails, return a JSON response with errors
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation errors',
                'errors' => $validator->errors()
            ], 422);
        }

        // Generate a random alphanumeric code
        $randomCode = strtoupper(substr(bin2hex(random_bytes(3)), 0, 6));

        // Create the referral code by concatenating prefix and random code
        $referralCode = $request->get('referral_prefix') . $randomCode;

        // Create the new referral code
        try {
            $referral = new RefferalCode([
                'referral_code' => $referralCode,
            ]);

            $referral->save();

            // Return a success response
            return response()->json([
                'success' => true,
                'message' => 'Referral created successfully.',
                'data' => $referral
            ], 201);
        } catch (\Exception $e) {
            // Return a failure response if something goes wrong
            return response()->json([
                'success' => false,
                'message' => 'Failed to create referral code.',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
