<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ServiceHistory;
use Illuminate\Http\Request;

class ServiceController extends Controller
{
    /**
     * Hiển thị trang lịch sử dịch vụ
     */
    public function index()
    {
        // Lấy tất cả các dịch vụ sắp xếp theo thời gian giảm dần
        $services = ServiceHistory::with('user', 'gameService', 'servicePackage')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('admin.history.services', compact('services'));
    }

    /**
     * Cập nhật trạng thái dịch vụ
     */
    public function updateStatus($id, Request $request)
    {
        $validStatuses = ['pending', 'processing', 'completed', 'cancelled'];
        if (!in_array($request->status, $validStatuses)) {
            return redirect()->back()->with('error', 'Trạng thái không hợp lệ.');
        }

        $service = ServiceHistory::findOrFail($id);
        $service->status = $request->status;
        $service->save();

        return redirect()->route('admin.history.services')->with('success', 'Cập nhật trạng thái thành công.');
    }
}
