<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\Zone;
use App\Models\Order;
use App\Models\Module;
use App\Models\Currency;
use App\Models\DMVehicle;
use App\Models\DataSetting;
use App\Models\SocialMedia;
use App\Traits\AddonHelper;
use Illuminate\Http\Request;
use App\CentralLogics\Helpers;
use App\Models\BusinessSetting;
use App\Models\OfflinePayments;
use App\Models\ReactTestimonial;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\OfflinePaymentMethod;
use Illuminate\Support\Facades\Http;
use App\Models\FlutterSpecialCriteria;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Validator;
use MatanYadaev\EloquentSpatial\Objects\Point;
use App\Models\Store;
use App\Models\AdminPromotionalBanner;
use App\Models\DeliveryPromotion;
use App\Models\DeliveryFaq;


use App\Models\AdminFeature;

class ConfigController extends Controller
{
    private $map_api_key;

    use AddonHelper;

    function __construct()
    {
        $map_api_key_server = BusinessSetting::where(['key' => 'map_api_key_server'])->first();
        $map_api_key_server = $map_api_key_server ? $map_api_key_server->value : null;
        $this->map_api_key = $map_api_key_server;
    }

    public function configuration()
    {
        $key = ['currency_code', 'cash_on_delivery', 'digital_payment', 'default_location', 'free_delivery_over', 'business_name', 'logo', 'address', 'phone', 'email_address', 'country', 'currency_symbol_position', 'app_minimum_version_android', 'app_url_android', 'app_minimum_version_ios', 'app_url_ios', 'app_url_android_store', 'app_minimum_version_ios_store', 'app_url_ios_store', 'app_minimum_version_ios_deliveryman', 'app_url_ios_deliveryman', 'app_minimum_version_android_deliveryman', 'app_minimum_version_android_store', 'app_url_android_deliveryman', 'customer_verification', 'schedule_order', 'order_delivery_verification', 'per_km_shipping_charge', 'minimum_shipping_charge', 'show_dm_earning', 'canceled_by_deliveryman', 'canceled_by_store', 'timeformat', 'toggle_veg_non_veg', 'toggle_dm_registration', 'toggle_store_registration', 'schedule_order_slot_duration', 'parcel_per_km_shipping_charge', 'parcel_minimum_shipping_charge', 'web_app_landing_page_settings', 'footer_text', 'landing_page_links', 'loyalty_point_exchange_rate', 'loyalty_point_item_purchase_point', 'loyalty_point_status', 'loyalty_point_minimum_point', 'wallet_status', 'dm_tips_status', 'ref_earning_status', 'ref_earning_exchange_rate', 'refund_active_status', 'refund', 'cancelation', 'shipping_policy', 'prescription_order_status', 'tax_included', 'icon', 'cookies_text', 'home_delivery_status', 'takeaway_status', 'additional_charge', 'additional_charge_status', 'additional_charge_name', 'dm_picture_upload_status', 'partial_payment_status', 'partial_payment_method', 'add_fund_status', 'offline_payment_status', 'websocket_url', 'websocket_port', 'websocket_status', 'guest_checkout_status', 'disbursement_type', 'restaurant_disbursement_waiting_time', 'dm_disbursement_waiting_time', 'min_amount_to_pay_store', 'min_amount_to_pay_dm'];

        $settings =  array_column(BusinessSetting::whereIn('key', $key)->get()->toArray(), 'value', 'key');

        $DataSetting =  DataSetting::where('type', 'flutter_landing_page')->where('key', 'download_user_app_links')->pluck('value', 'key')->toArray();
        $DataSetting =  isset($DataSetting['download_user_app_links']) ? json_decode($DataSetting['download_user_app_links'], true) : [];
        $landing_page_links =  isset($settings['landing_page_links']) ? json_decode($settings['landing_page_links'], true) : [];
        $landing_page_links['app_url_android_status'] =  data_get($DataSetting, 'playstore_url_status', null);
        $landing_page_links['app_url_android'] = data_get($DataSetting, 'playstore_url', null);
        $landing_page_links['app_url_ios_status'] =  data_get($DataSetting, 'apple_store_url_status', null);
        $landing_page_links['app_url_ios'] = data_get($DataSetting, 'apple_store_url', null);


        $currency_symbol = Currency::where(['currency_code' => Helpers::currency_code()])->first()->currency_symbol;
        $cod = json_decode($settings['cash_on_delivery'], true);
        $digital_payment = json_decode($settings['digital_payment'], true);
        $default_location = isset($settings['default_location']) ? json_decode($settings['default_location'], true) : 0;
        $free_delivery_over = $settings['free_delivery_over'];
        $free_delivery_over = isset($free_delivery_over) ? (float)$free_delivery_over : $free_delivery_over;
        $additional_charge = isset($settings['additional_charge']) ? (float)$settings['additional_charge'] : 0;
        $module = null;
        if (Module::active()->count() == 1) {
            $module = Module::active()->first();
        }
        $languages = Helpers::get_business_settings('language');
        $lang_array = [];
        foreach ($languages as $language) {
            array_push($lang_array, [
                'key' => $language,
                'value' => Helpers::get_language_name($language)
            ]);
        }
        $system_languages = Helpers::get_business_settings('system_language');
        $sys_lang_array = [];
        foreach ($system_languages as $language) {
            array_push($sys_lang_array, [
                'key' => $language['code'],
                'value' => Helpers::get_language_name($language['code']),
                'direction' => $language['direction'],
                'default' => $language['default']
            ]);
        }
        $social_login = [];
        foreach (Helpers::get_business_settings('social_login') as $social) {
            $config = [
                'login_medium' => $social['login_medium'],
                'status' => (bool)$social['status']
            ];
            array_push($social_login, $config);
        }
        $apple_login = [];
        $apples = Helpers::get_business_settings('apple_login');
        if (isset($apples)) {
            foreach (Helpers::get_business_settings('apple_login') as $apple) {
                $config = [
                    'login_medium' => $apple['login_medium'],
                    'status' => (bool)$apple['status'],
                    'client_id' => $apple['client_id']
                ];
                array_push($apple_login, $config);
            }
        }

        //addon settings publish status
        $published_status = 0; // Set a default value
        $payment_published_status = config('get_payment_publish_status');
        if (isset($payment_published_status[0]['is_published'])) {
            $published_status = $payment_published_status[0]['is_published'];
        }

        $active_addon_payment_lists = $published_status == 1 ? $this->getPaymentMethods() : $this->getDefaultPaymentMethods();

        $digital_payment_infos = array(
            'digital_payment' => (bool)($digital_payment['status'] == 1 ? true : false),
            'plugin_payment_gateways' =>  (bool)($published_status ? true : false),
            'default_payment_gateways' =>  (bool)($published_status ? false : true)
        );


        // dd(config('module.grocery'));


        return response()->json([
            'business_name' => $settings['business_name'],
            // 'business_open_time' => $settings['business_open_time'],
            // 'business_close_time' => $settings['business_close_time'],
            'logo' => $settings['logo'],
            'address' => $settings['address'],
            'phone' => $settings['phone'],
            'email' => $settings['email_address'],
            // 'store_location_coverage' => Branch::where(['id'=>1])->first(['longitude','latitude','coverage']),
            // 'minimum_order_value' => (float)$settings['minimum_order_value'],
            'base_urls' => [
                'item_image_url' => asset('storage/app/public/product'),
                'refund_image_url' => asset('storage/app/public/refund'),
                'customer_image_url' => asset('storage/app/public/profile'),
                'banner_image_url' => asset('storage/app/public/banner'),
                'category_image_url' => asset('storage/app/public/category'),
                'review_image_url' => asset('storage/app/public/review'),
                'notification_image_url' => asset('storage/app/public/notification'),
                'store_image_url' => asset('storage/app/public/store'),
                'vendor_image_url' => asset('storage/app/public/vendor'),
                'store_cover_photo_url' => asset('storage/app/public/store/cover'),
                'delivery_man_image_url' => asset('storage/app/public/delivery-man'),
                'chat_image_url' => asset('storage/app/public/conversation'),
                'campaign_image_url' => asset('storage/app/public/campaign'),
                'business_logo_url' => asset('storage/app/public/business'),
                'order_attachment_url' => asset('storage/app/public/order'),
                'module_image_url' => asset('storage/app/public/module'),
                'parcel_category_image_url' => asset('storage/app/public/parcel_category'),
                'landing_page_image_url' => asset('public/assets/landing/image'),
                'react_landing_page_images' => asset('storage/app/public/react_landing'),
                'react_landing_page_feature_images' => asset('storage/app/public/react_landing/feature'),
                'gateway_image_url' => asset('storage/app/public/payment_modules/gateway_image'),
            ],
            'country' => $settings['country'],
            'default_location' => ['lat' => $default_location ? $default_location['lat'] : '23.757989', 'lng' => $default_location ? $default_location['lng'] : '90.360587'],
            'currency_symbol' => $currency_symbol,
            'currency_symbol_direction' => $settings['currency_symbol_position'],
            'app_minimum_version_android' => (float)$settings['app_minimum_version_android'],
            'app_url_android' => $settings['app_url_android'],
            'app_url_ios' => $settings['app_url_ios'],
            'app_minimum_version_ios' => (float)$settings['app_minimum_version_ios'],
            'app_minimum_version_android_store' => (float)(isset($settings['app_minimum_version_android_store']) ? $settings['app_minimum_version_android_store'] : 0),
            'app_url_android_store' => (isset($settings['app_url_android_store']) ? $settings['app_url_android_store'] : null),
            'app_minimum_version_ios_store' => (float)(isset($settings['app_minimum_version_ios_store']) ? $settings['app_minimum_version_ios_store'] : 0),
            'app_url_ios_store' => (isset($settings['app_url_ios_store']) ? $settings['app_url_ios_store'] : null),
            'app_minimum_version_android_deliveryman' => (float)(isset($settings['app_minimum_version_android_deliveryman']) ? $settings['app_minimum_version_android_deliveryman'] : 0),
            'app_url_android_deliveryman' => (isset($settings['app_url_android_deliveryman']) ? $settings['app_url_android_deliveryman'] : null),
            'app_minimum_version_ios_deliveryman' => (float)(isset($settings['app_minimum_version_ios_deliveryman']) ? $settings['app_minimum_version_ios_deliveryman'] : 0),
            'app_url_ios_deliveryman' => (isset($settings['app_url_ios_deliveryman']) ? $settings['app_url_ios_deliveryman'] : null),
            'customer_verification' => (bool)$settings['customer_verification'],
            'prescription_order_status' => isset($settings['prescription_order_status']) ? (bool)$settings['prescription_order_status'] : false,
            'schedule_order' => (bool)$settings['schedule_order'],
            'order_delivery_verification' => (bool)$settings['order_delivery_verification'],
            'cash_on_delivery' => (bool)($cod['status'] == 1 ? true : false),
            'digital_payment' => (bool)($digital_payment['status'] == 1 ? true : false),
            'digital_payment_info' => $digital_payment_infos,
            'per_km_shipping_charge' => (float)$settings['per_km_shipping_charge'],
            'minimum_shipping_charge' => (float)$settings['minimum_shipping_charge'],
            'free_delivery_over' => $free_delivery_over,
            'demo' => (bool)(env('APP_MODE') == 'demo' ? true : false),
            'maintenance_mode' => (bool)Helpers::get_business_settings('maintenance_mode') ?? 0,
            'order_confirmation_model' => config('order_confirmation_model'),
            'show_dm_earning' => (bool)$settings['show_dm_earning'],
            'canceled_by_deliveryman' => (bool)$settings['canceled_by_deliveryman'],
            'canceled_by_store' => (bool)$settings['canceled_by_store'],
            'timeformat' => (string)$settings['timeformat'],
            'language' => $lang_array,
            'sys_language' => $sys_lang_array,
            'social_login' => $social_login,
            'apple_login' => $apple_login,
            'toggle_veg_non_veg' => (bool)$settings['toggle_veg_non_veg'],
            'toggle_dm_registration' => (bool)$settings['toggle_dm_registration'],
            'toggle_store_registration' => (bool)$settings['toggle_store_registration'],
            'refund_active_status' => (bool)$settings['refund_active_status'],
            'schedule_order_slot_duration' => (int)$settings['schedule_order_slot_duration'],
            'digit_after_decimal_point' => (int)config('round_up_to_digit'),
            'module_config' => config('module'),
            'module' => $module,
            'parcel_per_km_shipping_charge' => (float)$settings['parcel_per_km_shipping_charge'],
            'parcel_minimum_shipping_charge' => (float)$settings['parcel_minimum_shipping_charge'],
            'landing_page_settings' => isset($settings['web_app_landing_page_settings']) ? json_decode($settings['web_app_landing_page_settings'], true) : null,
            'social_media' => SocialMedia::active()->get()->toArray(),
            'footer_text' => isset($settings['footer_text']) ? $settings['footer_text'] : '',
            'cookies_text' => isset($settings['cookies_text']) ? $settings['cookies_text'] : '',
            'fav_icon' => $settings['icon'],
            'landing_page_links' => $landing_page_links,
            //Added Business Setting
            'dm_tips_status' => (int)(isset($settings['dm_tips_status']) ? $settings['dm_tips_status'] : 0),
            'loyalty_point_exchange_rate' => (int)(isset($settings['loyalty_point_item_purchase_point']) ? $settings['loyalty_point_exchange_rate'] : 0),
            'loyalty_point_item_purchase_point' => (float)(isset($settings['loyalty_point_item_purchase_point']) ? $settings['loyalty_point_item_purchase_point'] : 0.0),
            'loyalty_point_status' => (int)(isset($settings['loyalty_point_status']) ? $settings['loyalty_point_status'] : 0),
            'customer_wallet_status' => (int)(isset($settings['wallet_status']) ? $settings['wallet_status'] : 0),
            'ref_earning_status' => (int)(isset($settings['ref_earning_status']) ? $settings['ref_earning_status'] : 0),
            'ref_earning_exchange_rate' => (float)(isset($settings['ref_earning_exchange_rate']) ? $settings['ref_earning_exchange_rate'] : 0),
            'refund_policy' => (int)(self::get_settings_status('refund_policy_status')),
            'cancelation_policy' => (int)(self::get_settings_status('cancellation_policy_status')),
            'shipping_policy' => (int)(self::get_settings_status('shipping_policy_status')),
            'loyalty_point_minimum_point' => (int)(isset($settings['loyalty_point_minimum_point']) ? $settings['loyalty_point_minimum_point'] : 0),
            'tax_included' => (int)(isset($settings['tax_included']) ? $settings['tax_included'] : 0),
            'home_delivery_status' => (int)(isset($settings['home_delivery_status']) ? $settings['home_delivery_status'] : 0),
            'takeaway_status' => (int)(isset($settings['takeaway_status']) ? $settings['takeaway_status'] : 0),
            'active_payment_method_list' => $active_addon_payment_lists,
            'additional_charge_status' => (int)(isset($settings['additional_charge_status']) ? $settings['additional_charge_status'] : 0),
            'additional_charge_name' => (isset($settings['additional_charge_name']) ? $settings['additional_charge_name'] : 'Service Charge'),
            'additional_charge' => $additional_charge,
            'partial_payment_status' => (int)(isset($settings['partial_payment_status']) ? $settings['partial_payment_status'] : 0),
            'partial_payment_method' => (isset($settings['partial_payment_method']) ? $settings['partial_payment_method'] : ''),
            'dm_picture_upload_status' => (int)(isset($settings['dm_picture_upload_status']) ? $settings['dm_picture_upload_status'] : 0),
            'add_fund_status' => (int)(isset($settings['add_fund_status']) ? $settings['add_fund_status'] : 0),
            'offline_payment_status' => (int)(isset($settings['offline_payment_status']) ? $settings['offline_payment_status'] : 0),
            'websocket_status' => (int) (isset($settings['websocket_status']) ? $settings['websocket_status'] : 0),
            'websocket_url' => (isset($settings['websocket_url']) ? $settings['websocket_url'] : ''),
            'websocket_port' => (int)(isset($settings['websocket_port']) ? $settings['websocket_port'] : 6001),
            'websocket_key' => env('PUSHER_APP_KEY'),
            'guest_checkout_status' => (int)(isset($settings['guest_checkout_status']) ? $settings['guest_checkout_status'] : 0),
            'disbursement_type' => (string)(isset($settings['disbursement_type']) ? $settings['disbursement_type'] : 'manual'),
            'restaurant_disbursement_waiting_time' => (int)(isset($settings['restaurant_disbursement_waiting_time']) ? $settings['restaurant_disbursement_waiting_time'] : 0),
            'dm_disbursement_waiting_time' => (int)(isset($settings['dm_disbursement_waiting_time']) ? $settings['dm_disbursement_waiting_time'] : 0),
            'min_amount_to_pay_store' => (float)(isset($settings['min_amount_to_pay_store']) ? $settings['min_amount_to_pay_store'] : 0),
            'min_amount_to_pay_dm' => (float)(isset($settings['min_amount_to_pay_dm']) ? $settings['min_amount_to_pay_dm'] : 0),
        ]);
    }

