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
    $brands = Brand::all();
    return view('admin-views.brand.index', compact('brands'));
}


    
   
public function store(Request $request, $id = null)
{
   
    // dd($request);
    $validator = Validator::make($request->all(), [
        'brand_name' => 'required|string|max:191',
        'image' => 'required|image|mimes:jpg,png,jpeg,gif,bmp,tif,tiff|max:2048',
        'slug' => 'nullable|string|max:191',
        'items_count' => 'nullable|integer|max:191',
        'brand_class' => 'nullable|string|max:191',
        'trademark' => 'nullable|string|max:191',
        'file' => 'nullable|file|mimes:pdf,doc,docx|max:2048'
    ]);

    
    if ($validator->fails()) {
        return redirect()->back()
                         ->withErrors($validator)
                         ->withInput();
    }

   
    $existingBrand = Brand::where('brand_name', $request->input('brand_name'))->first();

    if ($existingBrand) {
     
        Toastr::error("Brand with this name already exists.");
        return redirect()->route('admin.brand');
    }

   
    $imagePath = Helpers::upload('brand/', 'png', $request->file('image'));
    $filePath = Helpers::upload('brand/files/', 'file', $request->file('file'));

 
    $module_id = Config::get('module.current_module_id');

  
    $data = [
        'brand_name' => $request->input('brand_name'),
        'image' => $imagePath,
        'slug' => $request->input('slug', null),
        'items_count' => $request->input('items_count', 0),
        'module_id' => $module_id,
        'brand_class' => $request->input('brand_class', null),
        'trademark' => $request->input('trademark', null),
        'file' => $filePath
    ];

    if ($id) {
     
        $brand = Brand::find($id);

        
        if (!$brand) {
            return redirect()->route('admin.brand.index')
                             ->with('error', 'Brand not found');
        }

      
        $data['updated_at'] = Carbon::now();
        $brand->update($data);
        $message = 'Brand updated successfully';
    } else {
     
        $data['created_at'] = Carbon::now();
        $brand = Brand::create($data);
        $message = 'Brand created successfully';
    }

    
    return redirect()->route('admin.brand')
                     ->with('success', $message);
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
    $brand = Brand::find($id);
   
    if (!$brand) {
        return redirect()->route('admin.brand.index')
                         ->with('error', 'Brand not found');
    }

    return view('admin-views.brand.edit', compact('brand'));
}

public function destroy($id)
{
    $brand = Brand::find($id);
    
    if ($brand) {
        $brand->delete();
        return redirect()->route('admin.brand')
                         ->with('success', 'Brand deleted successfully');
    }

    return redirect()->route('admin.brand.index')
                     ->with('error', 'Brand not found');
}
}

    


