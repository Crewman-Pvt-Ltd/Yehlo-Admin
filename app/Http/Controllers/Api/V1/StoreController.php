<?php

namespace App\Http\Controllers\Api\V1;

use App\CentralLogics\Helpers;
use App\CentralLogics\StoreLogic;
use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Item;
use App\Models\Store;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Review;
use Illuminate\Support\Facades\DB;
use App\Traits\AddonHelper;
use App\Models\BusinessSetting;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\Client\Pool;
use Illuminate\Support\Facades\Cache;

use League\CommonMark\Extension\CommonMark\Node\Inline\Strong;

class StoreController extends Controller
{


    private $map_api_key;

    use AddonHelper;

    function __construct()
    {
        $map_api_key_server = BusinessSetting::where(['key' => 'map_api_key_server'])->first();
        $map_api_key_server = $map_api_key_server ? $map_api_key_server->value : null;
        $this->map_api_key = $map_api_key_server;
    }


    public function get_stores(Request $request, $filter_data = "all")
    {
        // dd($request);
        if (!$request->hasHeader('zoneId')) {
            $errors = [];
            array_push($errors, ['code' => 'zoneId', 'message' => translate('messages.zone_id_required')]);
            return response()->json([
                'errors' => $errors
            ], 403);
        }

        $type = $request->query('type', 'all');


        $store_type = $request->query('store_type', 'all');

        $zone_id = $request->header('zoneId');

        $longitude = (float)$request->header('longitude');

        $latitude = (float)$request->header('latitude');

        $stores = StoreLogic::get_stores($zone_id, $filter_data, $type, $store_type, $request['limit'], $request['offset'], $request->query('featured'), $longitude, $latitude);

        $stores['stores'] = Helpers::store_data_formatting($stores['stores'], true);

        return response()->json($stores, 200);
    }

    public function get_stores_by_range(Request $request, $filter_data = "all")
    {
        if (!$request->hasHeader('zoneId')) {
            $errors = [];
            array_push($errors, ['code' => 'zoneId', 'message' => translate('messages.zone_id_required')]);
            return response()->json([
                'errors' => $errors
            ], 403);
        }

        $type = $request->query('type', 'all');
        $total_size = 0;

        $store_type = $request->query('store_type', 'all');

        $zone_id = $request->header('zoneId');

        $longitude = (float) $request->header('longitude');

        $latitude = (float) $request->header('latitude');
        $user_lat = $request->user_lat;
        $user_lng = $request->user_lng;
        $range = $request->range;

        $stores = StoreLogic::get_stores($zone_id, $filter_data, $type, $store_type, $request['limit'], $request['offset'], $request->query('featured'), $longitude, $latitude);

        $stores['stores'] = Helpers::store_data_formatting($stores['stores'], true);

        $nearbyStores = [];

        foreach ($stores['stores'] as $store) {
            $response = Http::get('https://maps.googleapis.com/maps/api/distancematrix/json', [
                'origins' => $user_lat . ',' . $user_lng,
                'destinations' => $store->latitude . ',' . $store->longitude,
                'key' => $this->map_api_key,
                'mode' => 'driving',
                'departure_time' => time(), // Specify the departure time for traffic predictions
                'traffic_model' => 'best_guess' // Choose the traffic model (best_guess, pessimistic, or optimistic)
            ]);


            $distanceData = $response->json();
            // dd($distanceData);

            if ($response->ok() && $distanceData['status'] === 'OK') {
                $distance = $distanceData['rows'][0]['elements'][0]['distance']['value'] / 1000; // distance in km
                $time = $distanceData['rows'][0]['elements'][0]['duration']['text'];

                if ($distance <= $range) { // Check if distance is within the specified range
                    $store->distance = $distance; // Add distance to the store object
                    $store->distance_time = $time;
                    $nearbyStores[] = $store;
                    $total_size++;
                }
            }
        }


        if (empty($nearbyStores)) {
            return response()->json([
                'errors' => [
                    ['code' => 'no_nearby_stores', 'message' => 'No nearby stores found within the specified range.']
                ]
            ], 404);
        }
        usort($nearbyStores, function ($a, $b) {
            return $a->distance <=> $b->distance;
        });

        // Return the response with nearby stores
        $stores['total_size'] = $total_size;
        $stores['stores'] = $nearbyStores;
        $stores['nearStore'] = $nearbyStores[0];

        return response()->json($stores, 200);
    }