    public static function get_settings_status($name)
    {
        $data = DataSetting::where(['key' => $name])->first()?->value;
        return $data ?? 0;
    }

    public function get_zone(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'lat' => 'required',
            'lng' => 'required',
        ]);

        if ($validator->errors()->count() > 0) {
            return response()->json(['errors' => Helpers::error_processor($validator)], 403);
        }

        $zones = Zone::with('modules')->whereContains('coordinates', new Point($request->lat, $request->lng, POINT_SRID))->latest()->get(['id', 'status', 'cash_on_delivery', 'digital_payment', 'offline_payment', 'increased_delivery_fee', 'increased_delivery_fee_status', 'increase_delivery_charge_message']);

        if (count($zones) < 1) {
            return response()->json([
                'errors' => [
                    ['code' => 'coordinates', 'message' => translate('messages.service_not_available_in_this_area')]
                ]
            ], 404);
        }
        $data = array_filter($zones->toArray(), function ($zone) {
            if ($zone['status'] == 1) {
                return $zone;
            }
        });


        if (count($data) > 0) {
            return response()->json(['zone_id' => json_encode(array_column($data, 'id')), 'zone_data' => array_values($data)], 200);
        }

        return response()->json([
            'errors' => [
                ['code' => 'coordinates', 'message' => translate('messages.we_are_temporarily_unavailable_in_this_area')]
            ]
        ], 403);
    }

    public function place_api_autocomplete(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'search_text' => 'required',
        ]);

        if ($validator->errors()->count() > 0) {
            return response()->json(['errors' => Helpers::error_processor($validator)], 403);
        }

        $response = Http::get('https://maps.googleapis.com/maps/api/place/autocomplete/json?input=' . $request['search_text'] . '&key=' . $this->map_api_key . '&language=' . app()->getLocale());

        // dd($response);
        return $response->json();
    }


    public function distance_api(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'origin_lat' => 'required',
            'origin_lng' => 'required',
            'destination_lat' => 'required',
            'destination_lng' => 'required',
        ]);

        if ($validator->errors()->count() > 0) {
            return response()->json(['errors' => Helpers::error_processor($validator)], 403);
        }
        $response = Http::get('https://maps.googleapis.com/maps/api/distancematrix/json?origins=' . $request['origin_lat'] . ',' . $request['origin_lng'] . '&destinations=' . $request['destination_lat'] . ',' . $request['destination_lng'] . '&key=' . $this->map_api_key . '&mode=walking');
        return $response->json();
    }

    // public function distance_api_range(Request $request)
    // {
    //     $validator = Validator::make($request->all(), [
    //         'origin_lat' => 'required',
    //         'origin_lng' => 'required',
    //         'range' => 'required',
    //     ]);

    //     if ($validator->fails()) {
    //         return response()->json(['errors' => $validator->errors()], 403);
    //     }

    //     $user_lat = $request->input('origin_lat');
    //     $user_lng = $request->input('origin_lng');
    //     $range = $request->input('range');

    //     // Retrieve all stores
    //     $stores = Store::all();
    //     // dd($stores);
    //     $nearbyStores = [];

    //     foreach ($stores as $store) {
    //         $response = Http::get('https://maps.googleapis.com/maps/api/distancematrix/json', [
    //             'origins' => $user_lat . ',' . $user_lng,
    //             'destinations' => $store->latitude . ',' . $store->longitude,
    //             'key' => $this->map_api_key,
    //             'mode' => 'walking'
    //         ]);

    //         $distanceData = $response->json();

    //         if ($response->ok() && $distanceData['status'] === 'OK') {
    //             $distance = $distanceData['rows'][0]['elements'][0]['distance']['value'] / 1000; // distance in km

    //             if ($distance <= $range) { // Check if distance is within 10 km
    //                 $store->distance = $distance; // Add distance to the store object
    //                 $nearbyStores[] = $store;
    //             }
    //         }
    //     }

    //     return response()->json(['stores' => $nearbyStores]);
    // }

    public function distance_api_range(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'origin_lat' => 'required',
            'origin_lng' => 'required',
            'range' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 403);
        }

        $user_lat = $request->input('origin_lat');
        $user_lng = $request->input('origin_lng');
        $range = $request->input('range');

        // Retrieve all stores
        $stores = Store::all();
        $nearbyStores = [];

        foreach ($stores as $store) {
            $response = Http::get('https://maps.googleapis.com/maps/api/distancematrix/json', [
                'origins' => $user_lat . ',' . $user_lng,
                'destinations' => $store->latitude . ',' . $store->longitude,
                'key' => $this->map_api_key,
                'mode' => 'walking'
            ]);

            $distanceData = $response->json();

            if ($response->ok() && $distanceData['status'] === 'OK') {
                $distance = $distanceData['rows'][0]['elements'][0]['distance']['value'] / 1000; // distance in km

                if ($distance <= $range) { // Check if distance is within the specified range
                    $store->distance = $distance; // Add distance to the store object
                    $nearbyStores[] = $store;
                }
            }
        }


        // Sort nearbyStores array by distance in ascending order
        usort($nearbyStores, function ($a, $b) {
            return $a->distance <=> $b->distance;
        });

        return response()->json(['stores' => $nearbyStores]);
    }




    public function place_api_details(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'placeid' => 'required',
        ]);

        if ($validator->errors()->count() > 0) {
            return response()->json(['errors' => Helpers::error_processor($validator)], 403);
        }
        $response = Http::get('https://maps.googleapis.com/maps/api/place/details/json?placeid=' . $request['placeid'] . '&key=' . $this->map_api_key);
        return $response->json();
    }

    public function geocode_api(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'lat' => 'required',
            'lng' => 'required',
        ]);

        if ($validator->errors()->count() > 0) {
            return response()->json(['errors' => Helpers::error_processor($validator)], 403);
        }
        $response = Http::get('https://maps.googleapis.com/maps/api/geocode/json?latlng=' . $request->lat . ',' . $request->lng . '&key=' . $this->map_api_key);
        return $response->json();
    }

    public function landing_page()
    {
        $key = [
            'react_header_banner', 'banner_section_full', 'banner_section_half', 'footer_logo', 'app_section_image',
            'react_feature', 'app_download_button', 'discount_banner', 'landing_page_links', 'delivery_service_section', 'hero_section', 'download_app_section', 'landing_page_text'
        ];
        $settings =  array_column(BusinessSetting::whereIn('key', $key)->get()->toArray(), 'value', 'key');
        return  response()->json(
            [
                'react_header_banner' => (isset($settings['react_header_banner']))  ? $settings['react_header_banner'] : null,
                'app_section_image' => (isset($settings['app_section_image'])) ? $settings['app_section_image']  : null,
                'footer_logo' => (isset($settings['footer_logo'])) ? $settings['footer_logo'] : null,
                'banner_section_full' => (isset($settings['banner_section_full']))  ? json_decode($settings['banner_section_full'], true) : null,
                'banner_section_half' => (isset($settings['banner_section_half']))  ? json_decode($settings['banner_section_half'], true) : [],
                'react_feature' => (isset($settings['react_feature'])) ? json_decode($settings['react_feature'], true) : [],
                'app_download_button' => (isset($settings['app_download_button'])) ? json_decode($settings['app_download_button'], true) : [],
                'discount_banner' => (isset($settings['discount_banner'])) ? json_decode($settings['discount_banner'], true) : null,
                'landing_page_links' => (isset($settings['landing_page_links'])) ? json_decode($settings['landing_page_links'], true) : null,
                'hero_section' => (isset($settings['hero_section'])) ? json_decode($settings['hero_section'], true) : null,
                'delivery_service_section' => (isset($settings['delivery_service_section'])) ? json_decode($settings['delivery_service_section'], true) : null,
                'download_app_section' => (isset($settings['download_app_section'])) ? json_decode($settings['download_app_section'], true) : null,
                'landing_page_text' => (isset($settings['landing_page_text'])) ? json_decode($settings['landing_page_text'], true) : null,
            ]
        );
    }

    public function extra_charge(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'distance' => 'required',
        ]);
        if ($validator->errors()->count() > 0) {
            return response()->json(['errors' => Helpers::error_processor($validator)], 403);
        }
        $distance_data = $request->distance ?? 1;
        $data = DmVehicle::active()
            ->where(function ($query) use ($distance_data) {
                $query->where('starting_coverage_area', '<=', $distance_data)->where('maximum_coverage_area', '>=', $distance_data)
                    ->orWhere(function ($query) use ($distance_data) {
                        $query->where('starting_coverage_area', '>=', $distance_data);
                    });
            })->orderBy('starting_coverage_area')->first();
        echo json_encode($data);
        exit();

        $extra_charges = (float) (isset($data) ? $data->extra_charges  : 0);
        return response()->json($extra_charges, 200);
    }

    public function get_vehicles(Request $request)
    {
        $data = DMVehicle::active()->get(['id', 'type']);
        return response()->json($data, 200);
    }

    public function react_landing_page()
    {
        $datas =  DataSetting::with('translations')->where('type', 'react_landing_page')->get();
        $data = [];

        foreach ($datas as $key => $value) {
            if (count($value->translations) > 0) {
                $cred = [
                    $value->key => $value->translations[0]['value'],
                ];
                array_push($data, $cred);
            } else {
                $cred = [
                    $value->key => $value->value,
                ];
                array_push($data, $cred);
            }
        }
        $settings = [];
        foreach ($data as $single_data) {
            foreach ($single_data as $key => $single_value) {
                $settings[$key] = $single_value;
            }
        }

        $reviews = ReactTestimonial::get();

        return  response()->json(
            [
                'base_urls' => [
                    'header_icon_url' => asset('storage/app/public/header_icon'),
                    'header_banner_url' => asset('storage/app/public/header_banner'),
                    'testimonial_image_url' => asset('storage/app/public/reviewer_image'),
                    'promotional_banner_url' => asset('storage/app/public/promotional_banner'),
                    'business_image_url' => asset('storage/app/public/business_image'),
                ],

                'header_title' => (isset($settings['header_title']))  ? $settings['header_title'] : null,
                'header_sub_title' => (isset($settings['header_sub_title']))  ? $settings['header_sub_title'] : null,
                'header_tag_line' => (isset($settings['header_tag_line']))  ? $settings['header_tag_line'] : null,
                'header_icon' => (isset($settings['header_icon']))  ? $settings['header_icon'] : null,
                'header_banner' => (isset($settings['header_banner']))  ? $settings['header_banner'] : null,
                'company_title' => (isset($settings['company_title']))  ? $settings['company_title'] : null,
                'company_sub_title' => (isset($settings['company_sub_title']))  ? $settings['company_sub_title'] : null,
                'company_description' => (isset($settings['company_description']))  ? $settings['company_description'] : null,
                'banner_image_company' => (isset($settings['banner_image_company']))  ? $settings['banner_image_company'] : null,
                'company_button_name' => (isset($settings['company_button_name']))  ? $settings['company_button_name'] : null,
                'company_button_url' => (isset($settings['company_button_url']))  ? $settings['company_button_url'] : null,
                'download_user_app_title' => (isset($settings['download_user_app_title']))  ? $settings['download_user_app_title'] : null,
                'download_user_app_sub_title' => (isset($settings['download_user_app_sub_title']))  ? $settings['download_user_app_sub_title'] : null,
                'banner_image_download' => (isset($settings['banner_image_download']))  ? $settings['banner_image_download'] : null,
                'earning_title' => (isset($settings['earning_title']))  ? $settings['earning_title'] : null,
                'earning_sub_title' => (isset($settings['earning_sub_title']))  ? $settings['earning_sub_title'] : null,
                'earning_seller_title' => (isset($settings['earning_seller_title']))  ? $settings['earning_seller_title'] : null,
                'earning_seller_sub_title' => (isset($settings['earning_seller_sub_title']))  ? $settings['earning_seller_sub_title'] : null,
                'earning_seller_button_name' => (isset($settings['earning_seller_button_name']))  ? $settings['earning_seller_button_name'] : null,
                'earning_seller_button_url' => (isset($settings['earning_seller_button_url']))  ? $settings['earning_seller_button_url'] : null,
                'earning_dm_title' => (isset($settings['earning_dm_title']))  ? $settings['earning_dm_title'] : null,
                'earning_dm_sub_title' => (isset($settings['earning_dm_sub_title']))  ? $settings['earning_dm_sub_title'] : null,
                'earning_dm_button_name' => (isset($settings['earning_dm_button_name']))  ? $settings['earning_dm_button_name'] : null,
                'earning_dm_button_url' => (isset($settings['earning_dm_button_url']))  ? $settings['earning_dm_button_url'] : null,
                'business_title' => (isset($settings['business_title']))  ? $settings['business_title'] : null,
                'business_sub_title' => (isset($settings['business_sub_title']))  ? $settings['business_sub_title'] : null,
                'business_image' => (isset($settings['business_image']))  ? $settings['business_image'] : null,
                'testimonial_title' => (isset($settings['testimonial_title']))  ? $settings['testimonial_title'] : null,
                'testimonial_list' => (isset($reviews))  ? $reviews : null,
                'fixed_newsletter_title' => (isset($settings['fixed_newsletter_title']))  ? $settings['fixed_newsletter_title'] : null,
                'fixed_newsletter_sub_title' => (isset($settings['fixed_newsletter_sub_title']))  ? $settings['fixed_newsletter_sub_title'] : null,
                'fixed_footer_description' => (isset($settings['fixed_footer_description']))  ? $settings['fixed_footer_description'] : null,
                'fixed_promotional_banner' => (isset($settings['fixed_promotional_banner']))  ? $settings['fixed_promotional_banner'] : null,



                'promotion_banners' => (isset($settings['promotion_banner']))  ? json_decode($settings['promotion_banner'], true) : null,
                'download_user_app_links' => (isset($settings['download_user_app_links']))  ? json_decode($settings['download_user_app_links'], true) : null,
                'download_business_app_links' => (isset($settings['download_business_app_links']))  ? json_decode($settings['download_business_app_links'], true) : null,
                // 'dm_app_earning_links'=> (isset($settings['dm_app_earning_links']) )  ? json_decode($settings['dm_app_earning_links'], true) : null ,
                // 'download_user_app_links'=> (isset($settings['download_app_links']) )  ? json_decode($settings['download_app_links'], true) : null ,
            ]
        );
    }

    public function admin_landing_page()
    {
        $datas = DataSetting::where('type', 'admin_landing_page')->get();



        // Initialize the settings array
        $settings = [];

        // Loop through each data setting and add it to the settings array
        foreach ($datas as $data) {
            $settings[$data->key] = $data->value;
        }



        // Fetch all reviews
        $reviews = ReactTestimonial::get();

        $promotional_banner = AdminPromotionalBanner::get();


        $promotion_banners = [];
        foreach ($promotional_banner as $banner) {
            $promotion_banners[] = [
                'title' => $banner->title,
                'sub_title' => $banner->sub_title,
                'image' => asset('storage/app/public/promotional_banner/' . $banner->image),
                // Add other necessary fields from the AdminPromotionalBanner model
            ];
        }

        $admin_feature = AdminFeature::get();
        $admin_features = [];
        foreach ($admin_feature as $ad_f) {
            $admin_features[] = [
                'title' => $ad_f->title,
                'sub_title' => $ad_f->sub_title,
                'sub_title_2' => $ad_f->sub_title_2,
                'sub_title_3' => $ad_f->sub_title_3,
                'image' => asset('storage/app/public/promotional_banner/' . $ad_f->image),
                // Add other necessary fields from the AdminPromotionalBanner model
            ];
        }

        // echo json_encode($datas);

        // exit();

        return  response()->json(
            [

                'fixed_header_title' => (isset($settings['fixed_header_title']))  ? $settings['fixed_header_title'] : null,
                'fixed_header_sub_title' => (isset($settings['fixed_header_sub_title']))  ? $settings['fixed_header_sub_title'] : null,
                'fixed_module_title' => (isset($settings['fixed_module_title']))  ? $settings['fixed_module_title'] : null,

                'fixed_module_sub_title' => (isset($settings['fixed_module_sub_title']))  ? $settings['fixed_module_sub_title'] : null,
                'fixed_referal_title' => (isset($settings['fixed_referal_title']))  ? $settings['fixed_referal_title'] : null,
                'fixed_referal_sub_title' => (isset($settings['fixed_referal_sub_title']))  ? $settings['fixed_referal_sub_title'] : null,
                'fixed_newsletter_title' => (isset($settings['fixed_newsletter_title']))  ? $settings['fixed_newsletter_title'] : null,

                'download_user_app_title' => (isset($settings['download_user_app_title']))  ? $settings['download_user_app_title'] : null,
                'download_user_app_sub_title' => (isset($settings['download_user_app_sub_title']))  ? $settings['download_user_app_sub_title'] : null,
                'earning_title' => (isset($settings['earning_title']))  ? $settings['earning_title'] : null,
                'earning_sub_title' => (isset($settings['earning_sub_title']))  ? $settings['earning_sub_title'] : null,

                'testimonial_title' => (isset($settings['testimonial_title']))  ? $settings['testimonial_title'] : null,
                'testimonial_list' => (isset($reviews))  ? $reviews : null,
                'fixed_newsletter_title' => (isset($settings['fixed_newsletter_title']))  ? $settings['fixed_newsletter_title'] : null,
                'fixed_newsletter_sub_title' => (isset($settings['fixed_newsletter_sub_title']))  ? $settings['fixed_newsletter_sub_title'] : null,
                'fixed_footer_article_title' => (isset($settings['fixed_footer_article_title']))  ? $settings['fixed_footer_article_title'] : null,
                // 'why_choose_title' => (isset($settings['why_choose_title']))  ? $settings['why_choose_title'] : null,


                'home_title' => (isset($settings['home_title']))  ? $settings['home_title'] : null,
                'home_sub_title' => (isset($settings['home_sub_title']))  ? $settings['home_sub_title'] : null,
                'home_heading' => (isset($settings['home_heading']))  ? $settings['home_heading'] : null,
                'home_sub_heading' => (isset($settings['home_sub_heading']))  ? $settings['home_sub_heading'] : null,
                'home_seller_image' => (isset($settings['home_seller_image']))  ? asset('storage/app/public/earning/' . $settings['home_seller_image']) : null,
                'seller_footer_title' => (isset($settings['seller_footer_title']))  ? $settings['seller_footer_title'] : null,
                'seller_footer_sub_title' => (isset($settings['seller_footer_sub_title']))  ? $settings['seller_footer_sub_title'] : null,

                'seller_footer_heading' => (isset($settings['seller_footer_heading']))  ? $settings['seller_footer_heading'] : null,
                'seller_footer_sub_heading' => (isset($settings['seller_footer_sub_heading']))  ? $settings['seller_footer_sub_heading'] : null,
                'footer_seller_image' => (isset($settings['footer_seller_image']))  ? asset('storage/app/public/earning/' . $settings['footer_seller_image']) : null,
                'seller_purchase' => (isset($settings['seller_purchase']))  ? $settings['seller_purchase'] : null,
                'seller_description' => (isset($settings['seller_description']))  ? $settings['seller_description'] : null,


                'feature_title' => (isset($settings['feature_title']))  ? $settings['feature_title'] : null,

                'admin_features' => (isset($admin_features))  ? $admin_features : null,
                'promotion_banners' => (isset($promotion_banners))  ? $promotion_banners : null,
                'download_user_app_links' => (isset($settings['download_user_app_links']))  ? json_decode($settings['download_user_app_links'], true) : null,
                'download_business_app_links' => (isset($settings['download_business_app_links']))  ? json_decode($settings['download_business_app_links'], true) : null,

            ]
        );
    }

    public function delivery_landing_page()
    {
        $datas = DataSetting::where('type', 'delivery_landing_page')->get();


        // Initialize the settings array
        $settings = [];

        // Loop through each data setting and add it to the settings array
        foreach ($datas as $data) {
            $settings[$data->key] = $data->value;
        }



        // Fetch all reviews

        $promotional_banner = DeliveryPromotion::get();

        $promotion_banners = [];

        // Retrieve the first record where main_heading is not null
        $main_heading_record = DeliveryPromotion::whereNotNull('main_heading')->first();
        $main_heading = $main_heading_record ? $main_heading_record->main_heading : null;

        // Add the main_heading to the response array
        $promotion_banners['main_heading'] = $main_heading;

        // Loop through each promotional banner and add its details to the response array
        foreach ($promotional_banner as $banner) {
            $promotion_banners['promotional_data'][] = [
                'title' => $banner->title,
                'sub_title' => $banner->subtitle,
                'image' => asset('storage/delivery_promotion/' . $banner->image),
            ];
        }



        $delivery_faq = DeliveryFaq::get();
        $delivery_faqs = [];
        foreach ($delivery_faq as $ad_f) {
            $delivery_faqs[] = [
                'question' => $ad_f->question,

                'anwser' => $ad_f->anwser,

                // Add other necessary fields from the AdminPromotionalBanner model
            ];
        }

        // echo json_encode($delivery_faqs);

        // exit();

        return  response()->json(
            [
                'delivery_heading' => (isset($settings['delivery_heading']))  ? $settings['delivery_heading'] : null,
                'delivery_sub_heading' => (isset($settings['delivery_sub_heading']))  ? $settings['delivery_sub_heading'] : null,
                'delivery_head_image' => (isset($settings['delivery_head_image']))  ? asset('storage/app/public/deliveryman/' . $settings['delivery_head_image']) : null,

                'delivery_footer_heading' => (isset($settings['delivery_footer_heading']))  ? $settings['delivery_footer_heading'] : null,
                'delivery_footer_sub_heading' => (isset($settings['delivery_footer_sub_heading']))  ? $settings['delivery_footer_sub_heading'] : null,
                'sub_head_2' => (isset($settings['sub_head_2']))  ? $settings['sub_head_2'] : null,

                'download_link_button' => (isset($settings['download_link_button']))  ? $settings['download_link_button'] : null,
                'delivery_footer_image' => (isset($settings['delivery_footer_image']))  ? asset('storage/app/public/deliveryman/' . $settings['delivery_footer_image']) : null,


                'delivery_promotion' => (isset($promotion_banners))  ? $promotion_banners : null,
                'delivery_faqs' => (isset($delivery_faqs))  ? $delivery_faqs : null,

            ]
        );
    }

    public function flutter_landing_page()
    {
        $datas =  DataSetting::with('translations')->where('type', 'flutter_landing_page')->get();
        $data = [];
        foreach ($datas as $key => $value) {
            if (count($value->translations) > 0) {
                $cred = [
                    $value->key => $value->translations[0]['value'],
                ];
                array_push($data, $cred);
            } else {
                $cred = [
                    $value->key => $value->value,
                ];
                array_push($data, $cred);
            }
        }
        $settings = [];
        foreach ($data as $single_data) {
            foreach ($single_data as $key => $single_value) {
                $settings[$key] = $single_value;
            }
        }

        $criterias = FlutterSpecialCriteria::get();

        return  response()->json(
            [
                'base_urls' => [
                    'fixed_header_image' => asset('storage/app/public/fixed_header_image'),
                    'special_criteria_image' => asset('storage/app/public/special_criteria'),
                    'download_user_app_image' => asset('storage/app/public/download_user_app_image'),
                ],

                'fixed_header_title' => (isset($settings['fixed_header_title']))  ? $settings['fixed_header_title'] : null,
                'fixed_header_sub_title' => (isset($settings['fixed_header_sub_title']))  ? $settings['fixed_header_sub_title'] : null,
                'fixed_header_image' => (isset($settings['fixed_header_image']))  ? $settings['fixed_header_image'] : null,
                'fixed_module_title' => (isset($settings['fixed_module_title']))  ? $settings['fixed_module_title'] : null,
                'fixed_module_sub_title' => (isset($settings['fixed_module_sub_title']))  ? $settings['fixed_module_sub_title'] : null,
                'fixed_location_title' => (isset($settings['fixed_location_title']))  ? $settings['fixed_location_title'] : null,
                'join_seller_title' => (isset($settings['join_seller_title']))  ? $settings['join_seller_title'] : null,
                'join_seller_sub_title' => (isset($settings['join_seller_sub_title']))  ? $settings['join_seller_sub_title'] : null,
                'join_seller_button_name' => (isset($settings['join_seller_button_name']))  ? $settings['join_seller_button_name'] : null,
                'join_seller_button_url' => (isset($settings['join_seller_button_url']))  ? $settings['join_seller_button_url'] : null,
                'join_delivery_man_title' => (isset($settings['join_delivery_man_title']))  ? $settings['join_delivery_man_title'] : null,
                'join_delivery_man_sub_title' => (isset($settings['join_delivery_man_sub_title']))  ? $settings['join_delivery_man_sub_title'] : null,
                'join_delivery_man_button_name' => (isset($settings['join_delivery_man_button_name']))  ? $settings['join_delivery_man_button_name'] : null,
                'join_delivery_man_button_url' => (isset($settings['join_delivery_man_button_url']))  ? $settings['join_delivery_man_button_url'] : null,
                'download_user_app_title' => (isset($settings['download_user_app_title']))  ? $settings['download_user_app_title'] : null,
                'download_user_app_sub_title' => (isset($settings['download_user_app_sub_title']))  ? $settings['download_user_app_sub_title'] : null,
                'download_user_app_image' => (isset($settings['download_user_app_image']))  ? $settings['download_user_app_image'] : null,

                'special_criterias' => (isset($criterias))  ? $criterias : null,



                'download_user_app_links' => (isset($settings['download_user_app_links']))  ? json_decode($settings['download_user_app_links'], true) : null,
            ]
        );
    }

    private function getPaymentMethods()
    {

        // Check if the addon_settings table exists
        if (!Schema::hasTable('addon_settings')) {
            return [];
        }

        $methods = DB::table('addon_settings')->where('is_active', 1)->where('settings_type', 'payment_config')->get();
        $env = env('APP_ENV') == 'live' ? 'live' : 'test';
        $credentials = $env . '_values';

        $data = [];
        foreach ($methods as $method) {
            $credentialsData = json_decode($method->$credentials);
            $additional_data = json_decode($method->additional_data);
            if ($credentialsData->status == 1) {
                $data[] = [
                    'gateway' => $method->key_name,
                    'gateway_title' => $additional_data?->gateway_title,
                    'gateway_image' => $additional_data?->gateway_image
                ];
            }
        }
        return $data;
    }

    private function getDefaultPaymentMethods()
    {
        // Check if the addon_settings table exists
        if (!Schema::hasTable('addon_settings')) {
            return [];
        }

        $methods = DB::table('addon_settings')->where('is_active', 1)->whereIn('settings_type', ['payment_config'])->whereIn('key_name', ['ssl_commerz', 'paypal', 'stripe', 'razor_pay', 'senang_pay', 'paytabs', 'paystack', 'paymob_accept', 'paytm', 'flutterwave', 'liqpay', 'bkash', 'mercadopago', 'phonepay'])->get();
        $env = env('APP_ENV') == 'live' ? 'live' : 'test';
        $credentials = $env . '_values';




        $data = [];
        foreach ($methods as $method) {
            $credentialsData = json_decode($method->$credentials);
            $additional_data = json_decode($method->additional_data);
            if ($credentialsData->status == 1) {
                $data[] = [
                    'gateway' => $method->key_name,
                    'gateway_title' => $additional_data?->gateway_title,
                    'gateway_image' => $additional_data?->gateway_image
                ];
            }
        }

        return $data;
    }

    public function offline_payment_method_list(Request $request)
    {
        $data = OfflinePaymentMethod::where('status', 1)->get();
        $data = $data->count() > 0 ? $data : null;
        return response()->json($data, 200);
    }
}
