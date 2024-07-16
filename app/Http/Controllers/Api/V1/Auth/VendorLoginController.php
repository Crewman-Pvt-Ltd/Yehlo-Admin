<?php

namespace App\Http\Controllers\Api\V1\Auth;

use App\CentralLogics\Helpers;
use App\Models\BusinessSetting;
use App\Http\Controllers\Controller;
use App\Models\Vendor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use App\Models\Zone;
use App\Models\Store;
use App\CentralLogics\StoreLogic;
use App\Models\Admin;
use App\Models\Translation;
use App\Models\VendorEmployee;
use MatanYadaev\EloquentSpatial\Objects\Point;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\Rules\Password;
use App\Models\Plan;
use App\Models\PlanPurchaseRequest;
// use App\Http\Controllers\PhonePeController;
use App\Library\Payment as PaymentInfo;
use App\Library\Receiver;
use App\Traits\Payment;
use App\Library\VendorDetails;


class VendorLoginController extends Controller
{
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required',
            'password' => 'required|min:6'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => Helpers::error_processor($validator)], 403);
        }
        $vendor_type = $request->vendor_type;

        $data = [
            'email' => $request->email,
            'password' => $request->password
        ];
        // dd($request);
        if ($vendor_type == 'owner') {

            if (auth('vendor')->attempt($data)) {
                $token = $this->genarate_token($request['email']);
                $vendor = Vendor::where(['email' => $request['email']])->first();
                if ($vendor->stores[0]->status == 0 && $vendor->status == 0) {
                    return response()->json([
                        'errors' => [
                            ['code' => 'auth-002', 'message' => translate('messages.Your_registration_is_not_approved_yet._You_can_login_once_admin_approved_the_request')]
                        ]
                    ], 403);
                } elseif ($vendor->stores[0]->status == 0 && $vendor->status == 1) {
                    return response()->json([
                        'errors' => [
                            ['code' => 'auth-002', 'message' => translate('messages.Your_account_is_suspended')]
                        ]
                    ], 403);
                }

                $vendor->auth_token = $token;
                $vendor->save();
                return response()->json(['token' => $token, 'zone_wise_topic' => $vendor->stores[0]->zone->store_wise_topic], 200);
            } else {
                $errors = [];
                array_push($errors, ['code' => 'auth-001', 'message' => translate('Credential_do_not_match,_please_try_again')]);
                return response()->json([
                    'errors' => $errors
                ], 401);
            }
        } elseif ($vendor_type == 'employee') {

            if (auth('vendor_employee')->attempt($data)) {
                $token = $this->genarate_token($request['email']);
                $vendor = VendorEmployee::where(['email' => $request['email']])->first();
                if ($vendor->stores[0]->status == 0 && $vendor->status == 0) {
                    return response()->json([
                        'errors' => [
                            ['code' => 'auth-002', 'message' => translate('messages.Your_registration_is_not_approved_yet._You_can_login_once_admin_approved_the_request')]
                        ]
                    ], 403);
                } elseif ($vendor->stores[0]->status == 0 && $vendor->status == 1) {
                    return response()->json([
                        'errors' => [
                            ['code' => 'auth-002', 'message' => translate('messages.Your_account_is_suspended')]
                        ]
                    ], 403);
                }
                $vendor->auth_token = $token;
                $vendor->save();
                $role = $vendor->role ? json_decode($vendor->role->modules) : [];
                return response()->json(['token' => $token, 'zone_wise_topic' => $vendor->store->zone->store_wise_topic, 'role' => $role], 200);
            } else {
                $errors = [];
                array_push($errors, ['code' => 'auth-001', 'message' => translate('Credential_do_not_match,_please_try_again')]);
                return response()->json([
                    'errors' => $errors
                ], 401);
            }
        } else {
            $errors = [];
            array_push($errors, ['code' => 'auth-001', 'message' => translate('Credential_do_not_match,_please_try_again')]);
            return response()->json([
                'errors' => $errors
            ], 401);
        }
    }

    private function genarate_token($email)
    {
        $token = Str::random(120);
        $is_available = Vendor::where('auth_token', $token)->where('email', '!=', $email)->count();
        if ($is_available) {
            $this->genarate_token($email);
        }
        return $token;
    }

    public function register(Request $request)
    {
        $status = BusinessSetting::where('key', 'toggle_store_registration')->first();
        if (!isset($status) || $status->value == '0') {
            return response()->json(['errors' => Helpers::error_processor('self-registration', translate('messages.store_self_registration_disabled'))]);
        }

        $validator = Validator::make($request->all(), [
            'f_name' => 'required|max:100',
            'l_name' => 'nullable|max:100',
            // 'name' => 'required|max:191',
            // 'address' => 'required|max:1000',
            'latitude' => 'required',
            'longitude' => 'required',
            'email' => 'required|unique:vendors',
            'phone' => 'required|regex:/^([0-9\s\-\+\(\)]*)$/|min:10|max:20|unique:vendors',
            'minimum_delivery_time' => 'required',
            'maximum_delivery_time' => 'required',
            'delivery_time_type' => 'required',
            'password' => ['required', Password::min(8)->mixedCase()->letters()->numbers()->symbols()->uncompromised()],
            'zone_id' => 'required',
            'module_id' => 'required',
            'logo' => 'required',
            'tax' => 'required'
        ], [
            'password.required' => translate('The password is required'),
            'password.min_length' => translate('The password must be at least :min characters long'),
            'password.mixed' => translate('The password must contain both uppercase and lowercase letters'),
            'password.letters' => translate('The password must contain letters'),
            'password.numbers' => translate('The password must contain numbers'),
            'password.symbols' => translate('The password must contain symbols'),
            'password.uncompromised' => translate('The password is compromised. Please choose a different one'),
        ]);

        if ($request->zone_id) {
            $zone = Zone::query()
                ->whereContains('coordinates', new Point($request->latitude, $request->longitude, POINT_SRID))
                ->where('id', $request->zone_id)
                ->first();
            if (!$zone) {
                $validator->getMessageBag()->add('latitude', translate('messages.coordinates_out_of_zone'));
                return response()->json(['errors' => Helpers::error_processor($validator)], 403);
            }
        }


        $data = json_decode($request->translations, true);

        if (count($data) < 1) {
            $validator->getMessageBag()->add('translations', translate('messages.Name and description in english is required'));
        }

        if ($validator->fails()) {
            return response()->json(['errors' => Helpers::error_processor($validator)], 403);
        }



        $vendor = new Vendor();
        $vendor->f_name = $request->f_name;
        $vendor->l_name = $request->l_name;
        $vendor->email = $request->email;
        $vendor->phone = $request->phone;
        $vendor->password = bcrypt($request->password);
        $vendor->status = null;

        if ($request->has('ref_code') && !empty($request->ref_code)) {
            // Check if the referral code exists in the database
            $vendor->ref_code = $this->generateReferralCode();
            $referrer = Vendor::where('ref_code', $request->ref_code)->first();
            if ($referrer) {

                $vendor->ref_code = $this->generateReferralCode();
                $vendor->ref_by = $referrer->ref_code;
            } else {
                // If the referrer does not exist, return an error response
                return response()->json(['error' => 'Invalid referral code'], 400);
            }
        } else {
            // Generate a new referral code for the new vendor if no referral code is provided
            $vendor->ref_code = $this->generateReferralCode();
        }

        // Save the vendor details

        $vendor->save();


        $purchase_request = new PlanPurchaseRequest();
        $purchase_request->vendor_id = $vendor->id;
        $purchase_request->plan_id = null;
        $purchase_request->is_purchased = 0;
        $purchase_request->mobile = $request->phone;
        $purchase_request->save();

        $store = new Store;
        $store->name = $data[0]['value'];
        $store->phone = $request->phone;
        $store->email = $request->email;
        $store->logo = Helpers::upload('store/', 'png', $request->file('logo'));
        $store->cover_photo = Helpers::upload('store/cover/', 'png', $request->file('cover_photo'));
        $store->address = $data[1]['value'];
        $store->latitude = $request->latitude;
        $store->longitude = $request->longitude;
        $store->vendor_id = $vendor->id;
        $store->zone_id = $request->zone_id;
        $store->tax = $request->tax;
        $store->delivery_time = $request->minimum_delivery_time . '-' . $request->maximum_delivery_time . ' ' . $request->delivery_time_type;
        $store->module_id = $request->module_id;
        $store->status = 0;

        $store->save();


        $store->module->increment('stores_count');
        if (config('module.' . $store->module->module_type)['always_open']) {
            StoreLogic::insert_schedule($store->id);
        }

        foreach ($data as $key => $i) {
            $data[$key]['translationable_type'] = 'App\Models\Store';
            $data[$key]['translationable_id'] = $store->id;
        }
        Translation::insert($data);

        try {
            $admin = Admin::where('role_id', 1)->first();
            $mail_status = Helpers::get_mail_status('registration_mail_status_store');
            if (config('mail.status') && $mail_status == '1') {
                Mail::to($request['email'])->send(new \App\Mail\VendorSelfRegistration('pending', $vendor->f_name . ' ' . $vendor->l_name));
            }
            $mail_status = Helpers::get_mail_status('store_registration_mail_status_admin');
            if (config('mail.status') && $mail_status == '1') {
                Mail::to($admin['email'])->send(new \App\Mail\StoreRegistration('pending', $vendor->f_name . ' ' . $vendor->l_name));
            }
        } catch (\Exception $ex) {
            info($ex->getMessage());
        }

        return response()->json(['message' => translate('messages.application_placed_successfully')], 200);
    }

    private function generateReferralCode()
    {
        $ref_code = "YEHLO_" . strtoupper(Str::random(8));
        while (Vendor::where('ref_code', $ref_code)->exists()) {
            $ref_code = "Yehlo-" . strtoupper(Str::random(8));
        }
        return $ref_code;
    }




    // public function getplandetails()
    // {

    //     $groupedPlans = Plan::all()
    //         ->orderBy('type')
    //         ->get()
    //         ->groupBy('type')
    //         ->map(function ($plans) {
    //             // $prices = $plans->pluck('price', 'plan_duration')->toArray();
    //             // $product_limit = $plans->pluck('product_limit', 'plan_duration')->toArray();

    //             return [
    //                 // 'title' => $plans->first()->name,
    //                 // 'prices' => $prices,
    //                 // 'product_limit' => $product_limit,
    //                 // 'description' => $plans->first()->description,
    //                 // 'image' => asset('storage/app/public/plan/' . $plans->first()->image),
    //                 // 'plan_duration' => implode(',', array_keys($prices)),
    //                 'plan' => $plans,
    //             ];
    //         });

    //     $jsonResponse = $groupedPlans->toJson();

    //     return $jsonResponse;
    // }

    // public function getPlanDetails($type = null)
    // {
    //     $query = Plan::query();

    //     // Filter by type if provided
    //     if (!is_null($type)) {
    //         $query->where('type', $type);
    //     }

    //     $groupedPlans = $query->orderBy('type')
    //         ->get()
    //         ->groupBy('type');


    //     $jsonResponse = $groupedPlans->toJson();

    //     return $jsonResponse;
    // }
    public function getPlanDetails($type = null)
    {
        $query = Plan::query();

        if (!is_null($type)) {
            $query->where('type', $type);
        }

        $plans = $query->orderBy('type')->get();

        $plans->each(function ($plan) {
            $plan->image_url = asset('storage/app/public/plan/' . $plan->image);
        });

        $groupedPlans = $plans->groupBy('type');

        $result = [];
        foreach ($groupedPlans as $type => $planGroup) {
            $result[$type] = $planGroup;
        }

        return response()->json($result);
    }




    // public function getplandetails()
    // {
    //     $groupedPlans = Plan::select('name', 'type', 'price', 'product_limit', 'plan_duration', 'description', 'image')
    //         ->orderBy('plan_duration')
    //         ->get()
    //         ->groupBy('plan_duration')
    //         ->map(function ($plans) {
    //             return $plans->map(function ($plan) {
    //                 return [
    //                     'title' => $plan->name,
    //                     'type' => $plan->type,
    //                     'price' => $plan->price,
    //                     'product_limit' => $plan->product_limit,
    //                     'description' => $plan->description,
    //                     'image' => asset('storage/app/public/plan/' . $plan->image),
    //                 ];
    //             });
    //         });

    //     $jsonResponse = $groupedPlans->toJson();

    //     return $jsonResponse;
    // }




    public function purchase_plan(Request $request)
    {

        $plan_id = $request->plan_id;
        $payment_method = $request->payment_method;

        $purchaseRequest = PlanPurchaseRequest::where('mobile', $request->mobile)->first();
        if (!$purchaseRequest) {
            return response()->json(['error' => 'Purchase request not found for the provided mobile number'], 404);
        }


        $payer_details = Vendor::find($purchaseRequest->vendor_id);


        $vendor_details = new VendorDetails(
            $payer_details->f_name,
            $payer_details->email,
            $payer_details->l_name,
            $payer_details->phone,
        );


        $plan_details = Plan::find($plan_id);
        $plan_amount = $plan_details->price;

        $currency = BusinessSetting::where(['key' => 'currency'])->first()->value;
        $additional_data = [
            'business_name' => BusinessSetting::where(['key' => 'business_name'])->first()?->value,
            'business_logo' => asset('storage/app/public/business') . '/' . BusinessSetting::where(['key' => 'logo'])->first()?->value
        ];


        $payment_info = new PaymentInfo(
            success_hook: 'wallet_success',
            failure_hook: 'wallet_failed',
            currency_code: $currency,
            payment_method: $payment_method,
            payment_platform: $request->payment_platform,
            payer_id: $payer_details->id,
            receiver_id: '100',
            additional_data: $additional_data,
            payment_amount: $plan_amount,
            external_redirect_link: $request->has('callback') ? $request['callback'] : session('callback'),
            attribute: 'plan_payments',
            attribute_id: $payer_details->id
        );



        $receiver_info = new Receiver('receiver_name', 'example.png');

        $redirect_link = Payment::generate_link($vendor_details, $payment_info, $receiver_info);

        // dd($redirect_link);

        $data = [
            'redirect_link' => $redirect_link,
        ];


        return response()->json($data, 200);
    }
}