    // public function get_stores_by_nearby(Request $request, $filter_data = "all")
    // {
    //     if (!$request->hasHeader('zoneId')) {
    //         $errors = [];
    //         array_push($errors, ['code' => 'zoneId', 'message' => translate('messages.zone_id_required')]);
    //         return response()->json([
    //             'errors' => $errors
    //         ], 403);
    //     }

    //     $type = $request->query('type', 'all');


    //     $store_type = $request->query('store_type', 'all');

    //     $zone_id = $request->header('zoneId');

    //     $longitude = (float) $request->header('longitude');

    //     $latitude = (float) $request->header('latitude');
    //     $user_lat = $request->user_lat;
    //     $user_lng = $request->user_lng;


    //     $stores = StoreLogic::get_stores($zone_id, $filter_data, $type, $store_type, $request['limit'], $request['offset'], $request->query('featured'), $longitude, $latitude);

    //     $stores['stores'] = Helpers::store_data_formatting($stores['stores'], true);



    //     $nearbyStores = [];
    //     foreach ($stores['stores'] as $store) {
    //         $response = Http::get('https://maps.googleapis.com/maps/api/distancematrix/json', [
    //             'origins' => $user_lat . ',' . $user_lng,
    //             'destinations' => $store->latitude . ',' . $store->longitude,
    //             'key' => $this->map_api_key,
    //             'mode' => 'driving',
    //             'departure_time' => time(), // Specify the departure time for traffic predictions
    //             'traffic_model' => 'best_guess' // Choose the traffic model (best_guess, pessimistic, or optimistic)
    //         ]);

    //         $distanceData = $response->json();

    //         if ($response->ok() && $distanceData['status'] === 'OK') {
    //             $distance = $distanceData['rows'][0]['elements'][0]['distance']['value'] / 1000; // distance in km
    //             $time = $distanceData['rows'][0]['elements'][0]['duration']['text'];
    //             $store->distance = $distance; // Add distance to the store object
    //             $store->distance_time = $time;
    //             $nearbyStores[] = $store;
    //         }
    //     }

    //     if (empty($nearbyStores)) {
    //         return response()->json([
    //             'errors' => [
    //                 ['code' => 'no_nearby_stores', 'message' => 'No nearby stores found within the specified range.']
    //             ]
    //         ], 404);
    //     }

    //     usort($nearbyStores, function ($a, $b) {
    //         return $a->distance <=> $b->distance;
    //     });

    //     $nearStore = $nearbyStores[0];
    //     $filteredNearStore = [
    //         'id' => $nearStore->id,
    //         'name' => $nearStore->name,
    //         'latitude' => $nearStore->latitude,
    //         'longitude' => $nearStore->longitude,
    //         'address' => $nearStore->address,
    //         'status' => $nearStore->status,
    //         'distance' => $nearStore->distance,
    //         'distance_time' => $nearStore->distance_time
    //     ];

    //     return response()->json(["nearStore" => $filteredNearStore], 200);
    // }



    public function get_stores_by_nearby(Request $request, $filter_data = "all")
    {
        if (!$request->hasHeader('zoneId')) {
            $errors = [];
            array_push($errors, ['code' => 'zoneId', 'message' => translate('messages.zone_id_required')]);
            return response()->json([
                'errors' => $errors
            ], 403);
        }

        $type = $request->query('type', 'all');
        $store_type = $request->query('store_type', 'all');
        $zone_id = $request->header('zoneId');
        $longitude = (float) $request->header('longitude');
        $latitude = (float) $request->header('latitude');
        $user_lat = $request->user_lat;
        $user_lng = $request->user_lng;

        $stores = StoreLogic::get_stores($zone_id, $filter_data, $type, $store_type, $request['limit'], $request['offset'], $request->query('featured'), $longitude, $latitude);
        $stores['stores'] = Helpers::store_data_formatting($stores['stores'], true);

        // Prepare destinations for the Distance Matrix API
        $destinations = [];
        foreach ($stores['stores'] as $store) {
            $destinations[] = $store->latitude . ',' . $store->longitude;
        }
        $destinationString = implode('|', $destinations);

        // Make a single request to the Distance Matrix API
        $response = Http::get('https://maps.googleapis.com/maps/api/distancematrix/json', [
            'origins' => $user_lat . ',' . $user_lng,
            'destinations' => $destinationString,
            'key' => $this->map_api_key,
            'mode' => 'driving',
            'departure_time' => time(), // Specify the departure time for traffic predictions
            'traffic_model' => 'best_guess' // Choose the traffic model (best_guess, pessimistic, or optimistic)
        ]);

        $distanceData = $response->json();
        $nearbyStores = [];



        if ($response->ok() && $distanceData['status'] === 'OK') {
            foreach ($stores['stores'] as $index => $store) {
                $element = $distanceData['rows'][0]['elements'][$index];
                if ($element['status'] === 'OK') {
                    $distance = $element['distance']['value'] / 1000; // distance in km
                    $time = $element['duration']['text'];


                    $store->distance = $distance; // Add distance to the store object
                    $store->distance_time = $time;
                    $nearbyStores[] = $store;
                }
            }
        }



        if (empty($nearbyStores)) {
            return response()->json([
                'errors' => [
                    ['code' => 'no_nearby_stores', 'message' => 'No nearby stores found within the specified range.']
                ]
            ], 200);
        }

        usort($nearbyStores, function ($a, $b) {
            return $a->distance <=> $b->distance;
        });


        $nearStore = $nearbyStores[0];

        $filteredNearStore = [
            'id' => $nearStore->id,
            'name' => $nearStore->name,
            'latitude' => $nearStore->latitude,
            'longitude' => $nearStore->longitude,
            'address' => $nearStore->address,
            'status' => $nearStore->status,
            'distance' => $nearStore->distance,
            'distance_time' => $nearStore->distance_time
        ];

        return response()->json(["nearStore" => $filteredNearStore], 200);
    }


