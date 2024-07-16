<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Contracts\ControllerInterface;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;
use App\Http\Controllers\BaseController;
use App\CentralLogics\Helpers;
use Brian2694\Toastr\Facades\Toastr;
use App\Models\Plan;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class NewPlanController extends Controller
{
    public function index(Request $request)
    {
        $module_id = Config::get('module.current_module_id');

        $plans = Plan::paginate(10);

        return view('admin-views.new-plan.index', compact('module_id', 'plans'));
    }

    function test($s, $n)
    {
        return substr($s, 0, $n) . substr($s, $n + 1, strlen($s) - $n);
    }
    public function create(Request $request)
    {
        // Define validation rules

        $rules = [
            'plan_name' => 'required|string|max:191',
            'plan_price' => 'required|numeric',
            'product_limit' => 'required|string|max:191',
            // 'plan_duration' => 'required|in:3,6,12', // Adjusted validation rule for plan duration
            'imagePlan' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048', // Example validation for image upload
        ];

        $validator = Validator::make($request->all(), $rules);

        // Check if validation fails
        if ($validator->fails()) {
            // Redirect back with errors
            return redirect()->back()->withErrors($validator)->withInput();
        }

        // Access form data
        $planVariation = $request->input('plan_variation', 0);

        $planName = $request->input('plan_name');
        $planPrice = $request->input('plan_price');
        $productLimit = $request->input('product_limit');
        $planDuration = $request->input('plan_duration');
        // dd($request);
        $desc_1 = $request->desc_1;
        $desc_2 = $request->desc_2;
        $desc_3 = $request->desc_3;

// dd($request);

        // $description = json_encode($request->input('description'));
        // $string = trim(str_replace('\n', '', (str_replace('\r', '', $description))));
        // $description1 = str_replace("<\/p>", ',', $string);
        // $description1 = str_replace("<p>", '', $description1);
        // $str = trim($description1, '[]"');
        // //  $array = explode(',', $str);
        // $description = $str;




        $image = $request->file('imagePlan'); // Uploaded image file
        // Save the image to storage and get the path
        $imagePath = Helpers::upload('plan/', 'png', $image);

        // Create a new Plan instance
        $plan = new Plan();

        // Set the attributes
        $plan->plan_id = $planVariation;
        $plan->name = $planName;
        $plan->type = Str::slug($planName, '-');
        $plan->price = $planPrice;
        $plan->plan_duration = $planDuration;
        $plan->product_limit = $productLimit;
        $plan->desc_1 = $desc_1;
        $plan->desc_2 = $desc_2;
        $plan->desc_3 = $desc_3;
        $plan->mode = env('APP_MODE');
        $plan->status = 1; // Assuming status 1 means active
        $plan->add_by = auth()->id(); // Assuming you want to store the user who added the plan
        $plan->updated_by = null;
        $plan->image = $imagePath;

        $plan->save();

        // Redirect to a success page or wherever you need
        return redirect()->back()->with('success', 'Data has been successfully submitted!');
    }

    public function get_plan_by_id(Request $request)
    {
        // Retrieve the plan ID from the request
        $planId = $request->input('plan_variation');

        // Fetch the plan details by ID
        $plan = Plan::findOrFail($planId);

        // Prepare the response data
        $responseData = [
            'name' => $plan->name,
            'price' => $plan->price,
            'product_limit' => $plan->product_limit,
            'description' => $plan->description,
            'image' => asset('storage/app/public/plan/' . $plan->image), // Assuming the image path is stored in the 'image' column
        ];

        // Return the response as JSON
        return response()->json($responseData);
    }


    public function destroy($id)
    {
        $plan = Plan::findOrFail($id);

        // Delete associated pictures
        $filePath = 'plan/' . $plan->image; // Construct the file path
        if (Storage::exists($filePath)) {
            Storage::delete($filePath); // Delete the file from the directory
        }


        $plan->delete();

        return redirect()->route('admin.new-plan')->with('success', 'Plan deleted successfully.');
    }



    public function updateStatus($id, $status)
    {

        $plan = Plan::findOrFail($id);

        $plan->status = $status;
        $plan->save();

        // Optionally, return a response
        return redirect()->back()->with('success', 'Plan Successfully Disable');
    }

    public function updateRecommendation($id, $status)
    {
        // Find the plan by ID
        $plan = Plan::findOrFail($id);

        // Retrieve all plans where recommend is currently set to 1
        $recommendedPlans = Plan::where('recommed', 1)->get();

        // If there are any recommended plans other than the current one, update their recommend value to 0
        foreach ($recommendedPlans as $recommendedPlan) {
            if ($recommendedPlan->id !== $id) {
                $recommendedPlan->recommed = 0;
                $recommendedPlan->save();
            }
        }

        // Update the status of the current plan
        $plan->recommed = $status;
        $plan->save();

        // Optionally, return a response
        return redirect()->back()->with('success', 'Plan Successfully Recommended');
    }


    public function edit(Request $request, $id)
    {
        // Find the plan by ID

        $plan = Plan::findOrFail($id);

        return view('admin-views.new-plan.edit', compact('plan'));
    }


    public function updateValue(Request $request)
    {

        $editValue = $request['editValue'];


        $image = $request->file('imagePlan');



        $imagePath = Helpers::upload('plan/', 'png', $image);
        $record = Plan::findOrFail($editValue);
        $record->name = $request->input('plan_name');
        $record->price = $request->input('plan_price');
        $record->product_limit = $request->input('product_limit');
        $record->plan_duration = $request->input('plan_duration');
        $record->desc_1 = $request->input('desc_1');
        $record->desc_2 = $request->input('desc_2');
        $record->desc_3 = $request->input('desc_3');
        // dd($imagePath);
        if($imagePath == "def.png"){
            $record->image = $record->image;
        }
        else{
            $record->image = $imagePath;
        }


        $record->save();

        // Optionally, redirect back with a success message
        return redirect()->route('admin.new-plan')->with('success', 'Record updated successfully');
    }
}
