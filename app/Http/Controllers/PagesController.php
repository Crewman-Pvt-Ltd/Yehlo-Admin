<?php

namespace App\Http\Controllers;
use App\Models\Page;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\CentralLogics\Helpers;
class PagesController extends Controller
{
    public function index(Request $request)
    {
        $page_type = $request->type;
        if ($page_type === "delivery-landing-page"){
            return view('pages.create');
        }
        else if ($page_type === "admin-landing-page"){
            echo "This is Admin Landing Page";
        }
        exit;
        $pages = Page::all();
        return view('pages.index', compact('pages'));
    }

    public function create()
    {
        return view('pages.create');
    }

    public function store(Request $request)
{
   
    $request->validate([
        'delivery_partner_title.*' => 'required|max:20',
        'delivery_sub_title.*' => 'required|max:80',
        'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
    ]);

    if ($request->hasFile('image')) {
        $imagePath = Helpers::upload('landing_page/', 'png', $request->file('image'));
    } else {
        $imagePath = null;
    }

    $titles = $request->input('delivery_partner_title');
    $subTitles = $request->input('delivery_sub_title');
    $deliveryPartnerData = [];

    foreach ($titles as $key => $title) {
        $deliveryPartnerData[] = [
            'title' => $title,
            'sub_title' => $subTitles[$key],
            'image' => $imagePath,
        ];
    }

    $page = Page::create([
        // 'admin_landing' => $request->input('admin_landing', 'default_value'),
        'delivery_partner' => json_encode($deliveryPartnerData),
    ]);

    return redirect()->back()->with('success', 'Page created successfully');
}

    public function edit($id)
    {
        $page = Page::findOrFail($id);
        return view('pages.edit', compact('page'));
    }

    public function update(Request $request, $id)
    {
        $page = Page::findOrFail($id);
        $page->update([
            'admin_landing' => $request->input('admin_landing'),
            'delivery_partner' => $request->input('delivery_partner'),
        ]);

        return redirect()->route('pages.index');
    }

    public function destroy($id)
    {
        $page = Page::findOrFail($id);
        $page->delete();
        return redirect()->route('pages.index');
    }
}