    public function get_latest_stores(Request $request, $filter_data = "all")
    {
        if (!$request->hasHeader('zoneId')) {
            $errors = [];
            array_push($errors, ['code' => 'zoneId', 'message' => translate('messages.zone_id_required')]);
            return response()->json([
                'errors' => $errors
            ], 403);
        }

        $type = $request->query('type', 'all');

        $zone_id = $request->header('zoneId');
        $longitude = (float)$request->header('longitude');
        $latitude = (float)$request->header('latitude');
        $stores = StoreLogic::get_latest_stores($zone_id, $request['limit'], $request['offset'], $type, $longitude, $latitude);
        $stores['stores'] = Helpers::store_data_formatting($stores['stores'], true);

        return response()->json($stores, 200);
    }

    public function get_popular_stores(Request $request)
    {
        if (!$request->hasHeader('zoneId')) {
            $errors = [];
            array_push($errors, ['code' => 'zoneId', 'message' => translate('messages.zone_id_required')]);
            return response()->json([
                'errors' => $errors
            ], 403);
        }
        $type = $request->query('type', 'all');
        $zone_id = $request->header('zoneId');
        $longitude = (float)$request->header('longitude');
        $latitude = (float)$request->header('latitude');
        $stores = StoreLogic::get_popular_stores($zone_id, $request['limit'], $request['offset'], $type, $longitude, $latitude);
        $stores['stores'] = Helpers::store_data_formatting($stores['stores'], true);

        return response()->json($stores, 200);
    }

    public function get_discounted_stores(Request $request)
    {
        if (!$request->hasHeader('zoneId')) {
            $errors = [];
            array_push($errors, ['code' => 'zoneId', 'message' => translate('messages.zone_id_required')]);
            return response()->json([
                'errors' => $errors
            ], 403);
        }
        $type = $request->query('type', 'all');
        $zone_id = $request->header('zoneId');
        $longitude = (float)$request->header('longitude');
        $latitude = (float)$request->header('latitude');
        $stores = StoreLogic::get_discounted_stores($zone_id, $request['limit'], $request['offset'], $type, $longitude, $latitude);
        $stores['stores'] = Helpers::store_data_formatting($stores['stores'], true);

        return response()->json($stores, 200);
    }

    public function get_top_rated_stores(Request $request)
    {
        if (!$request->hasHeader('zoneId')) {
            $errors = [];
            array_push($errors, ['code' => 'zoneId', 'message' => translate('messages.zone_id_required')]);
            return response()->json([
                'errors' => $errors
            ], 403);
        }
        $type = $request->query('type', 'all');
        $zone_id = $request->header('zoneId');
        $longitude = (float)$request->header('longitude');
        $latitude = (float)$request->header('latitude');
        $stores = StoreLogic::get_top_rated_stores($zone_id, $request['limit'], $request['offset'], $type, $longitude, $latitude);
        $stores['stores'] = Helpers::store_data_formatting($stores['stores'], true);

        usort($stores['stores'], function ($a, $b) {
            $key = 'avg_rating';
            return $b[$key] - $a[$key];
        });

        return response()->json($stores, 200);
    }

