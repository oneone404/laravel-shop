<?php

// app/Http/Controllers/UgPhoneController.php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\UgPhone;
use App\Models\UgPhoneHistory;
use Illuminate\Support\Facades\Auth;

class UgPhoneController extends Controller
{
    public function index()
    {
        $items = UgPhone::all();
        $histories = \App\Models\UgPhoneHistory::where('user_id', Auth::id())
                        ->orderBy('created_at', 'desc')
                        ->get();
    
        return view('user.ug-phone', compact('items', 'histories'));
    }

    public function purchase(Request $request)
    {
        $item = UgPhone::findOrFail($request->input('ug_phone_id'));

        UgPhoneHistory::create([
            'user_id' => Auth::id(),
            'sever' => $item->sever,
            'hansudung' => $item->hansudung,
            'price' => $item->price,
            'cauhinh' => $item->cauhinh,
        ]);

        // Optional: trừ tiền, xóa mã đã mua, v.v.
        $item->delete();

        return redirect()->back()->with('success', 'Đã mua mã thành công!');
    }
}
