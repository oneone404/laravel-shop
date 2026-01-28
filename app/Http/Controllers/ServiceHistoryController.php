<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ServiceHistory;

class ServiceHistoryController extends Controller
{
    public function index(Request $request)
    {
        // Lấy tất cả bản ghi, sau đó ẩn đi các trường không mong muốn
        $histories = ServiceHistory::all()->makeHidden(['game_account', 'game_password']);

        return response()->json($histories);
    }
}