    public function get_popular_store_items($id)
    {
        $items = Item::when(is_numeric($id), function ($qurey) use ($id) {
            $qurey->where('store_id', $id);
        })
            ->when(!is_numeric($id), function ($query) use ($id) {
                $query->whereHas('store', function ($q) use ($id) {
                    $q->where('slug', $id);
                });
            })
            ->active()->popular()->limit(10)->get();
        $items = Helpers::product_data_formatting($items, true, true, app()->getLocale());

        return response()->json($items, 200);
    }

    public function get_details(Request $request, $id)
    {
        $longitude = (float)$request->header('longitude');
        $latitude = (float)$request->header('latitude');
        $store = StoreLogic::get_store_details($id, $longitude, $latitude);
        if ($store) {
            $category_ids = DB::table('items')
                ->join('categories', 'items.category_id', '=', 'categories.id')
                ->selectRaw('categories.position as positions, IF((categories.position = "0"), categories.id, categories.parent_id) as categories')
                ->where('items.store_id', $store->id)
                ->where('categories.status', 1)
                ->groupBy('categories', 'positions')
                ->get();

            $store = Helpers::store_data_formatting($store);
            $store['category_ids'] = array_map('intval', $category_ids->pluck('categories')->toArray());
            $store['category_details'] = Category::whereIn('id', $store['category_ids'])->get();
            $store['price_range']  = Item::withoutGlobalScopes()->where('store_id', $store->id)
                ->select(DB::raw('MIN(price) AS min_price, MAX(price) AS max_price'))
                ->get(['min_price', 'max_price']);
        }
        return response()->json($store, 200);
    }

    public function get_searched_stores(Request $request)
    {
        if (!$request->hasHeader('zoneId')) {
            $errors = [];
            array_push($errors, ['code' => 'zoneId', 'message' => translate('messages.zone_id_required')]);
            return response()->json([
                'errors' => $errors
            ], 403);
        }
        $validator = Validator::make($request->all(), [
            'name' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => Helpers::error_processor($validator)], 403);
        }

        $type = $request->query('type', 'all');

        $zone_id = $request->header('zoneId');
        $longitude = (float)$request->header('longitude');
        $latitude = (float)$request->header('latitude');
        $stores = StoreLogic::search_stores($request['name'], $zone_id, $request->category_id, $request['limit'], $request['offset'], $type, $longitude, $latitude);
        $stores['stores'] = Helpers::store_data_formatting($stores['stores'], true);
        return response()->json($stores, 200);
    }

    public function reviews(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'store_id' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => Helpers::error_processor($validator)], 403);
        }
        $id = $request['store_id'];


        $reviews = Review::with(['customer', 'item'])
            ->whereHas('item', function ($query) use ($id) {
                return $query->where('store_id', $id);
            })
            ->active()->latest()->get();

        $storage = [];
        foreach ($reviews as $temp) {
            $temp['attachment'] = json_decode($temp['attachment']);
            $temp['item_name'] = null;
            $temp['item_image'] = null;
            $temp['customer_name'] = null;
            if ($temp->item) {
                $temp['item_name'] = $temp->item->name;
                $temp['item_image'] = $temp->item->image;
                if (count($temp->item->translations) > 0) {
                    $translate = array_column($temp->item->translations->toArray(), 'value', 'key');
                    $temp['item_name'] = $translate['name'];
                }
            }
            if ($temp->customer) {
                $temp['customer_name'] = $temp->customer->f_name . ' ' . $temp->customer->l_name;
            }

            unset($temp['item']);
            unset($temp['customer']);
            array_push($storage, $temp);
        }

        return response()->json($storage, 200);
    }


    public function get_recommended_stores(Request $request)
    {


        if (!$request->hasHeader('zoneId')) {
            $errors = [];
            array_push($errors, ['code' => 'zoneId', 'message' => translate('messages.zone_id_required')]);
            return response()->json([
                'errors' => $errors
            ], 403);
        }
        $type = $request->query('type', 'all');
        $zone_id = $request->header('zoneId');
        $longitude = (float)$request->header('longitude') ?? 0;
        $latitude = (float)$request->header('latitude') ?? 0;
        $stores = StoreLogic::get_recommended_stores($zone_id, $request['limit'], $request['offset'], $type, $longitude, $latitude);
        $stores['stores'] = Helpers::store_data_formatting($stores['stores'], true);

        return response()->json($stores, 200);
    }
}
