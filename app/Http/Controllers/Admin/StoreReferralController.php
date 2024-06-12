<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\RefferalCode;

class StoreReferralController extends Controller
{
    public function index()
    {

        $referrals = RefferalCode::paginate(10);

        return view('admin-views.referrals.index',compact('referrals'));
    }

    public function create(Request $request){
        $request->validate([
            'referral_code' => 'required|string|unique:store_referral,referral_code',
        ]);

        $referral = new RefferalCode([
            'referral_code' => $request->get('referral_code'),
        ]);

        $referral->save();

        return redirect()->route('admin.business-settings.referrals.index')->with('success', 'Referral created successfully.');

    }
}
