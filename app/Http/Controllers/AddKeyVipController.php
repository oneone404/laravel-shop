<?php

namespace App\Http\Controllers;

use App\Models\KeyVip;
use Illuminate\Http\Request;

class AddKeyVipController extends Controller
{
    public function showForm()
    {
        return view('admin.add-key-vip');
    }

    public function store(Request $request)
{
    // Validate dữ liệu đầu vào
    $request->validate([
        'game' => 'required|string|max:255',
        'key_value' => 'required|string', // Kiểm tra key_value là chuỗi
        'time_use' => 'required|integer',
        'price' => 'required|integer',
    ]);

    // Tách các key từ chuỗi nhập vào
    $keys = preg_split('/\r\n|\r|\n/', $request->key_value); // Tách key dựa trên các ký tự dòng mới (newline)

    // Loại bỏ các key trống và key trùng lặp
    $keys = array_map('trim', $keys); // Loại bỏ khoảng trắng thừa
    $keys = array_filter($keys); // Loại bỏ các key trống
    $keys = array_unique($keys); // Loại bỏ các key trùng lặp

    // Lưu từng key vào bảng key_vips
    foreach ($keys as $key_value) {
        KeyVip::create([
            'game' => $request->game,
            'key_value' => $key_value,
            'time_use' => $request->time_use,
            'price' => $request->price,
        ]);
    }

    return redirect()->route('add-key.show')->with('success', 'Các Key đã được thêm thành công.');
}

}
