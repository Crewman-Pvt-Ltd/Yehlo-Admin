<?php

namespace App\Http\Controllers\Admin;
use Carbon\Carbon;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Brand;
use App\CentralLogics\Helpers;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Config;
use Brian2694\Toastr\Facades\Toastr;

class BrandController extends Controller
{
    public function index()
{
    $user = auth('admin')?->check();
    $brands = Brand::all();
    if ($user){
        
        return view('admin-views.brand.index', compact('brands'));
    }
    else{
        return view('vendor-views.brand.index', compact('brands'));
    }
   
}


    
   
public function store(Request $request, $id = null)
{
    if (auth('admin')->check()) {
        $role = 'admin';
    } elseif (auth('vendor')->check()) {
        $role = 'vendor';
    }

    $validator = Validator::make($request->all(), [
        'brand_name' => 'required|string|max:191',
        'image' => 'required|image|mimes:jpg,png,jpeg,gif,bmp,tif,tiff|max:2048',
        'slug' => 'nullable|string|max:191',
        'items_count' => 'nullable|integer|max:191',
        'brand_class' => 'nullable|string|max:191',
        'trademark' => 'nullable|string|max:191',
        'file' => 'nullable|file|mimes:pdf,doc,docx|max:2048',
    ]);

    if ($validator->fails()) {
        return redirect()->back()
                         ->withErrors($validator)
                         ->withInput();
    }

    $existingBrand = Brand::where('brand_name', $request->input('brand_name'))->first();
    if ( $role== 'admin' && $existingBrand) {
        Toastr::error("Brand with this name already exists.");
        return redirect()->route('admin.brand');
    }
    elseif ( $role== 'vendor' && $existingBrand){
        Toastr::error("Brand with this name already exists.");
        return redirect()->route('vendor.brand');
    }

    $imagePath = Helpers::upload('brand/', 'png', $request->file('image'));
    $filePath = Helpers::upload('brand/files/', 'file', $request->file('file'));

    $module_id = Config::get('module.current_module_id');

    $data = [
        'brand_name' => $request->input('brand_name'),
        'image' => $imagePath,
        'slug' => $request->input('slug', null),
        'items_count' => $request->input('items_count', 0),
        'module_id' => $module_id ?? 4,
        'brand_class' => $request->input('brand_class', null),
        'trademark' => $request->input('trademark', null),
        'file' => $filePath,
        'is_approved' => $role === 'admin' ? 1 : 0,
    ];
    if ($role === 'vendor') {
        $vendorId = auth('vendor')->id();
        $data['vendor_id'] = $vendorId;
    }
    if ($id) {
        $brand = Brand::find($id);
       
        if (!$brand) {
            return redirect()->route($role . '.brand')
                             ->with('error', 'Brand not found');
        }

        $data['updated_by'] = $role; 
        $data['updated_at'] = Carbon::now();
        $brand->update($data);
        $message = 'Brand updated successfully';
    } else {
        $data['created_by'] = $role; 
        $data['created_at'] = Carbon::now();
        Brand::create($data);
        $message = 'Brand created successfully';
    }

    
    if ($role === 'admin') {
        return redirect()->route('admin.brand')->with('success', $message);
    } elseif ($role === 'vendor') {
        return redirect()->route('vendor.brand')->with('success', $message);
    }
}



public function getBrand()
{
    $brands = Brand::all();

    $brandsData = $brands->map(function($brand) {
        return [
            "id" => $brand->id,
            "name" => $brand->brand_name,
            "slug" => $brand->slug,
            "image" => $brand->image,
            "status" => $brand->status,
            "vendor_id"=> $brand->vendor_id,
            "created_at" => $brand->created_at,
            "updated_at" => $brand->updated_at,
            "items_count" => $brand->items_count,
            "brand_class" => $brand->brand_class,
            "trademark" => $brand->trademark,
            "module_id" => $brand->module_id,
            "image_full_url" => asset('storage/app/public/brand/' . $brand->image)
        ];
    });

    return response()->json($brandsData);
}





    
public function edit($id)
{
   

    if (auth('admin')->check()) {
        $role = 'admin';
    } elseif (auth('vendor')->check()) {
        $role = 'vendor';
    }

    $brand = Brand::find($id);

    if (!$brand) {
        if ($role === 'admin') {
            return redirect()->route('admin.brand')
                             ->with('error', 'Brand not found');
        } elseif ($role === 'vendor') {
            return redirect()->route('vendor.brand')
                             ->with('error', 'Brand not found');
        }
    }

    if ($role === 'admin') {
        return view('admin-views.brand.edit', compact('brand'));
    } elseif ($role === 'vendor') {
        return view('vendor-views.brand.edit', compact('brand'));
    }

}


public function destroy($id)
{
    
    if (auth('admin')->check()) {
        $role = 'admin';
    } elseif (auth('vendor')->check()) {
        $role = 'vendor';
    }

    $brand = Brand::find($id);
    
    if ($brand) {
        if ($role === 'admin') {
            return redirect()->route('admin.brand')
                             ->with('error', 'Brand not found');
        } elseif ($role === 'vendor') {
            return redirect()->route('vendor.brand')
                             ->with('error', 'Brand not found');
        }
    }

    if ($role === 'admin') {
        return view('admin-views.brand.edit', compact('brand'));
    } elseif ($role === 'vendor') {
        return view('vendor-views.brand.edit', compact('brand'));
    }
}

public function getbrandrequests()
{
    $brands = Brand::all();

    $brandsData = $brands->map(function($brand) {
        $statusLabel = '';
        switch ($brand->is_approved) {
            case 0:
                $statusLabel = 'Pending';
                break;
            case 1:
                $statusLabel = 'Approved';
                break;
            case 3:
                $statusLabel = 'Rejected';
                break;
        }

        return [
            "id" => $brand->id,
            "name" => $brand->brand_name,
            "status" => $statusLabel,
            "vendor_id" => $brand->vendor_id,
            "image" => $brand->image,
            "created_by" => $brand->created_by,
        ];
    });

    return view('admin-views.brand-requests.index', ['brands' => $brandsData]);
}

public function brandApprove($id)
{
    $brand = Brand::find($id);
    $brand->is_approved = 1; 
    $brand->save();

    return redirect()->back()->with('success', 'Brand approved successfully.');
}

public function brandDeny($id)
{
    $brand = Brand::find($id);
    $brand->is_approved = 3; 
    $brand->save();

    return redirect()->back()->with('success', 'Brand rejected successfully.');
}

public function brandDelete($id)
{
    $brand = Brand::find($id);
    $brand->delete();

    return redirect()->back()->with('success', 'Brand deleted successfully.');
}


}